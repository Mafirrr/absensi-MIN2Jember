<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $jenisGuru = $request->query('jenis_guru');

        $query = User::where('role', 'guru')->with(['kelasWali', 'kelasAjar']);

        if ($jenisGuru) {
            $query->where('jenis_guru', $jenisGuru);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('mata_pelajaran', 'like', "%{$search}%");
            });
        }

        $guruList = $query->orderBy('name', 'asc')->paginate(15)->withQueryString();
        $kelasList = Kelas::orderBy('tingkat', 'asc')->orderBy('nama_kelas', 'asc')->get();

        return view('tu.guru.index', compact('guruList', 'kelasList', 'search', 'jenisGuru'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'nip' => 'nullable|string|max:30|unique:users,nip',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:L,P',
            'jenis_guru' => 'nullable|in:guru_kelas,guru_mapel',
            'mata_pelajaran' => 'nullable|string|max:100',
            'wali_kelas_id' => 'nullable|exists:kelas,id',
            'kelas_ajar' => 'nullable|array',
            'kelas_ajar.*' => 'exists:kelas,id',
        ]);

        $jenisGuru = $validated['jenis_guru'] ?? (!empty($validated['wali_kelas_id']) ? 'guru_kelas' : 'guru_kelas');

        $guru = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'guru',
            'jenis_guru' => $jenisGuru,
            'mata_pelajaran' => $validated['mata_pelajaran'] ?? ($jenisGuru === 'guru_kelas' ? 'Guru Kelas' : null),
            'nip' => !empty($validated['nip']) ? trim($validated['nip']) : null,
            'phone' => !empty($validated['phone']) ? trim($validated['phone']) : null,
            'gender' => $validated['gender'] ?? 'L',
        ]);

        // Penugasan Wali Kelas jika dipilih
        if (!empty($validated['wali_kelas_id'])) {
            Kelas::where('id', $validated['wali_kelas_id'])->update(['wali_kelas_id' => $guru->id]);
            $guru->kelasAjar()->syncWithoutDetaching([$validated['wali_kelas_id']]);
        }

        // Penugasan Kelas Ajar (misal Guru Mapel)
        if (!empty($validated['kelas_ajar'])) {
            $attachData = [];
            foreach ($validated['kelas_ajar'] as $kid) {
                $attachData[$kid] = ['mata_pelajaran' => $guru->mata_pelajaran];
            }
            $guru->kelasAjar()->syncWithoutDetaching($attachData);
        }

        return redirect()->route('tu.guru.index')->with('success', "Data guru {$guru->name} berhasil ditambahkan!");
    }

    public function update(Request $request, User $guru)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email,' . $guru->id,
            'nip' => 'nullable|string|max:30|unique:users,nip,' . $guru->id,
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:L,P',
            'password' => 'nullable|string|min:6',
            'jenis_guru' => 'nullable|in:guru_kelas,guru_mapel',
            'mata_pelajaran' => 'nullable|string|max:100',
            'wali_kelas_id' => 'nullable|exists:kelas,id',
            'kelas_ajar' => 'nullable|array',
            'kelas_ajar.*' => 'exists:kelas,id',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nip' => !empty($validated['nip']) ? trim($validated['nip']) : null,
            'phone' => !empty($validated['phone']) ? trim($validated['phone']) : null,
            'gender' => $validated['gender'] ?? 'L',
        ];

        if (isset($validated['jenis_guru'])) {
            $updateData['jenis_guru'] = $validated['jenis_guru'];
        }

        if (array_key_exists('mata_pelajaran', $validated)) {
            $updateData['mata_pelajaran'] = $validated['mata_pelajaran'];
        }

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $guru->update($updateData);

        // Update penugasan wali kelas
        if ($request->has('wali_kelas_id')) {
            Kelas::where('wali_kelas_id', $guru->id)->update(['wali_kelas_id' => null]);
            if (!empty($validated['wali_kelas_id'])) {
                Kelas::where('id', $validated['wali_kelas_id'])->update(['wali_kelas_id' => $guru->id]);
                $guru->kelasAjar()->syncWithoutDetaching([$validated['wali_kelas_id']]);
            }
        }

        // Update penugasan kelas ajar
        if ($request->has('kelas_ajar')) {
            $attachData = [];
            foreach ($validated['kelas_ajar'] ?? [] as $kid) {
                $attachData[$kid] = ['mata_pelajaran' => $guru->mata_pelajaran];
            }
            $guru->kelasAjar()->sync($attachData);
            if (!empty($validated['wali_kelas_id'])) {
                $guru->kelasAjar()->syncWithoutDetaching([$validated['wali_kelas_id']]);
            }
        }

        return redirect()->route('tu.guru.index')->with('success', "Data guru {$guru->name} berhasil diperbarui!");
    }

    public function destroy(User $guru)
    {
        if ($guru->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($guru->role !== 'guru') {
            return back()->with('error', 'Hanya akun dengan peran guru yang dapat dihapus.');
        }

        $name = $guru->name;

        // Reset wali kelas jika terdaftar
        Kelas::where('wali_kelas_id', $guru->id)->update(['wali_kelas_id' => null]);

        // Lepas relasi kelas ajar (pivot)
        $guru->kelasAjar()->detach();

        // Null-kan guru_id pada absensi yang pernah diinput agar data absensi tetap utuh
        \App\Models\Absensi::where('guru_id', $guru->id)->update(['guru_id' => null]);

        $guru->delete();

        return redirect()->route('tu.guru.index')->with('success', "Guru {$name} berhasil dihapus.");
    }
}

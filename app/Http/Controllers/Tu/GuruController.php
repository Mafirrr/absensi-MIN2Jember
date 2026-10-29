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

        $query = User::where('role', 'guru')->with(['kelasWali', 'kelasAjar']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        $guruList = $query->orderBy('name', 'asc')->paginate(10)->withQueryString();
        $kelasList = Kelas::orderBy('nama_kelas', 'asc')->get();

        return view('tu.guru.index', compact('guruList', 'kelasList', 'search'));
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
        ]);

        $validated['role'] = 'guru';
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('tu.guru.index')->with('success', "Guru {$request->name} berhasil ditambahkan!");
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
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $guru->update($validated);

        return redirect()->route('tu.guru.index')->with('success', "Data guru {$guru->name} berhasil diperbarui!");
    }

    public function destroy(User $guru)
    {
        $name = $guru->name;

        // Reset wali kelas if assigned
        Kelas::where('wali_kelas_id', $guru->id)->update(['wali_kelas_id' => null]);
        $guru->delete();

        return redirect()->route('tu.guru.index')->with('success', "Guru {$name} berhasil dihapus.");
    }
}

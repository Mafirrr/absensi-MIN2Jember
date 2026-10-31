<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Kelas::with(['waliKelas'])->withCount('siswa');

        if ($search) {
            $query->where('nama_kelas', 'like', "%{$search}%")
                  ->orWhere('tingkat', 'like', "%{$search}%")
                  ->orWhereHas('waliKelas', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        $kelasList = $query->orderBy('tingkat', 'asc')->orderBy('nama_kelas', 'asc')->paginate(10)->withQueryString();
        $gurus = User::where('role', 'guru')->orderBy('name', 'asc')->get();

        return view('tu.kelas.index', compact('kelasList', 'gurus', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:50|unique:kelas,nama_kelas',
            'tingkat' => 'required|string|max:10',
            'jurusan' => 'nullable|string|max:100',
            'wali_kelas_id' => 'nullable|exists:users,id',
            'tahun_ajaran' => 'required|string|max:20',
        ]);

        Kelas::create($validated);

        return redirect()->route('tu.kelas.index')->with('success', "Kelas {$request->nama_kelas} berhasil ditambahkan!");
    }

    public function show(Kelas $kela)
    {
        $kelas = $kela->load(['waliKelas', 'siswa' => function ($q) {
            $q->orderBy('no_absen', 'asc')->orderBy('nama_lengkap', 'asc');
        }]);

        return view('tu.kelas.show', compact('kelas'));
    }

    public function update(Request $request, Kelas $kela)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:50|unique:kelas,nama_kelas,' . $kela->id,
            'tingkat' => 'required|string|max:10',
            'jurusan' => 'nullable|string|max:100',
            'wali_kelas_id' => 'nullable|exists:users,id',
            'tahun_ajaran' => 'required|string|max:20',
        ]);

        $kela->update($validated);

        return redirect()->route('tu.kelas.index')->with('success', "Data kelas {$kela->nama_kelas} berhasil diperbarui!");
    }

    public function destroy(Kelas $kela)
    {
        $nama = $kela->nama_kelas;

        if ($kela->siswa()->count() > 0) {
            return back()->with('error', "Kelas {$nama} tidak dapat dihapus karena masih memiliki {$kela->siswa()->count()} siswa.");
        }

        if ($kela->absensi()->count() > 0) {
            return back()->with('error', "Kelas {$nama} tidak dapat dihapus karena masih memiliki riwayat data absensi.");
        }

        // Lepas guru pengampu pivot
        $kela->guruPengampu()->detach();

        $kela->delete();

        return redirect()->route('tu.kelas.index')->with('success', "Kelas {$nama} berhasil dihapus.");
    }
}


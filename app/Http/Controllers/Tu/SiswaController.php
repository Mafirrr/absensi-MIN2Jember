<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Services\SiswaImportService;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    protected SiswaImportService $importService;

    public function __construct(SiswaImportService $importService)
    {
        $this->importService = $importService;
    }

    public function downloadTemplate()
    {
        return $this->importService->generateTemplate();
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'tipe_input' => 'required|in:text,file',
            'raw_text' => 'required_if:tipe_input,text|nullable|string',
            'file_excel' => 'required_if:tipe_input,file|nullable|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        $kelasId = (int) $request->kelas_id;

        if ($request->tipe_input === 'file' && $request->hasFile('file_excel')) {
            $result = $this->importService->importFromFile($request->file('file_excel'), $kelasId);
        } else {
            $result = $this->importService->importFromText($request->raw_text ?? '', $kelasId);
        }

        $msg = "Berhasil menambahkan {$result['success_count']} siswa sekaligus!";
        if ($result['existing_count'] > 0) {
            $msg .= " ({$result['existing_count']} siswa dilewati karena NIS sudah terdaftar).";
        }

        if (!empty($result['errors'])) {
            return redirect()->route('tu.siswa.index', ['kelas_id' => $kelasId])
                ->with('success', $msg)
                ->with('warning', implode(' | ', array_slice($result['errors'], 0, 3)));
        }

        return redirect()->route('tu.siswa.index', ['kelas_id' => $kelasId])
            ->with('success', $msg);
    }
    public function index(Request $request)
    {
        $search = $request->query('search');
        $kelasId = $request->query('kelas_id');
        $status = $request->query('status');

        $query = Siswa::with('kelas');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $siswaList = $query->orderBy('nama_lengkap', 'asc')->paginate(15)->withQueryString();
        $kelasList = Kelas::orderBy('tingkat', 'asc')->orderBy('nama_kelas', 'asc')->get();

        return view('tu.siswa.index', compact('siswaList', 'kelasList', 'search', 'kelasId', 'status'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis' => 'required|string|max:20|unique:siswa,nis',
            'nisn' => 'nullable|string|max:20|unique:siswa,nisn',
            'nama_lengkap' => 'required|string|max:150',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas_id' => 'required|exists:kelas,id',
            'no_absen' => 'nullable|integer|min:1',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'no_telepon_ortu' => 'nullable|string|max:25',
            'status' => 'required|in:aktif,nonaktif,lulus,pindah',
        ]);

        Siswa::create($validated);

        return redirect()->route('tu.siswa.index', ['kelas_id' => $request->kelas_id])
            ->with('success', "Siswa {$request->nama_lengkap} berhasil ditambahkan!");
    }

    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nis' => 'required|string|max:20|unique:siswa,nis,' . $siswa->id,
            'nisn' => 'nullable|string|max:20|unique:siswa,nisn,' . $siswa->id,
            'nama_lengkap' => 'required|string|max:150',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas_id' => 'required|exists:kelas,id',
            'no_absen' => 'nullable|integer|min:1',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'no_telepon_ortu' => 'nullable|string|max:25',
            'status' => 'required|in:aktif,nonaktif,lulus,pindah',
        ]);

        $siswa->update($validated);

        return redirect()->route('tu.siswa.index', ['kelas_id' => $request->kelas_id])
            ->with('success', "Data siswa {$siswa->nama_lengkap} berhasil diperbarui!");
    }

    public function destroy(Siswa $siswa)
    {
        $nama = $siswa->nama_lengkap;
        $totalAbsensi = $siswa->absensi()->count();

        if ($totalAbsensi > 0) {
            return back()->with('error', "Siswa {$nama} tidak dapat dihapus karena sudah memiliki {$totalAbsensi} riwayat presensi. Silakan ubah status siswa menjadi 'nonaktif', 'pindah', atau 'lulus' pada tombol Edit.");
        }

        $siswa->delete();

        return back()->with('success', "Siswa {$nama} berhasil dihapus.");
    }
}


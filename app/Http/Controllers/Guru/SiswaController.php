<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Services\SiswaImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    protected SiswaImportService $importService;

    public function __construct(SiswaImportService $importService)
    {
        $this->importService = $importService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $accessibleKelasIds = $user->getAccessibleKelasIds();

        $kelasList = Kelas::whereIn('id', $accessibleKelasIds)->get();

        if ($kelasList->isEmpty()) {
            return view('guru.siswa.index', [
                'kelasList' => collect(),
                'selectedKelas' => null,
                'siswaList' => collect(),
                'search' => '',
                'kelasId' => null,
            ])->with('error', 'Anda belum ditugaskan sebagai wali kelas atau guru pengampu kelas.');
        }

        $kelasId = $request->query('kelas_id', $kelasList->first()->id);

        if (!in_array($kelasId, $accessibleKelasIds)) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat siswa di kelas ini.');
        }

        $selectedKelas = $kelasList->firstWhere('id', $kelasId);
        $search = $request->query('search');

        $query = Siswa::where('kelas_id', $selectedKelas->id);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        $siswaList = $query->orderBy('no_absen', 'asc')
            ->orderBy('nama_lengkap', 'asc')
            ->paginate(20)
            ->withQueryString();

        return view('guru.siswa.index', compact('kelasList', 'selectedKelas', 'siswaList', 'search', 'kelasId'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $accessibleKelasIds = $user->getAccessibleKelasIds();

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
        ]);

        if (!in_array($validated['kelas_id'], $accessibleKelasIds)) {
            abort(403, 'Anda tidak berhak menambahkan siswa ke kelas ini.');
        }

        $validated['status'] = 'aktif';

        if (empty($validated['no_absen'])) {
            $validated['no_absen'] = Siswa::where('kelas_id', $validated['kelas_id'])->max('no_absen') + 1;
        }

        Siswa::create($validated);

        return redirect()->route('guru.siswa.index', ['kelas_id' => $validated['kelas_id']])
            ->with('success', "Siswa {$validated['nama_lengkap']} berhasil ditambahkan ke kelas dan langsung terdata di TU!");
    }

    public function bulkStore(Request $request)
    {
        $user = Auth::user();
        $accessibleKelasIds = $user->getAccessibleKelasIds();

        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'tipe_input' => 'required|in:text,file',
            'raw_text' => 'required_if:tipe_input,text|nullable|string',
            'file_excel' => 'required_if:tipe_input,file|nullable|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        $kelasId = (int) $request->kelas_id;

        if (!in_array($kelasId, $accessibleKelasIds)) {
            abort(403, 'Anda tidak berhak menambahkan siswa ke kelas ini.');
        }

        if ($request->tipe_input === 'file' && $request->hasFile('file_excel')) {
            $result = $this->importService->importFromFile($request->file('file_excel'), $kelasId);
        } else {
            $result = $this->importService->importFromText($request->raw_text ?? '', $kelasId);
        }

        $msg = "Berhasil menambahkan {$result['success_count']} siswa sekaligus ke kelas Anda! Data otomatis langsung tersinkron ke role TU.";
        if ($result['existing_count'] > 0) {
            $msg .= " ({$result['existing_count']} siswa dilewati karena NIS sudah ada).";
        }

        return redirect()->route('guru.siswa.index', ['kelas_id' => $kelasId])
            ->with('success', $msg);
    }

    public function downloadTemplate()
    {
        return $this->importService->generateTemplate();
    }
}

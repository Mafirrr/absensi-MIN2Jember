<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $accessibleKelasIds = $user->getAccessibleKelasIds();

        $kelasList = Kelas::whereIn('id', $accessibleKelasIds)->get();

        if ($kelasList->isEmpty()) {
            return view('guru.absensi.index', [
                'kelasList' => collect(),
                'selectedKelas' => null,
                'tanggal' => Carbon::today()->format('Y-m-d'),
                'siswaList' => collect(),
                'existingAbsensi' => collect(),
            ])->with('error', 'Anda belum ditugaskan sebagai wali kelas atau pengampu kelas apapun.');
        }

        $tanggal = $request->query('tanggal', Carbon::today()->format('Y-m-d'));
        $kelasId = $request->query('kelas_id', $kelasList->first()->id);

        // Security check: Ensure guru only accesses their assigned classes
        if (!in_array($kelasId, $accessibleKelasIds)) {
            abort(403, 'Anda tidak memiliki hak akses untuk menginput absensi di kelas ini.');
        }

        $selectedKelas = $kelasList->firstWhere('id', $kelasId);

        $siswaList = Siswa::where('kelas_id', $selectedKelas->id)
            ->where('status', 'aktif')
            ->orderBy('no_absen', 'asc')
            ->orderBy('nama_lengkap', 'asc')
            ->get();

        // Get existing attendance records for this date
        $existingAbsensi = Absensi::where('kelas_id', $selectedKelas->id)
            ->where('tanggal', $tanggal)
            ->get()
            ->keyBy('siswa_id');

        return view('guru.absensi.index', compact(
            'kelasList',
            'selectedKelas',
            'tanggal',
            'siswaList',
            'existingAbsensi'
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $accessibleKelasIds = $user->getAccessibleKelasIds();

        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'tanggal' => 'required|date',
            'absensi' => 'required|array',
            'absensi.*.status' => 'required|in:hadir,izin,sakit,alpa',
            'absensi.*.catatan' => 'nullable|string|max:255',
        ]);

        $kelasId = $validated['kelas_id'];
        $tanggal = $validated['tanggal'];

        // Security check
        if (!in_array($kelasId, $accessibleKelasIds)) {
            abort(403, 'Anda tidak berhak menyimpan absensi untuk kelas ini.');
        }

        DB::transaction(function () use ($validated, $kelasId, $tanggal, $user) {
            foreach ($validated['absensi'] as $siswaId => $data) {
                // Ensure student belongs to this class
                $siswa = Siswa::where('id', $siswaId)->where('kelas_id', $kelasId)->first();
                if (!$siswa) {
                    continue;
                }

                Absensi::updateOrCreate(
                    [
                        'siswa_id' => $siswaId,
                        'tanggal' => $tanggal,
                    ],
                    [
                        'kelas_id' => $kelasId,
                        'status' => $data['status'],
                        'catatan' => $data['catatan'] ?? null,
                        'guru_id' => $user->id,
                    ]
                );
            }
        });

        $kelas = Kelas::find($kelasId);
        $tglFormatted = Carbon::parse($tanggal)->translatedFormat('d F Y');

        return redirect()->route('guru.absensi.index', [
            'kelas_id' => $kelasId,
            'tanggal' => $tanggal,
        ])->with('success', "Absensi kelas {$kelas->nama_kelas} untuk tanggal {$tglFormatted} berhasil disimpan!");
    }
}


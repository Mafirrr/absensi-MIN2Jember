<?php

namespace Tests\Feature;

use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AbsensiSystemTest extends TestCase
{
    use RefreshDatabase;

    protected User $tuUser;
    protected User $guru1;
    protected User $guru2;
    protected Kelas $kelas1;
    protected Kelas $kelas2;
    protected Siswa $siswa1;
    protected Siswa $siswa2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tuUser = User::create([
            'name' => 'Staf Tata Usaha',
            'email' => 'tu@test.com',
            'password' => bcrypt('password123'),
            'role' => 'tu',
        ]);

        $this->guru1 = User::create([
            'name' => 'Guru Wali Kelas X',
            'email' => 'guru1@test.com',
            'password' => bcrypt('password123'),
            'role' => 'guru',
        ]);

        $this->guru2 = User::create([
            'name' => 'Guru Wali Kelas XI',
            'email' => 'guru2@test.com',
            'password' => bcrypt('password123'),
            'role' => 'guru',
        ]);

        $this->kelas1 = Kelas::create([
            'nama_kelas' => 'X RPL 1',
            'tingkat' => '10',
            'jurusan' => 'RPL',
            'wali_kelas_id' => $this->guru1->id,
            'tahun_ajaran' => '2025/2026',
        ]);

        $this->kelas2 = Kelas::create([
            'nama_kelas' => 'XI RPL 1',
            'tingkat' => '11',
            'jurusan' => 'RPL',
            'wali_kelas_id' => $this->guru2->id,
            'tahun_ajaran' => '2025/2026',
        ]);

        $this->siswa1 = Siswa::create([
            'nis' => '1001',
            'nama_lengkap' => 'Ahmad Siswa',
            'jenis_kelamin' => 'L',
            'kelas_id' => $this->kelas1->id,
            'status' => 'aktif',
        ]);

        $this->siswa2 = Siswa::create([
            'nis' => '2001',
            'nama_lengkap' => 'Budi Siswa',
            'jenis_kelamin' => 'L',
            'kelas_id' => $this->kelas2->id,
            'status' => 'aktif',
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/');
        $response->assertRedirect(route('login'));

        $responseTu = $this->get(route('tu.dashboard'));
        $responseTu->assertRedirect(route('login'));

        $responseGuru = $this->get(route('guru.dashboard'));
        $responseGuru->assertRedirect(route('login'));
    }

    public function test_tu_can_login_and_access_tu_dashboard(): void
    {
        $response = $this->post(route('login'), [
            'email' => 'tu@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('tu.dashboard'));
        $this->assertAuthenticatedAs($this->tuUser);

        $dashResponse = $this->get(route('tu.dashboard'));
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Panel Tata Usaha');
    }

    public function test_guru_can_login_and_access_guru_dashboard(): void
    {
        $response = $this->post(route('login'), [
            'email' => 'guru1@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('guru.dashboard'));
        $this->assertAuthenticatedAs($this->guru1);

        $dashResponse = $this->get(route('guru.dashboard'));
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Kelas X RPL 1');
        $dashResponse->assertSee('Statistik Kehadiran Per Status Siswa');
    }

    public function test_role_security_guru_cannot_access_tu_routes(): void
    {
        $this->actingAs($this->guru1);

        $response = $this->get(route('tu.dashboard'));
        $response->assertStatus(403);

        $response = $this->get(route('tu.kelas.index'));
        $response->assertStatus(403);

        $response = $this->get(route('tu.siswa.index'));
        $response->assertStatus(403);
    }

    public function test_guru_can_only_access_their_own_class_attendance(): void
    {
        $this->actingAs($this->guru1);

        // Accessing their own class is allowed
        $response = $this->get(route('guru.absensi.index', ['kelas_id' => $this->kelas1->id]));
        $response->assertStatus(200);
        $response->assertSee('Ahmad Siswa');

        // Accessing other class is forbidden
        $responseForbidden = $this->get(route('guru.absensi.index', ['kelas_id' => $this->kelas2->id]));
        $responseForbidden->assertStatus(403);
    }

    public function test_guru_can_record_attendance_successfully(): void
    {
        $this->actingAs($this->guru1);

        $today = Carbon::today()->format('Y-m-d');

        $response = $this->post(route('guru.absensi.store'), [
            'kelas_id' => $this->kelas1->id,
            'tanggal' => $today,
            'absensi' => [
                $this->siswa1->id => [
                    'status' => 'hadir',
                    'catatan' => 'Hadir tepat waktu',
                ],
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('absensi', [
            'siswa_id' => $this->siswa1->id,
            'kelas_id' => $this->kelas1->id,
            'tanggal' => $today,
            'status' => 'hadir',
            'catatan' => 'Hadir tepat waktu',
            'guru_id' => $this->guru1->id,
        ]);
    }

    public function test_guru_cannot_store_attendance_for_other_class(): void
    {
        $this->actingAs($this->guru1);

        $today = Carbon::today()->format('Y-m-d');

        $response = $this->post(route('guru.absensi.store'), [
            'kelas_id' => $this->kelas2->id,
            'tanggal' => $today,
            'absensi' => [
                $this->siswa2->id => [
                    'status' => 'hadir',
                ],
            ],
        ]);

        $response->assertStatus(403);
    }

    public function test_tu_can_manage_kelas_and_siswa(): void
    {
        $this->actingAs($this->tuUser);

        // Add class
        $resKelas = $this->post(route('tu.kelas.store'), [
            'nama_kelas' => 'XII TKJ 2',
            'tingkat' => '12',
            'jurusan' => 'TKJ',
            'tahun_ajaran' => '2025/2026',
        ]);
        $resKelas->assertRedirect(route('tu.kelas.index'));
        $this->assertDatabaseHas('kelas', ['nama_kelas' => 'XII TKJ 2']);

        // Add student
        $resSiswa = $this->post(route('tu.siswa.store'), [
            'nis' => '5555',
            'nama_lengkap' => 'Siswa Baru',
            'jenis_kelamin' => 'P',
            'kelas_id' => $this->kelas1->id,
            'status' => 'aktif',
        ]);
        $resSiswa->assertRedirect();
        $this->assertDatabaseHas('siswa', ['nis' => '5555', 'nama_lengkap' => 'Siswa Baru']);
    }

    public function test_tu_can_view_rekap_harian_and_bulanan(): void
    {
        $this->actingAs($this->tuUser);

        $resHarian = $this->get(route('tu.rekap.harian'));
        $resHarian->assertStatus(200);

        $resBulanan = $this->get(route('tu.rekap.bulanan', ['kelas_id' => $this->kelas1->id]));
        $resBulanan->assertStatus(200);
    }
}

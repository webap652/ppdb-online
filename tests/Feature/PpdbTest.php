<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PpdbTest extends TestCase
{
    private function loginAs(string $email): self
    {
        $this->post('/login', ['login' => $email, 'password' => 'password']);

        return $this;
    }

    public function test_public_pages_render(): void
    {
        $this->get('/')->assertOk()->assertSee('PPDB Online')->assertSee('Jalur & Kuota', false);
        $this->get('/daftar')->assertOk()->assertSee('Formulir Pendaftaran');
        $this->get('/login')->assertOk();
        $this->get('/pengumuman')->assertOk();
        $this->get('/pengumuman?no=PPDB-2027-0004')->assertOk()->assertSee('Hasil belum diumumkan');
        $this->get('/pengumuman?no=XXX')->assertOk()->assertSee('Nomor tidak ditemukan');
    }

    public function test_guests_are_redirected_and_roles_are_enforced(): void
    {
        $this->get('/akun')->assertRedirect('/login');
        $this->get('/panel')->assertRedirect('/login');

        $this->loginAs('pendaftar@ppdb.test');
        $this->get('/panel')->assertForbidden();

        $this->post('/logout');
        $this->loginAs('operator@ppdb.test');
        $this->get('/panel')->assertOk();
        $this->get('/panel/pengaturan')->assertForbidden();
        $this->get('/akun')->assertForbidden();
    }

    public function test_login_by_email_or_registration_number(): void
    {
        $this->post('/login', ['login' => 'admin@ppdb.test', 'password' => 'salah'])->assertSessionHasErrors('login');
        $this->post('/login', ['login' => 'admin@ppdb.test', 'password' => 'password'])->assertRedirect('/panel');
        $this->post('/logout');
        $this->post('/login', ['login' => 'ppdb-2027-0001', 'password' => 'password'])->assertRedirect('/akun');
        $this->get('/login')->assertRedirect('/akun');
    }

    public function test_registration_flow(): void
    {
        $data = [
            'nama' => 'Siswa Baru', 'nisn' => '0149999999', 'jk' => 'L', 'tempat_lahir' => 'Tiom',
            'tanggal_lahir' => '2014-01-01', 'agama' => 'Kristen', 'distrik' => 'Pirime', 'alamat' => 'Kampung Pirime',
            'nama_ayah' => 'Ayah', 'nama_ibu' => 'Ibu', 'pekerjaan_ortu' => 'Petani', 'hp' => '081234567890',
            'sekolah_asal' => 'SD Inpres Pirime', 'tahun_lulus' => '2027', 'nilai' => '85.5', 'jalur' => 'domisili',
            'email' => 'baru@contoh.id', 'password' => 'rahasia123', 'password_confirmation' => 'rahasia123', 'setuju' => '1',
        ];

        $this->post('/daftar', array_merge($data, ['nisn' => '0141234501']))->assertSessionHasErrors('nisn');
        $this->post('/daftar', $data)->assertRedirect('/daftar/berhasil');
        $this->get('/daftar/berhasil')->assertOk()->assertSee('PPDB-2027-0011');

        // Pendaftar baru langsung login dan bisa unggah berkas
        $this->get('/akun')->assertOk()->assertSee('Siswa Baru');
        $this->post('/akun/berkas', ['jenis' => 'kk', 'file' => UploadedFile::fake()->create('kk.pdf', 100, 'application/pdf')])
            ->assertSessionHas('success');
        $this->get('/akun/berkas')->assertSee('Diperiksa');

        // Bisa login ulang dengan password sendiri
        $this->post('/logout');
        $this->post('/login', ['login' => 'baru@contoh.id', 'password' => 'rahasia123'])->assertRedirect('/akun');
    }

    public function test_all_panel_pages_render(): void
    {
        $this->loginAs('admin@ppdb.test');

        foreach (['/panel', '/panel/pendaftar', '/panel/pendaftar?status=menunggu&q=yosia', '/panel/pendaftar/PPDB-2027-0001',
            '/panel/seleksi', '/panel/seleksi?jalur=afirmasi', '/panel/pengguna', '/panel/pengaturan'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_pendaftar_pages_render(): void
    {
        $this->loginAs('pendaftar@ppdb.test');

        foreach (['/akun', '/akun/biodata', '/akun/berkas', '/akun/kartu'] as $url) {
            $this->get($url)->assertOk()->assertSee('Yosia Kogoya');
        }
    }

    public function test_verification_selection_and_announcement(): void
    {
        $this->loginAs('admin@ppdb.test');

        // Tidak bisa diverifikasi jika ada berkas belum valid
        $this->post('/panel/pendaftar/PPDB-2027-0001/verifikasi', ['aksi' => 'terverifikasi'])->assertSessionHasErrors('aksi');
        $this->post('/panel/pendaftar/PPDB-2027-0001/verifikasi', ['aksi' => 'perbaikan'])->assertSessionHasErrors('catatan');

        $this->post('/panel/pendaftar/PPDB-2027-0001/verifikasi', [
            'aksi' => 'terverifikasi',
            'berkas' => ['kk' => 'valid', 'akta' => 'valid', 'ijazah' => 'valid', 'foto' => 'valid'],
        ])->assertSessionHas('success');
        $this->get('/panel/pendaftar/PPDB-2027-0001')->assertSee('Terverifikasi');

        $this->post('/panel/seleksi/PPDB-2027-0001', ['status' => 'diterima'])->assertSessionHas('success');
        $this->post('/panel/seleksi-umumkan')->assertSessionHas('success');

        $this->get('/pengumuman?no=PPDB-2027-0001')->assertSee('DITERIMA');
    }

    public function test_admin_manages_accounts_and_settings(): void
    {
        $this->loginAs('admin@ppdb.test');

        $this->post('/panel/pengguna', ['nama' => 'Panitia 2', 'email' => 'panitia2@ppdb.test', 'role' => 'operator', 'password' => 'rahasia123'])
            ->assertSessionHas('success');
        $this->post('/panel/pengguna/status', ['email' => 'operator@ppdb.test'])->assertSessionHas('success');

        $this->put('/panel/pengaturan', [
            'tahun_ajaran' => '2028/2029', 'kuota' => 64,
            'jadwal' => [['tahap' => 'Pendaftaran', 'mulai' => '2026-10-01', 'selesai' => '2026-10-31']],
        ])->assertSessionHas('success');
        $this->get('/')->assertSee('2028/2029')->assertSee('Ditutup');

        $this->post('/logout');
        $this->post('/login', ['login' => 'operator@ppdb.test', 'password' => 'password'])->assertSessionHasErrors('login');
        $this->post('/login', ['login' => 'panitia2@ppdb.test', 'password' => 'rahasia123'])->assertRedirect('/panel');
    }
}

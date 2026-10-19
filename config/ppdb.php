<?php

/*
|--------------------------------------------------------------------------
| Data PPDB (PROTOTYPE)
|--------------------------------------------------------------------------
| Seluruh data di bawah ini adalah data dummy untuk prototype tanpa database.
| Perubahan (pendaftaran baru, verifikasi, pengaturan) disimpan di session
| lewat App\Support\Ppdb, sehingga hilang ketika session berakhir.
*/

return [

    'sekolah' => [
        'nama' => 'SMP Negeri 1 Pirime',
        'npsn' => '60303xxx',
        'alamat' => 'Distrik Pirime, Kabupaten Lanny Jaya, Papua Pegunungan',
        'telepon' => '0812-0000-0000',
        'email' => 'ppdb@smpn1pirime.sch.id',
        'logo' => 'images/logo sekolah.png',
    ],

    'pengaturan' => [
        'tahun_ajaran' => '2027/2028',
        'kuota' => 96,
        'pendaftaran_dibuka' => true,
        'pengumuman_dirilis' => false,
    ],

    'jadwal' => [
        ['tahap' => 'Pendaftaran Online', 'mulai' => '2026-10-01', 'selesai' => '2026-10-31'],
        ['tahap' => 'Verifikasi Berkas', 'mulai' => '2026-11-01', 'selesai' => '2026-11-07'],
        ['tahap' => 'Pengumuman Hasil', 'mulai' => '2026-11-10', 'selesai' => '2026-11-10'],
        ['tahap' => 'Daftar Ulang', 'mulai' => '2026-11-11', 'selesai' => '2026-11-15'],
    ],

    // persen = porsi dari total kuota
    'jalur' => [
        'domisili' => ['label' => 'Domisili', 'persen' => 50, 'ket' => 'Calon siswa yang berdomisili di sekitar sekolah.'],
        'afirmasi' => ['label' => 'Afirmasi', 'persen' => 25, 'ket' => 'Calon siswa dari keluarga kurang mampu (KIP/PKH).'],
        'prestasi' => ['label' => 'Prestasi', 'persen' => 20, 'ket' => 'Berdasarkan nilai rapor atau prestasi lomba.'],
        'mutasi' => ['label' => 'Mutasi', 'persen' => 5, 'ket' => 'Perpindahan tugas orang tua/wali.'],
    ],

    // wajib = harus diunggah sebelum diverifikasi
    'berkas' => [
        'kk' => ['label' => 'Kartu Keluarga', 'wajib' => true],
        'akta' => ['label' => 'Akta Kelahiran', 'wajib' => true],
        'ijazah' => ['label' => 'Ijazah / SKL SD', 'wajib' => true],
        'foto' => ['label' => 'Pas Foto 3x4', 'wajib' => true],
        'rapor' => ['label' => 'Rapor Kelas 4–6', 'wajib' => false],
        'kip' => ['label' => 'KIP / PKH (jalur afirmasi)', 'wajib' => false],
    ],

    'persyaratan' => [
        'Lulus SD/MI atau sederajat',
        'Usia maksimal 15 tahun pada 1 Juli',
        'Memiliki NISN',
        'Mengunggah berkas yang diminta',
    ],

    // Akun demo. Password ditulis polos KHUSUS untuk prototype.
    'akun' => [
        ['nama' => 'Administrator', 'email' => 'admin@ppdb.test', 'password' => 'password', 'role' => 'admin', 'aktif' => true],
        ['nama' => 'Panitia Seleksi', 'email' => 'operator@ppdb.test', 'password' => 'password', 'role' => 'operator', 'aktif' => true],
        ['nama' => 'Yosia Kogoya', 'email' => 'pendaftar@ppdb.test', 'password' => 'password', 'role' => 'pendaftar', 'aktif' => true, 'no' => 'PPDB-2027-0001'],
    ],

    'pendaftar' => [
        ['no' => 'PPDB-2027-0001', 'nama' => 'Yosia Kogoya', 'nisn' => '0141234501', 'jk' => 'L', 'tempat_lahir' => 'Tiom', 'tanggal_lahir' => '2014-03-12', 'agama' => 'Kristen', 'distrik' => 'Pirime', 'alamat' => 'Kampung Pirime', 'sekolah_asal' => 'SD Inpres Pirime', 'tahun_lulus' => '2027', 'nilai' => 84.5, 'jalur' => 'domisili', 'nama_ayah' => 'Markus Kogoya', 'nama_ibu' => 'Ester Wenda', 'pekerjaan_ortu' => 'Petani', 'hp' => '081200000001', 'email' => 'pendaftar@ppdb.test', 'tanggal_daftar' => '2026-10-02', 'status' => 'menunggu', 'catatan' => null,
            'berkas' => ['kk' => 'valid', 'akta' => 'valid', 'ijazah' => 'menunggu', 'foto' => 'menunggu']],
        ['no' => 'PPDB-2027-0002', 'nama' => 'Mince Tabuni', 'nisn' => '0141234502', 'jk' => 'P', 'tempat_lahir' => 'Pirime', 'tanggal_lahir' => '2014-07-21', 'agama' => 'Kristen', 'distrik' => 'Pirime', 'alamat' => 'Kampung Yugumena', 'sekolah_asal' => 'SD YPPGI Pirime', 'tahun_lulus' => '2027', 'nilai' => 88.2, 'jalur' => 'prestasi', 'nama_ayah' => 'Yakob Tabuni', 'nama_ibu' => 'Maria Murib', 'pekerjaan_ortu' => 'Guru', 'hp' => '081200000002', 'email' => 'mince@contoh.id', 'tanggal_daftar' => '2026-10-02', 'status' => 'terverifikasi', 'catatan' => null,
            'berkas' => ['kk' => 'valid', 'akta' => 'valid', 'ijazah' => 'valid', 'foto' => 'valid', 'rapor' => 'valid']],
        ['no' => 'PPDB-2027-0003', 'nama' => 'Otis Wenda', 'nisn' => '0141234503', 'jk' => 'L', 'tempat_lahir' => 'Wamena', 'tanggal_lahir' => '2013-11-05', 'agama' => 'Kristen', 'distrik' => 'Balingga', 'alamat' => 'Kampung Balingga', 'sekolah_asal' => 'SD Inpres Balingga', 'tahun_lulus' => '2027', 'nilai' => 78.0, 'jalur' => 'afirmasi', 'nama_ayah' => 'Nikolas Wenda', 'nama_ibu' => 'Rut Yikwa', 'pekerjaan_ortu' => 'Petani', 'hp' => '081200000003', 'email' => 'otis@contoh.id', 'tanggal_daftar' => '2026-10-03', 'status' => 'perbaikan', 'catatan' => 'Foto KIP buram, mohon unggah ulang.',
            'berkas' => ['kk' => 'valid', 'akta' => 'valid', 'ijazah' => 'valid', 'foto' => 'valid', 'kip' => 'ditolak']],
        ['no' => 'PPDB-2027-0004', 'nama' => 'Debora Murib', 'nisn' => '0141234504', 'jk' => 'P', 'tempat_lahir' => 'Tiom', 'tanggal_lahir' => '2014-01-30', 'agama' => 'Kristen', 'distrik' => 'Tiom', 'alamat' => 'Kampung Tiom', 'sekolah_asal' => 'SD Negeri Tiom', 'tahun_lulus' => '2027', 'nilai' => 91.4, 'jalur' => 'prestasi', 'nama_ayah' => 'Daniel Murib', 'nama_ibu' => 'Selina Karoba', 'pekerjaan_ortu' => 'PNS', 'hp' => '081200000004', 'email' => 'debora@contoh.id', 'tanggal_daftar' => '2026-10-04', 'status' => 'diterima', 'catatan' => null,
            'berkas' => ['kk' => 'valid', 'akta' => 'valid', 'ijazah' => 'valid', 'foto' => 'valid', 'rapor' => 'valid']],
        ['no' => 'PPDB-2027-0005', 'nama' => 'Marten Yikwa', 'nisn' => '0141234505', 'jk' => 'L', 'tempat_lahir' => 'Pirime', 'tanggal_lahir' => '2014-05-17', 'agama' => 'Kristen', 'distrik' => 'Pirime', 'alamat' => 'Kampung Indawa', 'sekolah_asal' => 'SD Inpres Pirime', 'tahun_lulus' => '2027', 'nilai' => 80.7, 'jalur' => 'domisili', 'nama_ayah' => 'Lukas Yikwa', 'nama_ibu' => 'Yuliana Enumbi', 'pekerjaan_ortu' => 'Wiraswasta', 'hp' => '081200000005', 'email' => 'marten@contoh.id', 'tanggal_daftar' => '2026-10-05', 'status' => 'terverifikasi', 'catatan' => null,
            'berkas' => ['kk' => 'valid', 'akta' => 'valid', 'ijazah' => 'valid', 'foto' => 'valid']],
        ['no' => 'PPDB-2027-0006', 'nama' => 'Ruth Karoba', 'nisn' => '0141234506', 'jk' => 'P', 'tempat_lahir' => 'Makki', 'tanggal_lahir' => '2014-09-09', 'agama' => 'Kristen', 'distrik' => 'Makki', 'alamat' => 'Kampung Makki', 'sekolah_asal' => 'SD YPPGI Makki', 'tahun_lulus' => '2027', 'nilai' => 76.3, 'jalur' => 'afirmasi', 'nama_ayah' => 'Simon Karoba', 'nama_ibu' => 'Agustina Wakerkwa', 'pekerjaan_ortu' => 'Petani', 'hp' => '081200000006', 'email' => 'ruth@contoh.id', 'tanggal_daftar' => '2026-10-06', 'status' => 'menunggu', 'catatan' => null,
            'berkas' => ['kk' => 'menunggu', 'akta' => 'menunggu', 'ijazah' => 'menunggu', 'foto' => 'menunggu', 'kip' => 'menunggu']],
        ['no' => 'PPDB-2027-0007', 'nama' => 'Yulianus Enumbi', 'nisn' => '0141234507', 'jk' => 'L', 'tempat_lahir' => 'Wamena', 'tanggal_lahir' => '2013-12-01', 'agama' => 'Kristen', 'distrik' => 'Pirime', 'alamat' => 'Kampung Pirime', 'sekolah_asal' => 'SD Negeri Wamena 3', 'tahun_lulus' => '2027', 'nilai' => 82.1, 'jalur' => 'mutasi', 'nama_ayah' => 'Petrus Enumbi', 'nama_ibu' => 'Lince Tabuni', 'pekerjaan_ortu' => 'TNI/Polri', 'hp' => '081200000007', 'email' => 'yulianus@contoh.id', 'tanggal_daftar' => '2026-10-07', 'status' => 'ditolak', 'catatan' => 'Surat mutasi orang tua tidak dilampirkan.',
            'berkas' => ['kk' => 'valid', 'akta' => 'valid', 'ijazah' => 'valid', 'foto' => 'valid']],
        ['no' => 'PPDB-2027-0008', 'nama' => 'Selina Wakerkwa', 'nisn' => '0141234508', 'jk' => 'P', 'tempat_lahir' => 'Pirime', 'tanggal_lahir' => '2014-02-14', 'agama' => 'Kristen', 'distrik' => 'Pirime', 'alamat' => 'Kampung Yugumena', 'sekolah_asal' => 'SD YPPGI Pirime', 'tahun_lulus' => '2027', 'nilai' => 86.9, 'jalur' => 'domisili', 'nama_ayah' => 'Hans Wakerkwa', 'nama_ibu' => 'Dorkas Kogoya', 'pekerjaan_ortu' => 'Petani', 'hp' => '081200000008', 'email' => 'selina@contoh.id', 'tanggal_daftar' => '2026-10-08', 'status' => 'diterima', 'catatan' => null,
            'berkas' => ['kk' => 'valid', 'akta' => 'valid', 'ijazah' => 'valid', 'foto' => 'valid']],
        ['no' => 'PPDB-2027-0009', 'nama' => 'Natan Yigibalom', 'nisn' => '0141234509', 'jk' => 'L', 'tempat_lahir' => 'Kuyawage', 'tanggal_lahir' => '2014-06-25', 'agama' => 'Kristen', 'distrik' => 'Kuyawage', 'alamat' => 'Kampung Kuyawage', 'sekolah_asal' => 'SD Inpres Kuyawage', 'tahun_lulus' => '2027', 'nilai' => 73.8, 'jalur' => 'afirmasi', 'nama_ayah' => 'Amos Yigibalom', 'nama_ibu' => 'Marta Wenda', 'pekerjaan_ortu' => 'Petani', 'hp' => '081200000009', 'email' => 'natan@contoh.id', 'tanggal_daftar' => '2026-10-10', 'status' => 'terverifikasi', 'catatan' => null,
            'berkas' => ['kk' => 'valid', 'akta' => 'valid', 'ijazah' => 'valid', 'foto' => 'valid', 'kip' => 'valid']],
        ['no' => 'PPDB-2027-0010', 'nama' => 'Agustina Kogoya', 'nisn' => '0141234510', 'jk' => 'P', 'tempat_lahir' => 'Tiom', 'tanggal_lahir' => '2014-08-03', 'agama' => 'Kristen', 'distrik' => 'Gamelia', 'alamat' => 'Kampung Gamelia', 'sekolah_asal' => 'SD Inpres Gamelia', 'tahun_lulus' => '2027', 'nilai' => 79.4, 'jalur' => 'domisili', 'nama_ayah' => 'Yohanes Kogoya', 'nama_ibu' => 'Elsina Murib', 'pekerjaan_ortu' => 'Petani', 'hp' => '081200000010', 'email' => 'agustina@contoh.id', 'tanggal_daftar' => '2026-10-12', 'status' => 'menunggu', 'catatan' => null,
            'berkas' => ['kk' => 'menunggu', 'akta' => 'menunggu', 'ijazah' => 'menunggu', 'foto' => 'menunggu']],
    ],

];

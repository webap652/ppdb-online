<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /** Video contoh (Big Buck Bunny – Blender Foundation). Ganti dengan video sekolah. */
    private const SAMPLE_VIDEO = '<p><iframe src="https://www.youtube.com/embed/aqz-KE-bpKQ" width="560" height="315" allowfullscreen="allowfullscreen"></iframe></p>';

    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $operator = User::where('role', 'operator')->first() ?? $admin;
        $cat = Category::pluck('id', 'slug');

        $posts = [
            [
                'title' => 'Upacara Peringatan HUT Kemerdekaan RI di SMP Negeri 1 Pirime',
                'categories' => ['berita', 'foto'],
                'thumbnail' => 'images/hero-bg/hero bg (2).png',
                'days' => 2,
                'content' => '<p>Seluruh warga sekolah mengikuti upacara bendera memperingati Hari Ulang Tahun Kemerdekaan Republik Indonesia. Upacara berlangsung khidmat dengan petugas dari siswa kelas IX.</p><p>Usai upacara, kegiatan dilanjutkan dengan lomba-lomba tradisional antarkelas yang disambut antusias oleh para siswa.</p>',
            ],
            [
                'title' => 'Jadwal Penilaian Akhir Semester Ganjil',
                'categories' => ['pengumuman'],
                'days' => 3,
                'content' => '<p>Diberitahukan kepada seluruh siswa bahwa Penilaian Akhir Semester (PAS) Ganjil akan dilaksanakan dengan ketentuan sebagai berikut:</p><ul><li>Hari/tanggal: Senin – Sabtu, sesuai jadwal kelas</li><li>Waktu: 07.30 – 11.00 WIT</li><li>Tempat: ruang kelas masing-masing</li></ul><p>Siswa diharapkan hadir tepat waktu dan mengenakan seragam lengkap.</p>',
            ],
            [
                'title' => 'Program Literasi Pagi Tingkatkan Minat Baca Siswa',
                'categories' => ['berita'],
                'thumbnail' => 'images/tetang.png',
                'days' => 5,
                'content' => '<p>Program Pirime Literasi Pagi yang dilaksanakan setiap hari sebelum pelajaran dimulai menunjukkan hasil positif. Siswa kini lebih terbiasa membaca dan berani menceritakan kembali isi bacaan di depan kelas.</p>',
            ],
            [
                'title' => 'Video Profil SMP Negeri 1 Pirime',
                'categories' => ['video'],
                'days' => 6,
                'content' => self::SAMPLE_VIDEO . '<p>Video profil singkat sekolah. <em>Video contoh — ganti dengan video sekolah melalui tombol media di editor.</em></p>',
            ],
            [
                'title' => 'Pembagian Rapor dan Libur Semester',
                'categories' => ['pengumuman'],
                'days' => 8,
                'content' => '<p>Pembagian rapor semester ganjil dilaksanakan pada hari Sabtu dan wajib diambil oleh orang tua/wali siswa. Libur semester berlangsung selama dua minggu setelah pembagian rapor.</p>',
            ],
            [
                'title' => 'Kegiatan Laboratorium IPA Kelas VIII',
                'categories' => ['berita', 'foto'],
                'thumbnail' => 'images/ruangan lab.png',
                'days' => 10,
                'content' => '<p>Siswa kelas VIII melakukan praktikum pengamatan sel tumbuhan menggunakan mikroskop di laboratorium sekolah.</p><p><img src="/images/ruangan%20lab.png" alt="Laboratorium IPA"></p>',
            ],
            [
                'title' => 'Penampilan Sanggar Noken & Tifa',
                'categories' => ['video', 'berita'],
                'days' => 12,
                'content' => '<p>Siswa anggota Sanggar Noken &amp; Tifa menampilkan tarian daerah dalam acara pentas seni sekolah.</p>' . self::SAMPLE_VIDEO,
            ],
            [
                'title' => 'Dokumentasi Sambutan Kepala Sekolah pada Awal Tahun Ajaran',
                'categories' => ['foto'],
                'thumbnail' => 'images/hero-bg/bg hero sambutan kepala sekolah.jpg',
                'days' => 15,
                'content' => '<p>Kepala sekolah memberikan sambutan dan motivasi kepada seluruh siswa pada hari pertama tahun ajaran baru.</p>',
            ],
            [
                'title' => 'Panen Perdana Kebun Sekolah',
                'categories' => ['berita'],
                'days' => 18,
                'content' => '<p>Siswa bersama guru memanen sayuran dari kebun sekolah. Hasil panen sebagian dijual untuk mendukung kegiatan kewirausahaan siswa.</p>',
            ],
            [
                'title' => 'Pengumuman Kegiatan Pramuka Setiap Jumat',
                'categories' => ['pengumuman'],
                'days' => 20,
                'content' => '<p>Kegiatan ekstrakurikuler Pramuka wajib bagi siswa kelas VII dan VIII, dilaksanakan setiap hari Jumat pukul 13.00 WIT di lapangan sekolah.</p>',
            ],
            [
                'title' => 'Rencana Kegiatan Studi Wisata (Draf)',
                'categories' => ['pengumuman'],
                'status' => 'draft',
                'days' => 0,
                'content' => '<p>Draf pengumuman — belum diterbitkan.</p>',
            ],
        ];

        foreach ($posts as $i => $data) {
            $post = Post::updateOrCreate(
                ['slug' => Str::slug($data['title'])],
                [
                    'user_id' => $i % 2 ? $operator->id : $admin->id,
                    'title' => $data['title'],
                    'content' => $data['content'],
                    'thumbnail' => $data['thumbnail'] ?? null,
                    'status' => $data['status'] ?? 'published',
                    'published_at' => now()->subDays($data['days'])->setTime(8, 0),
                    'views' => ($data['status'] ?? 'published') === 'published' ? random_int(10, 300) : 0,
                ]
            );

            $post->categories()->sync(collect($data['categories'])->map(fn ($slug) => $cat[$slug])->all());
        }
    }
}

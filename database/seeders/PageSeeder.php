<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Sambutan Kepala Sekolah',
                'slug' => 'sambutan-kepala-sekolah',
                'image' => 'images/Kepala sekolah.png',
                'content' => <<<'HTML'
<p>Selamat datang di website resmi SMP Negeri 1 Pirime. Kami berkomitmen untuk terus meningkatkan kualitas pendidikan dan mencetak generasi penerus bangsa yang unggul, berkarakter, serta menjunjung tinggi nilai-nilai budaya lokal.</p>
<p>Website ini kami hadirkan sebagai sarana informasi dan komunikasi antara sekolah, orang tua, dan masyarakat. Melalui website ini, kami akan membagikan berita kegiatan, pengumuman, serta dokumentasi foto dan video sekolah.</p>
<p>Saya berharap SMP Negeri 1 Pirime dapat menjadi sekolah unggulan di Kabupaten Lanny Jaya yang mampu melahirkan generasi muda yang beriman, berilmu, berkarakter, dan peduli lingkungan. Dengan kerja sama seluruh pihak, kita wujudkan Generasi Emas Lanny Jaya.</p>
<p><strong>Yulianus Giban, S.Pd</strong><br>Kepala SMP Negeri 1 Pirime</p>
HTML,
            ],
            [
                'title' => 'Visi & Misi',
                'slug' => 'visi-misi',
                'content' => <<<'HTML'
<h2>Visi</h2>
<p>Mewujudkan peserta didik yang Beriman, Berilmu, Berkarakter, dan Peduli Lingkungan menuju Generasi Emas Lanny Jaya.</p>
<h2>Misi</h2>
<ol>
<li>Menyelenggarakan pembelajaran yang aktif, kreatif, inovatif, dan berbasis kearifan lokal Papua Pegunungan.</li>
<li>Menanamkan nilai-nilai keimanan, budi pekerti, dan karakter unggul melalui pembiasaan sehari-hari.</li>
<li>Meningkatkan kompetensi literasi, numerasi, serta teknologi informasi dan komunikasi bagi guru dan peserta didik.</li>
<li>Mengembangkan potensi peserta didik dalam bidang akademik, olahraga, seni, budaya, dan kewirausahaan.</li>
<li>Mewujudkan lingkungan sekolah yang bersih, sehat, aman, nyaman, dan berwawasan lingkungan.</li>
<li>Menumbuhkan rasa cinta terhadap budaya, adat istiadat, dan kearifan lokal Kabupaten Lanny Jaya.</li>
<li>Meningkatkan kerja sama antara sekolah, orang tua, gereja, pemerintah, dan masyarakat dalam mendukung pendidikan.</li>
</ol>
<h2>Motto</h2>
<p>Cerdas, Mandiri, Berkarakter.</p>
HTML,
            ],
            [
                'title' => 'Sejarah',
                'slug' => 'sejarah',
                'content' => <<<'HTML'
<p>SMP Negeri 1 Pirime berdiri pada tahun 1998 sebagai jawaban atas kebutuhan pendidikan menengah pertama bagi anak-anak di Distrik Pirime dan sekitarnya, Kabupaten Lanny Jaya.</p>
<p>Berawal dari jumlah ruang kelas dan tenaga pendidik yang terbatas, sekolah terus berkembang berkat dukungan pemerintah daerah, gereja, orang tua, dan masyarakat. Kini sekolah memiliki ruang kelas, perpustakaan, laboratorium, dan fasilitas penunjang lainnya.</p>
<p><em>Contoh isi — silakan perbarui melalui menu Halaman di dasbor.</em></p>
HTML,
            ],
            [
                'title' => 'Struktur Organisasi',
                'slug' => 'struktur-organisasi',
                'content' => <<<'HTML'
<table>
<thead><tr><th>Jabatan</th><th>Nama</th></tr></thead>
<tbody>
<tr><td>Kepala Sekolah</td><td>Yulianus Giban, S.Pd</td></tr>
<tr><td>Wakil Kepala Sekolah Bidang Kurikulum</td><td>—</td></tr>
<tr><td>Wakil Kepala Sekolah Bidang Kesiswaan</td><td>—</td></tr>
<tr><td>Kepala Tata Usaha</td><td>—</td></tr>
<tr><td>Bendahara</td><td>—</td></tr>
</tbody>
</table>
<p><em>Contoh isi — silakan lengkapi nama pejabat sekolah.</em></p>
HTML,
            ],
            [
                'title' => 'Guru & Staf',
                'slug' => 'guru-staf',
                'content' => <<<'HTML'
<table>
<thead><tr><th>No</th><th>Nama</th><th>Mata Pelajaran / Tugas</th></tr></thead>
<tbody>
<tr><td>1</td><td>Yulianus Giban, S.Pd</td><td>Kepala Sekolah</td></tr>
<tr><td>2</td><td>—</td><td>Bahasa Indonesia</td></tr>
<tr><td>3</td><td>—</td><td>Matematika</td></tr>
<tr><td>4</td><td>—</td><td>Ilmu Pengetahuan Alam</td></tr>
<tr><td>5</td><td>—</td><td>Pendidikan Agama Kristen</td></tr>
</tbody>
</table>
<p><em>Contoh isi — silakan lengkapi data guru dan staf.</em></p>
HTML,
            ],
            [
                'title' => 'Fasilitas',
                'slug' => 'fasilitas',
                'content' => <<<'HTML'
<p><img src="/images/ruangan%20lab.png" alt="Ruang laboratorium"></p>
<ul>
<li>Ruang kelas</li>
<li>Perpustakaan</li>
<li>Laboratorium IPA dan komputer</li>
<li>Lapangan olahraga</li>
<li>Ruang guru dan kantor tata usaha</li>
<li>Ruang UKS</li>
<li>Kebun sekolah</li>
</ul>
HTML,
            ],
            [
                'title' => 'Program Unggulan',
                'slug' => 'program-unggulan',
                'content' => <<<'HTML'
<h3>Pirime Literasi Pagi</h3>
<p>15 menit membaca Alkitab dan buku non-pelajaran setiap pagi sebelum kegiatan belajar mengajar.</p>
<h3>Taman Belajar Kebun Sekolah</h3>
<p>Siswa menanam dan merawat sayuran serta tanaman lokal sebagai media pembelajaran IPA sekaligus kewirausahaan sederhana.</p>
<h3>SMPN 1 Pirime Berbahasa</h3>
<p>Pembiasaan Bahasa Indonesia yang baik dan benar, serta pengenalan Bahasa Lani satu hari dalam setiap minggu.</p>
<h3>Sanggar Noken &amp; Tifa</h3>
<p>Kegiatan seni dan budaya: menganyam noken, tarian daerah, dan memainkan tifa.</p>
<h3>Pramuka &amp; Bela Negara Pegunungan</h3>
<p>Melatih kepemimpinan, disiplin, kemandirian, dan rasa cinta tanah air.</p>
<h3>Digital Corner 3T</h3>
<p>Pengenalan TIK dasar, mengetik, Canva, dan keterampilan digital lainnya.</p>
HTML,
            ],
        ];

        foreach ($pages as $i => $page) {
            Page::updateOrCreate(['slug' => $page['slug']], $page + [
                'is_published' => true,
                'show_in_menu' => true,
                'sort_order' => $i + 1,
            ]);
        }
    }
}

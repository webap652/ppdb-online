# RENCANA IMPLEMENTASI MENU PROFIL SMP NEGERI 1 PIRIME

## Status Saat Ini
- Menu Profil memiliki 8 submenu tetapi semuanya masih placeholder/view sementara
- Hanya ada 1 file view `profil/index.blade.php` untuk semua submenu
- Admin dashboard hanya memiliki menu PPDB, belum ada menu untuk Profil
- Belum ada Model untuk Guru, Fasilitas, Prestasi, Sejarah, Struktur Organisasi
- Data sekolah sudah ada di `settings` table via SettingsSeeder

---

## TAHAP 1: LAYER DATABASE (MIGRATIONS + MODELS + SEEDERS)

### 1.1 Migration: Guru & Tenaga Kependidikan
- Table: `gurus`
- Fields: foto, nama_lengkap, nip, mapel, pendidikan, status (ASN/Honorer), no_hp, email, jabatan, urutan, is_active

### 1.2 Migration: Fasilitas
- Table: `fasilitas`
- Fields: foto, nama, deskripsi, jumlah, status (baik/rusak/kurang), kategori (ruang_kelas, perpustakaan, lab, lapangan, toilet, uks, ruang_guru, kantor, internet, air, listrik, ibadah), is_active

### 1.3 Migration: Prestasi
- Table: `prestasis`
- Fields: foto, nama_prestasi, tahun, tingkat (kabupaten/provinsi/nasional), juara (1/2/3/harapan), deskripsi, kategori (akademik/non_akademik/guru/sekolah), is_active

### 1.4 Migration: Sejarah
- Table: `sejarahs`
- Fields: judul, deskripsi, tahun, galeri (json), is_active, urutan

### 1.5 Migration: Struktur Organisasi
- Table: `struktur_organisasis`
- Fields: nama, jabatan, foto, parent_id (self-referencing), urutan, is_active

### 1.6 Migration: Galeri (untuk upload gambar di admin)
- Table: `galeris`
- Fields: judul, deskripsi, gambar, kategori, is_active

### 1.7 Models
- App\Models\Guru
- App\Models\Fasilitas
- App\Models\Prestasi
- App\Models\Sejarah
- App\Models\StrukturOrganisasi
- App\Models\Galeri

### 1.8 Update SettingsSeeder
- Tambahkan settings untuk: NIP Kepsek, Tujuan Sekolah (10 tujuan), Nilai Sekolah, Statistik Sekolah

---

## TAHAP 2: ADMIN DASHBOARD - SYSTEM SETTINGS UPDATE

### 2.1 Update SystemSettingsController
- Add fields: school.identity.npsn, school.identity.akreditasi, school.identity.tahun_berdiri, school.identity.jenis
- Add fields: school.leadership.nip, school.leadership.goals, school.leadership.harapan
- Add fields: school.statistics (jumlah_siswa, jumlah_guru, jumlah_kelas, jumlah_rombel)
- Add fields: school.values (json array nilai-nilai sekolah)

### 2.2 Update System Settings View (admin/system-settings/edit.blade.php)
- Add form fields for all new settings
- Group sections lebih rapi

---

## TAHAP 3: ADMIN CRUD CONTROLLERS & VIEWS

### 3.1 Admin/GuruController
- index, create, store, edit, update, destroy
- View: admin/guru/index.blade.php, admin/guru/create.blade.php, admin/guru/edit.blade.php

### 3.2 Admin/FasilitasController
- index, create, store, edit, update, destroy
- View: admin/fasilitas/index.blade.php, admin/fasilitas/create.blade.php, admin/fasilitas/edit.blade.php

### 3.3 Admin/PrestasiController
- index, create, store, edit, update, destroy
- View: admin/prestasi/index.blade.php, admin/prestasi/create.blade.php, admin/prestasi/edit.blade.php

### 3.4 Admin/SejarahController
- index, create, store, edit, update, destroy
- View: admin/sejarah/index.blade.php, admin/sejarah/create.blade.php, admin/sejarah/edit.blade.php

### 3.5 Admin/StrukturOrganisasiController
- index, create, store, edit, update, destroy
- View: admin/struktur-organisasi/index.blade.php, admin/struktur-organisasi/create.blade.php, admin/struktur-organisasi/edit.blade.php

### 3.6 Admin/GaleriController
- index, create, store, edit, update, destroy
- View: admin/galeri/index.blade.php, admin/galeri/create.blade.php, admin/galeri/edit.blade.php

### 3.7 Update Admin Layout (layouts/admin.blade.php)
- Add all new menu items: Pengaturan Website, Profil Sekolah, Visi & Misi, Tujuan Sekolah, Moto Sekolah, Sambutan, Sejarah, Struktur Organisasi, Guru, Fasilitas, Prestasi, Galeri, Berita, PPDB, Agenda, Kontak, Media Sosial, Banner Hero, SEO, Footer

---

## TAHAP 4: PUBLIC VIEWS - HALAMAN PROFIL

### 4.1 Component: Sidebar Profil (profil/partials/sidebar.blade.php)
- Navigasi ke semua 8 submenu
- Active state berdasarkan route
- Styling modern dengan Tailwind

### 4.2 Component: CTA PPDB (profil/partials/cta-ppdb.blade.php)
- Card CTA untuk mendaftar PPDB
- Tampil di semua halaman profil

### 4.3 Component: CTA Hubungi Kami (profil/partials/cta-kontak.blade.php)
- Card CTA untuk kontak sekolah

### 4.4 Component: Related Content (profil/partials/related-content.blade.php)
- Menampilkan link ke halaman profil lain

### 4.5 Halaman: Profil Sekolah (profil/index.blade.php) - REDESIGN
- Hero Banner
- Breadcrumb
- Tentang Sekolah
- Profil Singkat
- Identitas Sekolah (NPSN, Akreditasi, Tahun, Alamat)
- Visi Singkat
- Misi Singkat
- Tujuan Sekolah
- Moto Sekolah
- Nilai Sekolah (cards)
- Program Unggulan (cards)
- Statistik Sekolah (counter)
- Lokasi Sekolah (map/alamat)
- CTA PPDB
- Sidebar Profil

### 4.6 Halaman: Visi & Misi (profil/visi-misi.blade.php)
- Hero Card dengan Visi
- Misi dalam modern cards
- Tujuan Sekolah dalam Timeline
- Moto dalam premium design
- Sidebar Profil

### 4.7 Halaman: Sambutan Kepala Sekolah (profil/sambutan.blade.php)
- Foto Kepala Sekolah
- Nama, NIP, Jabatan
- Sambutan lengkap
- Harapan
- Tanda tangan digital
- Sidebar Profil

### 4.8 Halaman: Sejarah Sekolah (profil/sejarah.blade.php)
- Timeline sejarah
- Galeri foto lama
- Sidebar Profil

### 4.9 Halaman: Struktur Organisasi (profil/struktur-organisasi.blade.php)
- Diagram organisasi interaktif
- Tree/hierarki view
- Sidebar Profil

### 4.10 Halaman: Guru & Staff (profil/guru-staff.blade.php)
- Filter by mapel
- Guru cards dengan foto, nama, nip, mapel, status
- Statistik (jumlah guru, ASN, honorer)
- Sidebar Profil

### 4.11 Halaman: Fasilitas (profil/fasilitas.blade.php)
- Grid fasilitas per kategori
- Masing-masing: foto, nama, deskripsi, jumlah, status
- Sidebar Profil

### 4.12 Halaman: Prestasi (profil/prestasi.blade.php)
- Filter by kategori/tahun/tingkat
- Cards prestasi
- Sidebar Profil

---

## TAHAP 5: ROUTES UPDATE

### 5.1 Public Routes (web.php)
- Update semua route profil placeholder ke controller methods baru
- Route names yang konsisten

### 5.2 Admin Routes (web.php)
- Tambahkan semua route resource untuk admin CRUD

### 5.3 Public Controller Methods (PPDBController.php)
- Tambah methods: visiMisi(), sambutan(), sejarah(), strukturOrganisasi(), guruStaff(), fasilitas(), prestasi()

---

## TAHAP 6: SEO & OPTIMASI

### 6.1 SEO
- Meta Title per halaman
- Meta Description per halaman
- Canonical URL
- Open Graph tags
- Twitter Card
- JSON-LD Schema.org (School, Organization)
- BreadcrumbList Schema

### 6.2 Performance
- Lazy loading images
- Animasi smooth
- Responsive untuk semua device

---

## URUTAN EKSEKUSI

1. ✅ Buat Migrations (6 tabel baru)
2. ✅ Buat Models (6 model baru)
3. ✅ Update SettingsSeeder + SystemSettingsController
4. ✅ Buat Admin CRUD Controllers
5. ✅ Buat Admin Views
6. ✅ Update Admin Layout sidebar
7. ✅ Update Public Routes
8. ✅ Update PPDBController methods
9. ✅ Buat Partial Components (sidebar, CTA, related)
10. ✅ Buat 8 Halaman Publik Profil
11. ✅ Update Navbar active states
12. ✅ Update Footer dengan data dari settings
13. ✅ Run migrations dan seed
14. ✅ Testing dan polish

---

Setuju dengan rencana ini? Jika ada yang ingin ditambahkan/diubah, silakan sampaikan.


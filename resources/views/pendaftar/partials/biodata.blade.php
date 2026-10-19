{{-- Daftar biodata pendaftar; dipakai di akun pendaftar & detail panel --}}
@php
    use Illuminate\Support\Carbon;

    $grup = [
        'Data Siswa' => [
            'Nama Lengkap' => $applicant['nama'],
            'NISN' => $applicant['nisn'],
            'Jenis Kelamin' => $applicant['jk'] === 'L' ? 'Laki-laki' : 'Perempuan',
            'Tempat, Tgl Lahir' => $applicant['tempat_lahir'] . ', ' . Carbon::parse($applicant['tanggal_lahir'])->translatedFormat('d F Y'),
            'Agama' => $applicant['agama'],
            'Distrik' => $applicant['distrik'],
            'Alamat' => $applicant['alamat'],
        ],
        'Orang Tua / Wali' => [
            'Nama Ayah' => $applicant['nama_ayah'],
            'Nama Ibu' => $applicant['nama_ibu'],
            'Pekerjaan' => $applicant['pekerjaan_ortu'],
            'No. HP' => $applicant['hp'],
        ],
        'Sekolah & Jalur' => [
            'Sekolah Asal' => $applicant['sekolah_asal'],
            'Tahun Lulus' => $applicant['tahun_lulus'],
            'Rata-rata Rapor' => number_format($applicant['nilai'], 2, ',', '.'),
            'Jalur' => $applicant['jalur_label'],
            'Email Akun' => $applicant['email'],
        ],
    ];
@endphp
@foreach ($grup as $judul => $isi)
    <section class="card">
        <h2 class="px-5 pt-4 pb-2 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ $judul }}</h2>
        <dl class="divide-y divide-slate-100">
            @foreach ($isi as $label => $nilai)
                <div class="px-5 py-3 grid grid-cols-5 gap-3 text-sm">
                    <dt class="col-span-2 text-slate-500">{{ $label }}</dt>
                    <dd class="col-span-3 font-medium break-words">{{ $nilai }}</dd>
                </div>
            @endforeach
        </dl>
    </section>
@endforeach

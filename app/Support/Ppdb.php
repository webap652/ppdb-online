<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

/**
 * Penyimpanan data PPDB untuk prototype (tanpa database).
 *
 * Data awal dibaca dari config/ppdb.php, lalu setiap perubahan ditumpuk di
 * session: pendaftaran baru, perubahan status pendaftar, akun baru, dan
 * pengaturan. Ganti kelas ini dengan Eloquent saat database sudah siap.
 */
class Ppdb
{
    public const STATUS = [
        'menunggu' => ['Menunggu Verifikasi', 'bg-amber-50 text-amber-700 ring-amber-200'],
        'perbaikan' => ['Perlu Perbaikan', 'bg-orange-50 text-orange-700 ring-orange-200'],
        'terverifikasi' => ['Terverifikasi', 'bg-sky-50 text-sky-700 ring-sky-200'],
        'diterima' => ['Diterima', 'bg-emerald-50 text-emerald-700 ring-emerald-200'],
        'ditolak' => ['Tidak Diterima', 'bg-rose-50 text-rose-700 ring-rose-200'],
    ];

    public const BERKAS_STATUS = [
        'menunggu' => ['Diperiksa', 'bg-amber-50 text-amber-700 ring-amber-200'],
        'valid' => ['Valid', 'bg-emerald-50 text-emerald-700 ring-emerald-200'],
        'ditolak' => ['Ditolak', 'bg-rose-50 text-rose-700 ring-rose-200'],
    ];

    public const ROLES = [
        'admin' => 'Administrator',
        'operator' => 'Panitia Seleksi',
        'pendaftar' => 'Pendaftar',
    ];

    /* ------------------------------------------------------------------
     | Sekolah & pengaturan
     * ------------------------------------------------------------------ */

    public static function sekolah(?string $key = null): mixed
    {
        return $key ? config("ppdb.sekolah.$key") : config('ppdb.sekolah');
    }

    public static function setting(?string $key = null): mixed
    {
        $all = array_merge(config('ppdb.pengaturan'), session('ppdb.pengaturan', []));

        return $key ? ($all[$key] ?? null) : $all;
    }

    public static function jadwal(): array
    {
        return session('ppdb.jadwal', config('ppdb.jadwal'));
    }

    public static function saveSettings(array $pengaturan, array $jadwal): void
    {
        session(['ppdb.pengaturan' => $pengaturan, 'ppdb.jadwal' => $jadwal]);
    }

    public static function jalur(?string $key = null): array
    {
        $kuota = (int) self::setting('kuota');
        $jalur = collect(config('ppdb.jalur'))
            ->map(fn ($j) => $j + ['kuota' => (int) floor($kuota * $j['persen'] / 100)])
            ->all();

        return $key ? $jalur[$key] : $jalur;
    }

    public static function berkas(): array
    {
        return config('ppdb.berkas');
    }

    /* ------------------------------------------------------------------
     | Pendaftar
     * ------------------------------------------------------------------ */

    public static function applicants(): Collection
    {
        $updates = session('ppdb.updates', []);

        return collect(config('ppdb.pendaftar'))
            ->merge(session('ppdb.registrations', []))
            ->map(fn ($a) => array_replace_recursive($a, $updates[$a['no']] ?? []))
            ->map(fn ($a) => $a + ['jalur_label' => config("ppdb.jalur.{$a['jalur']}.label")])
            ->sortByDesc('tanggal_daftar')
            ->values();
    }

    public static function find(?string $no): ?array
    {
        return $no ? self::applicants()->firstWhere('no', strtoupper(trim($no))) : null;
    }

    public static function update(string $no, array $changes): void
    {
        $updates = session('ppdb.updates', []);
        $updates[$no] = array_replace_recursive($updates[$no] ?? [], $changes);
        session(['ppdb.updates' => $updates]);
    }

    public static function register(array $data): array
    {
        $year = substr(explode('/', self::setting('tahun_ajaran'))[0], 0, 4);
        $no = sprintf('PPDB-%s-%04d', $year, self::applicants()->count() + 1);

        $applicant = collect($data)->except(['password', 'password_confirmation', 'setuju'])->all() + [
            'no' => $no,
            'tanggal_daftar' => now()->toDateString(),
            'status' => 'menunggu',
            'catatan' => null,
            'berkas' => [],
        ];

        session()->push('ppdb.registrations', $applicant);
        session()->push('ppdb.accounts', [
            'nama' => $data['nama'],
            'email' => $data['email'],
            'password_hash' => Hash::make($data['password']),
            'role' => 'pendaftar',
            'aktif' => true,
            'no' => $no,
        ]);

        return $applicant;
    }

    /** Pendaftar siap diverifikasi bila semua berkas wajib sudah diunggah. */
    public static function berkasLengkap(array $applicant): bool
    {
        return collect(self::berkas())
            ->filter(fn ($b) => $b['wajib'])
            ->keys()
            ->every(fn ($key) => ! empty($applicant['berkas'][$key]) && $applicant['berkas'][$key] !== 'ditolak');
    }

    public static function stats(): array
    {
        $all = self::applicants();

        return [
            'total' => $all->count(),
            'status' => collect(self::STATUS)->map(fn ($s, $key) => $all->where('status', $key)->count()),
            'jalur' => collect(self::jalur())->map(fn ($j, $key) => $all->where('jalur', $key)->count()),
        ];
    }

    /* ------------------------------------------------------------------
     | Akun & autentikasi (session)
     * ------------------------------------------------------------------ */

    public static function accounts(): Collection
    {
        $toggles = session('ppdb.account_status', []);

        return collect(config('ppdb.akun'))
            ->merge(session('ppdb.accounts', []))
            ->map(fn ($u) => array_merge($u, ['aktif' => $toggles[$u['email']] ?? $u['aktif']]));
    }

    public static function addAccount(array $data): void
    {
        session()->push('ppdb.accounts', [
            'nama' => $data['nama'],
            'email' => $data['email'],
            'password_hash' => Hash::make($data['password']),
            'role' => $data['role'],
            'aktif' => true,
        ]);
    }

    public static function toggleAccount(string $email): void
    {
        $account = self::accounts()->firstWhere('email', $email);

        // Email mengandung titik, jadi jangan dipakai sebagai kunci dot-notation session
        $toggles = session('ppdb.account_status', []);
        $toggles[$email] = ! $account['aktif'];
        session(['ppdb.account_status' => $toggles]);
    }

    /** Login dengan email, atau nomor pendaftaran untuk pendaftar. */
    public static function attempt(string $login, string $password): ?array
    {
        $login = trim($login);
        $account = self::accounts()->first(fn ($u) => strcasecmp($u['email'], $login) === 0
            || (isset($u['no']) && strcasecmp($u['no'], $login) === 0));

        if (! $account || ! $account['aktif']) {
            return null;
        }

        $valid = isset($account['password_hash'])
            ? Hash::check($password, $account['password_hash'])
            : hash_equals($account['password'], $password);

        return $valid ? $account : null;
    }

    public static function login(array $account): void
    {
        session()->regenerate();
        session(['ppdb.auth' => collect($account)->only(['nama', 'email', 'role', 'no'])->all()]);
    }

    public static function logout(): void
    {
        // Hanya hapus data login: data prototype lain di session tetap tersimpan.
        session()->forget('ppdb.auth');
        session()->regenerateToken();
    }

    public static function user(): ?array
    {
        return session('ppdb.auth');
    }

    public static function homeRoute(string $role): string
    {
        return $role === 'pendaftar' ? 'pendaftar.dashboard' : 'panel.dashboard';
    }
}

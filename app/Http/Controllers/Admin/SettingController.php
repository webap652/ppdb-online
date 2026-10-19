<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Daftar pengaturan situs: grup => [key => [label, tipe]].
     */
    public const FIELDS = [
        'Identitas' => [
            'site.name' => ['Nama Sekolah', 'text'],
            'site.tagline' => ['Slogan', 'text'],
            'site.description' => ['Deskripsi Singkat (footer & SEO)', 'textarea'],
            'site.logo' => ['Logo', 'image'],
            'site.favicon' => ['Favicon', 'image'],
        ],
        'Beranda' => [
            'home.hero_title' => ['Judul Banner', 'text'],
            'home.hero_image' => ['Gambar Banner', 'image'],
        ],
        'Kontak' => [
            'contact.address' => ['Alamat', 'textarea'],
            'contact.phone' => ['Telepon', 'text'],
            'contact.email' => ['Email', 'email'],
            'contact.hours' => ['Jam Operasional', 'text'],
            'contact.map_embed' => ['Embed Google Maps (URL atau kode <iframe> dari menu Bagikan › Sematkan peta)', 'map'],
        ],
        'Media Sosial' => [
            'social.facebook' => ['Facebook', 'url'],
            'social.instagram' => ['Instagram', 'url'],
            'social.youtube' => ['YouTube', 'url'],
            'social.whatsapp' => ['Nomor WhatsApp (format 62…)', 'text'],
        ],
        'Sistem' => [
            'system.maintenance' => ['Mode perbaikan (situs publik ditutup sementara)', 'checkbox'],
        ],
    ];

    public function edit()
    {
        return view('admin.settings', ['groups' => self::FIELDS]);
    }

    public function update(Request $request)
    {
        // Kode <iframe> Google Maps: cukup ambil URL src-nya
        $map = self::input('contact.map_embed');
        if (preg_match('~<iframe[^>]+src=["\']([^"\']+)["\']~i', (string) $request->input($map), $m)) {
            $request->merge([$map => html_entity_decode($m[1])]);
        }

        $rules = [];
        foreach (self::FIELDS as $fields) {
            foreach ($fields as $key => [$label, $type]) {
                $rules[$this->input($key)] = match ($type) {
                    'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,ico,svg', 'max:4096'],
                    'email' => ['nullable', 'email', 'max:255'],
                    'url' => ['nullable', 'url', 'max:1000'],
                    'map' => ['nullable', 'url:https', 'max:2000'],
                    'checkbox' => ['nullable'],
                    'textarea' => ['nullable', 'string', 'max:2000'],
                    default => ['nullable', 'string', 'max:255'],
                };
            }
        }

        $request->validate($rules);

        foreach (self::FIELDS as $fields) {
            foreach ($fields as $key => [$label, $type]) {
                $name = $this->input($key);

                match ($type) {
                    'checkbox' => Setting::set($key, $request->boolean($name)),
                    'image' => $this->saveImage($request, $key, $name),
                    default => Setting::set($key, $request->input($name)),
                };
            }
        }

        return back()->with('success', 'Pengaturan situs berhasil disimpan.');
    }

    private function saveImage(Request $request, string $key, string $name): void
    {
        if ($request->boolean('remove_' . $name)) {
            $this->deleteUpload(Setting::get($key));
            Setting::set($key, null);
        }

        if ($request->hasFile($name)) {
            Setting::set($key, $this->storeUpload($request->file($name), 'settings', Setting::get($key)));
        }
    }

    /** Nama input form: titik diganti garis bawah agar tidak dibaca sebagai array. */
    public static function input(string $key): string
    {
        return str_replace('.', '_', $key);
    }
}

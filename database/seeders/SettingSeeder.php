<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'site.name' => 'SMP Negeri 1 Pirime',
            'site.tagline' => 'Cerdas, Mandiri, Berkarakter',
            'site.description' => 'Mewujudkan peserta didik yang Beriman, Berilmu, Berkarakter, dan Peduli Lingkungan menuju Generasi Emas Lanny Jaya.',
            'site.logo' => 'images/logo sekolah.png',
            'site.favicon' => null,

            'home.hero_title' => 'Membangun Generasi Emas Lanny Jaya',
            'home.hero_image' => 'images/hero-bg/hero bg (2).png',

            'contact.address' => 'Pirime, Kabupaten Lanny Jaya, Papua Pegunungan',
            'contact.phone' => '+62 821-2044-5529',
            'contact.email' => 'info@smpn1pirime.sch.id',
            'contact.hours' => 'Senin – Jumat, 07.00 – 14.00 WIT',
            // Simulasi lokasi Distrik Pirime, Lanny Jaya (tanpa API key)
            'contact.map_embed' => 'https://www.google.com/maps/embed?origin=mfe&pb=!1m3!2m1!1sPirime,+Lanny+Jaya,+Papua+Pegunungan!6i13',

            'social.facebook' => 'https://facebook.com/smpn1pirime',
            'social.instagram' => 'https://instagram.com/smpn1pirime',
            'social.youtube' => 'https://youtube.com/@smpn1pirime',
            'social.whatsapp' => '6282120445529',

            'system.maintenance' => false,
        ];

        foreach ($defaults as $key => $value) {
            // Jangan timpa pengaturan yang sudah diisi admin
            if (Setting::get($key) === null) {
                Setting::set($key, $value);
            }
        }
    }
}

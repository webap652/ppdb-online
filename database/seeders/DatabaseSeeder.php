<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Idempotent: aman dijalankan berulang kali
        User::updateOrCreate(['email' => 'admin@smpn1pirime.sch.id'], [
            'name' => 'Administrator',
            'password' => 'password',
            'role' => 'admin',
            'is_active' => true,
        ]);

        User::updateOrCreate(['email' => 'operator@smpn1pirime.sch.id'], [
            'name' => 'Operator Sekolah',
            'password' => 'password',
            'role' => 'operator',
            'is_active' => true,
        ]);

        $this->call([
            SettingSeeder::class,
            CategorySeeder::class,
            PageSeeder::class,
            PostSeeder::class,
        ]);
    }
}

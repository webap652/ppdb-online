<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Berita', 'Pengumuman', 'Foto', 'Video'] as $i => $name) {
            Category::updateOrCreate(
                ['slug' => strtolower($name)],
                ['name' => $name, 'sort_order' => $i + 1, 'show_in_menu' => true]
            );
        }
    }
}

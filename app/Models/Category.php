<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasSlug;

    protected $fillable = ['name', 'slug', 'sort_order', 'show_in_menu'];

    protected $casts = [
        'show_in_menu' => 'boolean',
    ];

    protected function slugSource(): string
    {
        return 'name';
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function scopeInMenu($query)
    {
        return $query->where('show_in_menu', true);
    }
}

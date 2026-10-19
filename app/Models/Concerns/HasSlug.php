<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/**
 * Membuat slug unik otomatis dari kolom judul (title / name).
 */
trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::saving(function ($model) {
            if (empty($model->slug)) {
                $model->slug = static::uniqueSlug($model->{$model->slugSource()}, $model->getKey());
            }
        });
    }

    protected function slugSource(): string
    {
        return 'title';
    }

    public static function uniqueSlug(string $text, $ignoreId = null): string
    {
        $base = Str::slug($text) ?: Str::lower(Str::random(8));
        $slug = $base;
        $i = 2;

        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}

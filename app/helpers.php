<?php

use Illuminate\Support\Str;

if (! function_exists('media_url')) {
    /**
     * URL file media: mendukung URL penuh, aset di public/ (images/...)
     * dan file unggahan di storage/app/public.
     */
    function media_url(?string $path, ?string $default = null): ?string
    {
        if (empty($path)) {
            return $default ? asset($default) : null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }

        if (Str::startsWith($path, ['images/', 'storage/'])) {
            return asset($path);
        }

        return asset('storage/' . ltrim($path, '/'));
    }
}

if (! function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        return \App\Models\Setting::get($key, $default);
    }
}

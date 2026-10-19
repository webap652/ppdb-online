<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    protected $casts = [
        'value' => 'array',
    ];

    /** Seluruh pengaturan dimuat sekali per request (disimpan di container). */
    protected static function loadAll(): array
    {
        if (! app()->bound('settings.loaded')) {
            try {
                $loaded = static::query()->pluck('value', 'key')
                    ->map(fn ($v) => is_array($v) && array_key_exists('value', $v) ? $v['value'] : $v)
                    ->all();
            } catch (\Throwable) {
                $loaded = [];
            }

            app()->instance('settings.loaded', $loaded);
        }

        return app('settings.loaded');
    }

    public static function get(string $key, $default = null): mixed
    {
        $value = static::loadAll()[$key] ?? null;

        return ($value === null || $value === '') ? $default : $value;
    }

    public static function set(string $key, mixed $value): void
    {
        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => ['value' => $value]]
        );

        app()->forgetInstance('settings.loaded');
        app()->forgetInstance('site.shared');
    }

    public static function url(string $key, ?string $default = null): ?string
    {
        return media_url(static::get($key), $default);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'description'];

    protected static string $cacheKey = 'app_settings';

    /**
     * キーで値を取得（キャッシュ付き）
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = Cache::rememberForever(static::$cacheKey, function () {
            return static::pluck('value', 'key')->toArray();
        });

        return $settings[$key] ?? $default;
    }

    /**
     * キーで値をセット（キャッシュクリア付き）
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        Cache::forget(static::$cacheKey);
    }

    /**
     * キャッシュクリア
     */
    public static function clearCache(): void
    {
        Cache::forget(static::$cacheKey);
    }
}

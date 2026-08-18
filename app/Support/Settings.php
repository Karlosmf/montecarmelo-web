<?php

namespace App\Support;

use App\Models\Setting;

class Settings
{
    public static function get(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }

    public static function set(string $key, $value, string $group = 'general'): void
    {
        Setting::set($key, $value, $group);
    }
}

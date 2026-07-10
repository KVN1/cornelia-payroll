<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeductionSetting extends Model
{
    protected $fillable = ['key', 'label', 'value', 'type', 'description', 'is_active'];

    protected $casts = [
        'value'     => 'decimal:4',
        'is_active' => 'boolean',
    ];

    // Get a setting value by key
    public static function getValue(string $key, float $default = 0): float
    {
        $setting = static::where('key', $key)->where('is_active', true)->first();
        return $setting ? (float) $setting->value : $default;
    }

    // Get all active settings as key => value array
    public static function allActive(): array
    {
        return static::where('is_active', true)
            ->pluck('value', 'key')
            ->map(fn($v) => (float) $v)
            ->toArray();
    }
}

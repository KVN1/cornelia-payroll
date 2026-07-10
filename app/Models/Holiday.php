<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model {
    public $timestamps = false;
    protected $fillable = ['name','holiday_date','type'];
    protected $casts    = ['holiday_date' => 'date'];

    public static function isHoliday(\Carbon\Carbon $date): ?self {
        return static::where('holiday_date', $date->toDateString())->first();
    }
}

<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PayrollPeriod extends Model {
    protected $fillable = ['period_start','period_end','pay_date','status'];
    protected $casts    = [
        'period_start' => 'date',
        'period_end'   => 'date',
        'pay_date'     => 'date',
    ];

    public function records() { return $this->hasMany(PayrollRecord::class); }
}

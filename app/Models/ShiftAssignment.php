<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ShiftAssignment extends Model {
    public $timestamps = false;
    protected $fillable = ['employee_id','shift_id','work_date'];
    protected $casts    = ['work_date' => 'date'];

    public function employee() { return $this->belongsTo(Employee::class); }
    public function shift()    { return $this->belongsTo(Shift::class); }
}

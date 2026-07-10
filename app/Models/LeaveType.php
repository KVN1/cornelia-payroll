<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model {
    public $timestamps = false;
    protected $fillable = ['name','is_paid'];
    public function leaveRequests() { return $this->hasMany(LeaveRequest::class); }
}

<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'employee_id',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['password' => 'hashed'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function passwordChangeRequests()
    {
        return $this->hasMany(PasswordChangeRequest::class);
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'hr', 'manager']);
    }
}

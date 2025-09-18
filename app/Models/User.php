<?php

namespace App\Models;

use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'location',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];
    public function requests()
    {

        return $this->hasMany(Request::class,'requester_id');

    }

    public function notifications()
    {

        return $this->hasMany(Notification::class);

    }

    public function reports()

        {
            return $this->hasMany(Report::class);
        }

    public function auditLogs()
        {
            return $this->hasMany(AuditLog::class, 'actor_id');
        }

    public function targetsAuditLogs()
        {
            return $this->morphMany(AuditLog::class, 'target');
        }

    public function donationsAsRecipient()
        {
            return $this->hasMany(Donation::class, 'recipient_id');
        }
    public function donationsAsDonor()
    {
        return $this->hasMany(Donation::class, 'donor_id');
    }
}

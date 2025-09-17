<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'actor_id',
        'action_type',
        'target_type',
        'target_id',
        'detail',
        'created_at'
    ];

    protected $casts = [
        'detail' => 'array',
    ];

    public $timestamps = false;

    // Relations
    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function target()
    {
        return $this->belongsTo(User::class, 'target_id');
    }
}

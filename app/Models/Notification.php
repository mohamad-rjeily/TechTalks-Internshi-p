<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'payload',
        'read_at',
    ];

    // Automatically cast 'payload' to array when retrieving and JSON when saving
    protected $casts = [
        'payload' => 'array',
        'read_at' => 'datetime', // optional, if you want 'read_at' to be a Carbon instance
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


}

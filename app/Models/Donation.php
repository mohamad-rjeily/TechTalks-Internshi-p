<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'medicine_id',
        'donor_id',
        'recipient_id',
        'quantity',
        'confirmed_at',
        'notes',
        'status',
        'expiry_date',
    ];

    protected $casts = [
    'expiry_date' => 'datetime',
    'confirmed_at' => 'datetime',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
];


    // Relations
    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'medicine_id');
    }

    public function donor()
    {
        return $this->belongsTo(User::class, 'donor_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}

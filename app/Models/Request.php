<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'medicine_id',
        'requester_id',
        'donor_id',
        'quantity_requested',
        'quantity_remaining',
        'message',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    // Relations
    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function donor()
    {
        return $this->belongsTo(User::class, 'donor_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

   
    protected $fillable = [
        'medicine_id','donor_id','recipient_id','quantity',
        'confirmed_at','notes','status','expiry_date',
    ];

    protected $casts = [
        'expiry_date'  => 'date',
        'confirmed_at' => 'datetime',
    ];

    public function medicine(){ return $this->belongsTo(Medicine::class); }
    public function donor(){ return $this->belongsTo(User::class, 'donor_id'); }
    public function recipient(){ return $this->belongsTo(User::class, 'recipient_id'); }
}
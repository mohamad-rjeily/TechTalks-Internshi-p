<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'reported_id',
        'target_id',
        'reason',
        'status',
        'admin_note',
    ];

    // Relation to the user who reported
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_id');
    }
}

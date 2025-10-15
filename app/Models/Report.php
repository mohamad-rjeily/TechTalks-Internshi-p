<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'reported_id',
        'target_id',
        'target_type',
        'reason',
        'status',
        'admin_note',
    ];

    // Relation to the user who reported
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_id');
    }

    // Polymorphic relation to the reported target (User or Medicine)
     public function target(): MorphTo
    {
        return $this->morphTo();
    }
    
}

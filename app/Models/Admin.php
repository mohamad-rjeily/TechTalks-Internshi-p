<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    // Table name
    protected $table = 'admin';

    // Primary key
    protected $primaryKey = 'id';

    // Auto-incrementing
    public $incrementing = true;

    // Mass assignable
    protected $fillable = [
        'userName', // match DB column
        'password',
    ];

    // Hidden attributes
    protected $hidden = [
        'password',
    ];
}

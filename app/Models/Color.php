<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    // Table name (optional, but good practice)
    protected $table = 'colors';

    // Allow mass assignment
    protected $fillable = [
        'name',
        'code',
        'status',
    ];
}
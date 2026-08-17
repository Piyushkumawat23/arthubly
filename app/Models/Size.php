<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'sizes';

    // Allow mass assignment
    protected $fillable = [
        'name',
        'status',
    ];
}
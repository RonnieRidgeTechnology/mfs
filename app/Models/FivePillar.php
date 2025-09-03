<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FivePillar extends Model
{
    protected $fillable = [
        'name',
        'image',
        'is_active',
    ];
}

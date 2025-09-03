<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RulesRegulation extends Model
{
    protected $table = 'rules_regulation';

    protected $fillable = [
        'title',
        'points',
        'is_active',
    ];

    protected $casts = [
        'points' => 'array',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    protected $table = 'about_us';

    protected $fillable = [
        'meta_title',
        'meta_description',
        'meta_keywords',
        'title',
        'points',
    ];

    protected $casts = [
        'title' => 'array',
        'points' => 'array',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeUpdate extends Model
{
    protected $fillable = [
        'main_title',
        'main_desc',
        'main_image1',
        'main_image2',
        'section1_main_title',
        'section1_title',
        'section1_desc',
        'section1_points',
        'section1_image1',
        'section1_image2',
        'section1_image3',
        'section2_title',
        'section2_desc',
        'section3_main_title',
        'section3_title',
        'section3_desc',
        'section3_image',
        'footer_main_title',
        'footer_main_desc',
        'footer_title',
        'footer_desc',
        'meta_title',
        'meta_desc',
        'meta_keyword',
    ];

    protected $casts = [
        'section1_points' => 'array',
    ];
}

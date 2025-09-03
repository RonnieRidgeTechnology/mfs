<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaqUpdate extends Model
{
    protected $fillable = [
        'title',
        'image',
        'meta_title',
        'meta_desc',
        'meta_keyword',
    ];
}

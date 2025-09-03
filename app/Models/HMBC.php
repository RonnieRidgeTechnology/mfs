<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HMBC extends Model
{
    protected $fillable = [
        'title',
        'desc',
        'location_title',
        'location_desc',
        'location_link',
        'member_title',
        'member_desc',
        'pdf',
        'meta_title',
        'meta_desc',
        'meta_keyword',
    ];
}

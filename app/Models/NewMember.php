<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewMember extends Model
{
    protected $table = 'new_members';

    protected $fillable = [
        'title',
        'desc',
        'pdf',
        'meta_title',
        'meta_desc',
        'meta_keyword',
    ];
}

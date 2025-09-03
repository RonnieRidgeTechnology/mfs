<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactUpdate extends Model
{
    protected $table = 'contact_updates';

    protected $fillable = [
        'title',
        'desc',
        'meta_title',
        'meta_desc',
        'meta_keyword',
    ];
}

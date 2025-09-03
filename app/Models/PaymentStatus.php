<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentStatus extends Model
{

    protected $fillable = [
        'title',
        'description',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'excel_title',
        'excel_file',
    ];
}

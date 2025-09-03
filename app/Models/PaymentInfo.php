<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentInfo extends Model
{
    protected $table = 'payment_infos';
    protected $fillable = [
        'meta_title',
        'meta_description',
        'meta_keywords',
        'title',
        'points',
        'note',
    ];
    protected $casts = [
        'title' => 'array',
        'points' => 'array',
    ];
}

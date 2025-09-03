<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipFeeSetting extends Model
{
    protected $fillable = [
        'member_type',
        'amount',
        'year',
    ];
}

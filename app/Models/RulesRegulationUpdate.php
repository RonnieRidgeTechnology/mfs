<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RulesRegulationUpdate extends Model
{
    protected $table = 'rules_regulation_updates';

    protected $fillable = [
        'meta_title',
        'meta_desc',
        'meta_keyword',
    ];
}

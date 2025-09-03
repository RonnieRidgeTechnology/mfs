<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BurialCouncil extends Model
{
     protected $fillable = [
         'title',
         'desc',
         'image',
         'meta_title',
         'meta_desc',
         'meta_keyword',
     ];
}

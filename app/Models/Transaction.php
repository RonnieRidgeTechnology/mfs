<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    protected $table = "transactions";
    protected $fillable = [
        'date',
        'account',
        'amount',
        'status',
        'user_id',
        'flag_status',
        'no_id_flaged',
        'name',
    ];

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

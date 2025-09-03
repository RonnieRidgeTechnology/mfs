<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'email',
        'role',
        'activity',
        'ip_address',
        'action_type',
        'user_agent',
    ];

    // ✅ Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

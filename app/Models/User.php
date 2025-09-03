<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'unique_id',
        'original_unique_id',
        'name',
        'email',
        'password',
        'type',
        'is_user',
        'phone',
        'street',
        'area',
        'town',
        'postal_code',
        'profile_image',
        'status',
        'is_guest',
        'cover',
        'member_status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    //unique id
    function generateUniqueId($name)
    {
        $firstChar = strtoupper(substr($name, 0, 1)); // Q, H, etc.

        // Get all existing unique IDs starting with this character
        $existingIds = User::where('unique_id', 'like', $firstChar . '%')
            ->pluck('unique_id')
            ->map(function ($id) use ($firstChar) {
                return (int) str_replace($firstChar, '', $id);
            })->toArray();

        // Find next available number
        $next = 1;
        while (in_array($next, $existingIds)) {
            $next++;
        }

        return $firstChar . $next;
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}

<?php

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

if (!function_exists('log_activity')) {
    function log_activity($activity, $user = null)
    {
        $user = $user ?: Auth::user();

        ActivityLog::create([
            'user_id'    => $user?->id,
            'email'      => $user?->email,
            'role'       => $user?->type, // if using Spatie roles
            'activity'   => $activity,
            'action_type'  => $actionType ?? 'system',
            'ip_address' => request()->ip(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
        ]);
    }
}

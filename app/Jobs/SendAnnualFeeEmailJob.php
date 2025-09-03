<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\MembershipFeeSetting;
use App\Models\Transaction;
use App\Models\ActivityLog;
use App\Models\WebSetting;
use App\Mail\AnnualFeeCompletedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendAnnualFeeEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $year;

    public function __construct($userId, $year)
    {
        $this->userId = $userId;
        $this->year = $year;
    }

    public function handle()
    {
        $user = User::find($this->userId);
        if (!$user || $user->type !== 'member') {
            return;
        }

        $feeSetting = MembershipFeeSetting::where('member_type', 'annual_fee')
            ->where('year', $this->year)
            ->first();

        if (!$feeSetting) {
            return;
        }

        $requiredAmount = $feeSetting->amount;
        $totalPaid = Transaction::where('user_id', $this->userId)
            ->whereYear('date', $this->year)
            ->sum('amount');

        $activityMsg = "Annual fee completion email sent to member {$user->name} (user ID {$user->unique_id}) for year {$this->year}.";
        $alreadySent = ActivityLog::where('activity', $activityMsg)->exists();

        $webSetting = WebSetting::first();
        $shouldSend = $webSetting && $webSetting->send_fee_completion_email;

        if ($totalPaid >= $requiredAmount && !$alreadySent && $shouldSend) {
            Mail::to($user->email)->send(new AnnualFeeCompletedMail($user, $this->year, $requiredAmount));
            log_activity($activityMsg);
        }
    }
}

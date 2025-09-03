<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\WebSetting;

class AnnualFeeCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $year;
    public $amount;
    public $webSetting;

    public function __construct($user, $year, $amount)
    {
        $this->user = $user;
        $this->year = $year;
        $this->amount = $amount;
        $this->webSetting = WebSetting::first();
    }

    public function build()
    {
         return $this->subject('Annual Membership Fee Payment Successful – Congratulations! 🎉')
                    ->view('emails.annual_fee_completed')
                    ->with([
                        'webSetting' => $this->webSetting,
                    ]);
    }
}

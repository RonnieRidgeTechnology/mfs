<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\WebSetting;

class GuestPromotedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $member;
    public $plainPassword;
    public $webSetting;

    /**
     * Create a new message instance.
     *
     * @param  mixed  $member
     * @param  string|null  $plainPassword
     * @return void
     */
    public function __construct($member, $plainPassword = null)
    {
        $this->member = $member;
        $this->plainPassword = $plainPassword;
        // Fetch the first WebSetting record to pass to the view
        $this->webSetting = WebSetting::first();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Congratulations! Your MFS Account Has Been Upgraded 🎉')
            ->view('emails.guest_promoted')
            ->with([
                'member' => $this->member,
                'plainPassword' => $this->plainPassword,
                'webSetting' => $this->webSetting,
            ]);
    }
}

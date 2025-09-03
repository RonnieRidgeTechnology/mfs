<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\WebSetting;

class MemberCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $member;
    public $plainPassword;
    public $webSetting;

    /**
     * Create a new message instance.
     */
    public function __construct(User $member, $plainPassword)
    {
        $this->member = $member;
        $this->plainPassword = $plainPassword;
        $this->webSetting = WebSetting::first();
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Welcome to MFS — Your Member Account is Ready 🎉')
            ->view('emails.member_created')
            ->with([
                'name' => $this->member->name,
                'email' => $this->member->email,
                'plainPassword' => $this->plainPassword,
                'webSetting' => $this->webSetting,
            ]);
    }
}

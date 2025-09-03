<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\WebSetting;

class AdminCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $admin;
    public $plainPassword;
    public $webSetting;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\User  $admin
     * @param  string  $plainPassword
     * @return void
     */
    public function __construct(User $admin, $plainPassword)
    {
        $this->admin = $admin;
        $this->plainPassword = $plainPassword;
        $this->webSetting = WebSetting::first();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Welcome to MFS — Your Admin Account is Ready 🎉')
            ->view('emails.admin_created', [
                'admin' => $this->admin,
                'name' => $this->admin->name,
                'email' => $this->admin->email,
                'plainPassword' => $this->plainPassword,
                'webSetting' => $this->webSetting,
            ]);
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subjectLine;
    public $content;

    public function __construct($subjectLine, $content)
    {
        $this->subjectLine = $subjectLine;
        $this->content = $content;
    }

    public function build()
    {
        return $this->subject($this->subjectLine)
                    ->view('emails.newsletter');
    }
}

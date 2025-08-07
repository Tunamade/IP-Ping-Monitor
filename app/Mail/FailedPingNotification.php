<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FailedPingNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $ip;

    public function __construct($ip)
    {
        $this->ip = $ip;
    }



    public function build()
    {
        return $this->subject('Ping Başarısız')->view('emails.failed_ping');
    }

}

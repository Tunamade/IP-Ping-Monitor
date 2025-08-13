<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FailedPingNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $ip;
    public $name; // ✅ eklendi

    public function __construct($ip, $name = null)
    {
        $this->ip = $ip;
        $this->name = $name ?? '-'; // boşsa "-" göster
    }

    public function build()
    {
        return $this->subject('Ping Başarısız')
            ->view('emails.failed_ping')
            ->with([
                'ip' => $this->ip,
                'name' => $this->name
            ]);
    }
}

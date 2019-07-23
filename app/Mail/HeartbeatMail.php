<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class HeartbeatMail extends Mailable
{
    use Queueable, SerializesModels;

    public $values;
    public $weather;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($values, $weather)
    {
        $this->values = $values;
        $this->weather = $weather ;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Status - Sunny Days')
            ->markdown('emails.heartbeat');
    }
}

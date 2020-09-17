<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;

class StatisticsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $logs;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Collection $logs)
    {
        $this->logs = $logs;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Opbrengsten - SunnyDays')
            ->markdown('emails.statistics');
    }
}

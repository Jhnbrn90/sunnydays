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

    public Collection $logs;

    public function __construct(Collection $logs)
    {
        $this->logs = $logs;
    }

    public function build()
    {
        return $this
            ->subject('Opbrengsten - SunnyDays')
            ->markdown('emails.statistics');
    }
}

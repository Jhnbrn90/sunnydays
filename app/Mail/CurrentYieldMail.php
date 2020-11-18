<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;

class CurrentYieldMail extends Mailable
{
    use Queueable, SerializesModels;

    public Collection $currentYields;
    public array $weatherCondition;

    /**
     * Create a new message instance.
     *
     * @param Collection $currentYields
     * @param array $weatherCondition
     */
    public function __construct(Collection $currentYields, array $weatherCondition)
    {
        $this->currentYields = $currentYields;
        $this->weatherCondition = $weatherCondition;
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
            ->markdown('emails.current-yield');
    }
}

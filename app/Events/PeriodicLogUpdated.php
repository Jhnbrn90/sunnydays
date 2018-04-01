<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PeriodicLogUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $power;
    public $temperature;
    public $weather;
    public $time;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($powerlog)
    {
        $this->power = $powerlog->current_power;
        $this->temperature = $powerlog->temperature;
        $this->weather = $powerlog->weather_condition;
        $this->time = $powerlog->created_at->format('H:i');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('periodic-update');
        // return new PrivateChannel('channel-name');
    }
}

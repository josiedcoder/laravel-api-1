<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExchangeRateEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    private $exchangeRate;

    public function __construct($exchangeRate)
    {
        //
        $this->exchangeRate = $exchangeRate;
    }

    public function broadcastWith()
    {

        return [
            $this->exchangeRate->symbol =>
            [
                'price' => $this->exchangeRate->price,
                'amount' => $this->exchangeRate->amount
            ]
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('exchange-rate');
    }

    public function broadcastAs()
    {
        return $this->exchangeRate->symbol;
    }
}

<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TFID implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $tfid;
    public string $fridge_id;
    public ?string $is_user;

    public function __construct(string $tfid, string $fridge_id, ?string $is_user)
    {
        $this->tfid = $tfid;
        $this->fridge_id = $fridge_id;
        $this->is_user = $is_user;
    }

    public function broadcastOn()
    {
        return ['GrabIT'];
    }

    public function broadcastAs()
    {
        return 'Tfid-channel';
    }
}

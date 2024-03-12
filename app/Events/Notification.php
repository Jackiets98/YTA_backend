<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Notification implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $eventId;
    public $message;
    public $redirectPath;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($eventId, $message, $redirectPath)
    {
        $this->eventId = $eventId;
        $this->message = $message;
        $this->redirectPath = $redirectPath;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('pusher-notification');
    }

    public function broadcastAs()
    {
        switch ($this->eventId) {
            case 1:
                return 'deliveryStatus';
            case 2:
                return 'geofenceAlert';
            case 3:
                return 'speedAlert';
            default:
                return 'defaultEventName';
        }
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'message' => $this->message,
            'redirectPath' => $this->redirectPath,
        ];
    }
}

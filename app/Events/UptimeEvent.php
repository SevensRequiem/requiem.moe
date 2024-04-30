<?php
namespace App\Events;

use Pusher\Pusher;
use Illuminate\Broadcasting\Channel;
use Illuminate\Support\Facades\Log;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UptimeEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $uptime;

    public function __construct(array $uptime)
    {
        $this->uptime = $uptime;
    }

public function broadcastOn()
{
    $options = array(
        'cluster' => env('PUSHER_APP_CLUSTER'),
        'encrypted' => true
    );

    $pusher = new Pusher(
        env('PUSHER_APP_KEY'),
        env('PUSHER_APP_SECRET'),
        env('PUSHER_APP_ID'),
        $options
    );

    $channel = 'uptime';
    $event = new UptimeEvent($this->uptime);

    $pusher->trigger($channel, 'uptime-updated', $event);

    Log::info('Broadcasting UptimeEvent on channel: ' . $channel);

    return new Channel($channel);
}

    public function broadcastAs()
    {
        return 'uptime-updated';
    }
}
?>
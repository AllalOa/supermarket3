<?php
// app/Events/ZeroStockAlert.php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Product;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ZeroStockAlert implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $product;
    public $message;
    public $userId;

    /**
     * Create a new event instance.
     */
    public function __construct(Product $product, $message, $userId)
    {
        $this->product = $product;
        $this->message = $message;
        $this->userId = $userId;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn()
    {
        return new PrivateChannel('supervisor.' . $this->userId);
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs()
    {
        return 'zero.stock.alert';
    }
}
<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $status;
    public $message;
    public $cashierId;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Order $order
     * @param string $status   // 'approved' or 'rejected'
     * @param string $message
     */
    public function __construct(Order $order, string $status, string $message)
    {
        $this->order    = $order;
        $this->status   = $status;
        $this->message  = $message;
        $this->cashierId = $order->cashier_id; // Ensure your Order model has this field
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * We are using a private channel for the specific cashier.
     */
    public function broadcastOn()
    {
        return new PrivateChannel('cashier.' . $this->cashierId);
    }

    /**
     * Optional: Set a custom event name for the front end.
     */
    public function broadcastAs()
    {
        return 'order.status.updated';
    }

}

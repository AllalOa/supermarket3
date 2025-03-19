<?php

namespace App\Listeners;

use App\Events\OrderStatusUpdated;
use App\Models\Notification; // Your Notification model for the notifications table
use Illuminate\Contracts\Queue\ShouldQueue;

class StoreOrderStatusNotification implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\OrderStatusUpdated  $event
     * @return void
     */
    public function handle(OrderStatusUpdated $event)
    {
        // Create a new notification record in the database for the cashier
        Notification::create([
            'user_id' => $event->cashierId,
            'message' => $event->message,
            'is_read' => false,
        ]);
    }
}

<?php
// Step 1: Create a notification class
// app/Notifications/NewOrderNotification.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class NewOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;
    
    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'message' => 'New order #' . $this->order->id . ' has been created',
            'amount' => $this->order->total_amount,
            'created_by' => $this->order->cashier_name,
            'created_at' => $this->order->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'order_id' => $this->order->id,
            'message' => 'New order #' . $this->order->id . ' has been created',
            'amount' => $this->order->total_amount,
            'created_by' => $this->order->cashier_name,
            'created_at' => $this->order->created_at->format('Y-m-d H:i:s'),
        ]);
    }
}
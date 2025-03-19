<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class OrderStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;
    public $status;
    public $message;

    /**
     * Create a new notification instance.
     *
     * @param  mixed  $order
     * @param  string $status   e.g., 'approved' or 'rejected'
     * @param  string $message
     */
    public function __construct($order, $status, $message)
    {
        $this->order   = $order;
        $this->status  = $status;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification for database storage.
     */
    public function toDatabase($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'status'   => $this->status,
            'message'  => $this->message,
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'order_id' => $this->order->id,
            'status'   => $this->status,
            'message'  => $this->message,
        ]);
    }
}

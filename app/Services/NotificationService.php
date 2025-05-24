<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    public function getSupervisorNotificationData()
    {
        $lowStockProducts = Product::where('quantity', '>', 0)
            ->where('quantity', '<=', 10)
            ->get();

        $zeroStockProducts = Product::where('quantity', 0)->get();

        $unreadNotifications = Notification::where('user_id', Auth::id())
            ->where('is_read', 0)
            ->whereRaw("message LIKE '%stock%' OR message LIKE '%Alerte%'")
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'lowStockProducts' => $lowStockProducts,
            'zeroStockProducts' => $zeroStockProducts,
            'unreadNotifications' => $unreadNotifications
        ];
    }
} 
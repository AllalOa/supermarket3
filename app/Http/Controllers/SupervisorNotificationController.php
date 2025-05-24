<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupervisorNotificationController extends Controller
{
    /**
     * Get notification data for the dropdown
     */
    public function getDropdownData()
    {
        $unreadNotifications = Notification::where('user_id', Auth::id())
            ->where('is_read', 0)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'unreadNotifications' => $unreadNotifications,
            'unreadCount' => $unreadNotifications->count()
        ]);
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $notification->is_read = 1;
        $notification->save();

        // Get updated notifications count
        $newCount = Notification::where('user_id', Auth::id())
            ->where('is_read', 0)
            ->whereRaw("message LIKE '%stock%' OR message LIKE '%Alerte%'")
            ->count();

        return response()->json([
            'success' => true,
            'newCount' => $newCount
        ]);
    }

    /**
     * Get all notification data for supervisor
     */
    public function getNotificationData()
    {
        $lowStockProducts = Product::where('quantity', '>', 0)
            ->where('quantity', '<=', 10)
            ->get();

        $zeroStockProducts = Product::where('quantity', 0)->get();

        $unreadNotifications = Notification::where('user_id', Auth::id())
            ->where('is_read', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('layouts.appp')->with([
            'lowStockProducts' => $lowStockProducts,
            'zeroStockProducts' => $zeroStockProducts,
            'unreadNotifications' => $unreadNotifications
        ]);
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadCount()
    {
        $count = Notification::where('user_id', Auth::id())
            ->where('is_read', 0)
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Display unread notifications
     */
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->where('is_read', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('supervisor.notifications.index', compact('notifications'));
    }
} 
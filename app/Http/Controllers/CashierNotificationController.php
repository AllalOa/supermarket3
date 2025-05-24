<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashierNotificationController extends Controller
{
    public function index()
    {
        try {
            $notifications = Notification::where('user_id', Auth::id())
                ->where('is_read', 0)
                ->latest()
                ->take(10)
                ->get();

            $unreadCount = Notification::where('user_id', Auth::id())
                ->where('is_read', 0)
                ->count();

            return response()->json([
                'notifications' => $notifications,
                'unreadCount' => $unreadCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch notifications',
                'message' => $e->getMessage()
            ], 500);
        }
    }

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
            ->count();

        return response()->json([
            'success' => true,
            'newCount' => $newCount
        ]);
    }

    public function all()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->where('is_read', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('cashier.notifications.index', compact('notifications'));
    }
} 
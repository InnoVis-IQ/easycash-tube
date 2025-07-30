<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\User;

class NotificationController extends Controller
{
    /**
     * Display notifications for admin
     */
    public function index()
    {
        $notifications = Cache::get('admin_notifications', []);
        $announcements = Cache::get('site_announcement', null);
        
        return view('backend.admin.notifications.index', compact('notifications', 'announcements'));
    }

    /**
     * Send notification to specific users
     */
    public function send(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,warning,success,danger',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $notification = [
            'id' => uniqid(),
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
            'user_ids' => $request->user_ids,
            'created_at' => now(),
            'read_by' => []
        ];

        $notifications = Cache::get('admin_notifications', []);
        $notifications[] = $notification;
        Cache::put('admin_notifications', $notifications, now()->addDays(30));

        return back()->with('success', 'Notification sent successfully');
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request)
    {
        $notificationId = $request->notification_id;
        $userId = auth()->user()->id;

        $notifications = Cache::get('admin_notifications', []);
        
        foreach ($notifications as &$notification) {
            if ($notification['id'] === $notificationId) {
                if (!in_array($userId, $notification['read_by'])) {
                    $notification['read_by'][] = $userId;
                }
                break;
            }
        }

        Cache::put('admin_notifications', $notifications, now()->addDays(30));

        return response()->json(['success' => true]);
    }

    /**
     * Delete notification
     */
    public function destroy(Request $request)
    {
        $notificationId = $request->notification_id;
        
        $notifications = Cache::get('admin_notifications', []);
        $notifications = array_filter($notifications, function($notification) use ($notificationId) {
            return $notification['id'] !== $notificationId;
        });

        Cache::put('admin_notifications', array_values($notifications), now()->addDays(30));

        return back()->with('success', 'Notification deleted successfully');
    }

    /**
     * Get user notifications (for users)
     */
    public function getUserNotifications()
    {
        $userId = auth()->user()->id;
        $notifications = Cache::get('admin_notifications', []);
        $userNotifications = [];

        foreach ($notifications as $notification) {
            if (in_array($userId, $notification['user_ids'])) {
                $userNotifications[] = $notification;
            }
        }

        return response()->json($userNotifications);
    }
} 
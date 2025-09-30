<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserNotification;

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
        $userId = Auth::user()->id;

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
        $userId = Auth::user()->id;
        
        // Get both old cache notifications and new database notifications
        $cacheNotifications = Cache::get('admin_notifications', []);
        $dbNotifications = UserNotification::getRecentNotifications($userId, 20);
        
        $userNotifications = [];

        // Process cache notifications
        foreach ($cacheNotifications as $notification) {
            if (in_array($userId, $notification['user_ids'])) {
                $userNotifications[] = [
                    'id' => 'cache_' . $notification['id'],
                    'title' => $notification['title'],
                    'message' => $notification['message'],
                    'type' => $notification['type'],
                    'created_at' => $notification['created_at'],
                    'is_read' => in_array($userId, $notification['read_by'] ?? []),
                    'source' => 'cache'
                ];
            }
        }

        // Process database notifications
        foreach ($dbNotifications as $notification) {
            $userNotifications[] = [
                'id' => 'db_' . $notification->id,
                'title' => $notification->title,
                'message' => $notification->message,
                'type' => $notification->type,
                'created_at' => $notification->created_at,
                'is_read' => $notification->is_read,
                'is_important' => $notification->is_important,
                'data' => $notification->data,
                'source' => 'database'
            ];
        }

        // Sort by creation date
        usort($userNotifications, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return response()->json($userNotifications);
    }

    /**
     * Mark database notification as read
     */
    public function markDatabaseNotificationAsRead($id)
    {
        $notification = UserNotification::find($id);
        
        if ($notification && $notification->user_id === Auth::id()) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
    }

    /**
     * Get unread notification count
     */
    public function getUnreadCount()
    {
        $userId = Auth::id();
        $dbUnreadCount = UserNotification::getUnreadCount($userId);
        
        // Count unread cache notifications
        $cacheNotifications = Cache::get('admin_notifications', []);
        $cacheUnreadCount = 0;
        
        foreach ($cacheNotifications as $notification) {
            if (in_array($userId, $notification['user_ids']) && 
                !in_array($userId, $notification['read_by'] ?? [])) {
                $cacheUnreadCount++;
            }
        }
        
        return response()->json([
            'unread_count' => $dbUnreadCount + $cacheUnreadCount,
            'db_unread' => $dbUnreadCount,
            'cache_unread' => $cacheUnreadCount
        ]);
    }

    /**
     * Admin: Send notification to all users
     */
    public function sendToAllUsers(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:new_offer,system_alert',
            'is_important' => 'boolean'
        ]);

        $users = User::where('role', 'USER')->get();
        
        foreach ($users as $user) {
            UserNotification::create([
                'user_id' => $user->id,
                'type' => $request->type,
                'title' => $request->title,
                'message' => $request->message,
                'is_important' => $request->boolean('is_important', false),
                'expires_at' => now()->addDays(30)
            ]);
        }

        return back()->with('success', "Notification sent to {$users->count()} users");
    }

    /**
     * Admin: Send new offer notification
     */
    public function sendNewOffer(Request $request)
    {
        $request->validate([
            'offer_title' => 'required|string|max:255',
            'offer_description' => 'required|string',
            'target_users' => 'required|in:all,active,new'
        ]);

        $query = User::where('role', 'USER');
        
        if ($request->target_users === 'active') {
            // Users with active subscriptions
            $query->whereHas('subscriptions', function($q) {
                $q->where('expire_at', '>', now());
            });
        } elseif ($request->target_users === 'new') {
            // Users registered in last 7 days
            $query->where('created_at', '>=', now()->subDays(7));
        }

        $users = $query->get();
        
        foreach ($users as $user) {
            UserNotification::createNewOfferNotification(
                $user->id, 
                $request->offer_title, 
                $request->offer_description
            );
        }

        return back()->with('success', "New offer notification sent to {$users->count()} users");
    }

    /**
     * Clean up expired notifications
     */
    public function cleanupExpired()
    {
        $deletedCount = UserNotification::cleanupExpiredNotifications();
        return response()->json(['success' => true, 'deleted_count' => $deletedCount]);
    }
} 
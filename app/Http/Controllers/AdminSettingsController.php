<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class AdminSettingsController extends Controller
{
    /**
     * Display the settings page
     */
    public function index()
    {
        $settings = $this->getSettings();
        return view('backend.admin.settings.index', compact('settings'));
    }

    /**
     * Update general settings
     */
    public function updateGeneral(Request $request)
    {
        $request->validate([
            'daily_return_percentage' => 'required|numeric|min:0|max:100',
            'min_withdrawal_limit' => 'required|numeric|min:0',
            'max_withdrawal_limit' => 'required|numeric|min:0',
            'referral_bonus_percentage' => 'required|numeric|min:0|max:100',
            'site_maintenance_mode' => 'boolean',
            'new_user_bonus' => 'required|numeric|min:0',
        ]);

        $settings = [
            'daily_return_percentage' => $request->daily_return_percentage,
            'min_withdrawal_limit' => $request->min_withdrawal_limit,
            'max_withdrawal_limit' => $request->max_withdrawal_limit,
            'referral_bonus_percentage' => $request->referral_bonus_percentage,
            'site_maintenance_mode' => $request->has('site_maintenance_mode'),
            'new_user_bonus' => $request->new_user_bonus,
        ];

        $this->saveSettings($settings);

        return back()->with('success', 'General settings updated successfully');
    }

    /**
     * Update payment settings
     */
    public function updatePayment(Request $request)
    {
        $request->validate([
            'easypaisa_enabled' => 'boolean',
            'jazzcash_enabled' => 'boolean',
            'nayapay_enabled' => 'boolean',
            'bank_transfer_enabled' => 'boolean',
            'payment_processing_fee' => 'required|numeric|min:0|max:100',
        ]);

        $settings = [
            'easypaisa_enabled' => $request->has('easypaisa_enabled'),
            'jazzcash_enabled' => $request->has('jazzcash_enabled'),
            'nayapay_enabled' => $request->has('nayapay_enabled'),
            'bank_transfer_enabled' => $request->has('bank_transfer_enabled'),
            'payment_processing_fee' => $request->payment_processing_fee,
        ];

        $this->saveSettings($settings);

        return back()->with('success', 'Payment settings updated successfully');
    }

    /**
     * Update notification settings
     */
    public function updateNotifications(Request $request)
    {
        $request->validate([
            'email_notifications_enabled' => 'boolean',
            'sms_notifications_enabled' => 'boolean',
            'push_notifications_enabled' => 'boolean',
            'withdrawal_notification_threshold' => 'required|numeric|min:0',
        ]);

        $settings = [
            'email_notifications_enabled' => $request->has('email_notifications_enabled'),
            'sms_notifications_enabled' => $request->has('sms_notifications_enabled'),
            'push_notifications_enabled' => $request->has('push_notifications_enabled'),
            'withdrawal_notification_threshold' => $request->withdrawal_notification_threshold,
        ];

        $this->saveSettings($settings);

        return back()->with('success', 'Notification settings updated successfully');
    }

    /**
     * Send announcement to users
     */
    public function sendAnnouncement(Request $request)
    {
        $request->validate([
            'announcement_title' => 'required|string|max:255',
            'announcement_message' => 'required|string',
            'announcement_type' => 'required|in:info,warning,success,danger',
            'target_users' => 'required|in:all,active,inactive,specific',
            'user_ids' => 'required_if:target_users,specific|array',
        ]);

        // Store announcement in database or cache
        $announcement = [
            'title' => $request->announcement_title,
            'message' => $request->announcement_message,
            'type' => $request->announcement_type,
            'target_users' => $request->target_users,
            'user_ids' => $request->user_ids,
            'created_at' => now(),
        ];

        Cache::put('site_announcement', $announcement, now()->addDays(7));

        return back()->with('success', 'Announcement sent successfully');
    }

    /**
     * Clear cache
     */
    public function clearCache()
    {
        Cache::flush();
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');

        return back()->with('success', 'Cache cleared successfully');
    }

    /**
     * Get all settings
     */
    private function getSettings()
    {
        return [
            // General settings
            'daily_return_percentage' => Cache::get('daily_return_percentage', 2.5),
            'min_withdrawal_limit' => Cache::get('min_withdrawal_limit', 1000),
            'max_withdrawal_limit' => Cache::get('max_withdrawal_limit', 50000),
            'referral_bonus_percentage' => Cache::get('referral_bonus_percentage', 10),
            'site_maintenance_mode' => Cache::get('site_maintenance_mode', false),
            'new_user_bonus' => Cache::get('new_user_bonus', 100),

            // Payment settings
            'easypaisa_enabled' => Cache::get('easypaisa_enabled', true),
            'jazzcash_enabled' => Cache::get('jazzcash_enabled', true),
            'nayapay_enabled' => Cache::get('nayapay_enabled', true),
            'bank_transfer_enabled' => Cache::get('bank_transfer_enabled', true),
            'payment_processing_fee' => Cache::get('payment_processing_fee', 2.5),

            // Notification settings
            'email_notifications_enabled' => Cache::get('email_notifications_enabled', true),
            'sms_notifications_enabled' => Cache::get('sms_notifications_enabled', false),
            'push_notifications_enabled' => Cache::get('push_notifications_enabled', false),
            'withdrawal_notification_threshold' => Cache::get('withdrawal_notification_threshold', 10000),
        ];
    }

    /**
     * Save settings to cache
     */
    private function saveSettings($settings)
    {
        foreach ($settings as $key => $value) {
            Cache::put($key, $value, now()->addYear());
        }
    }
} 
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserSubscription;
use App\Models\UserNotification;

class CheckPlanExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plans:check-expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for expiring plans and send notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expiring plans...');
        
        // Get all active subscriptions that expire within 3 days
        $expiringSubscriptions = UserSubscription::where('expire_at', '>', now())
            ->where('expire_at', '<=', now()->addDays(3))
            ->with(['user', 'plan'])
            ->get();
        
        $notificationsSent = 0;
        
        foreach ($expiringSubscriptions as $subscription) {
            // Check if notification already sent for this subscription
            $existingNotification = UserNotification::where('user_id', $subscription->user_id)
                ->where('type', 'plan_expiry')
                ->where('data->plan_title', $subscription->plan->title ?? 'Unknown Plan')
                ->whereDate('created_at', today())
                ->first();
            
            if (!$existingNotification) {
                $notification = UserNotification::createPlanExpiryNotification(
                    $subscription->user_id,
                    $subscription->plan->title ?? 'Your Plan',
                    $subscription->expire_at->format('Y-m-d')
                );
                
                if ($notification) {
                    $notificationsSent++;
                    $this->line("Sent expiry notification to user {$subscription->user->name} for {$subscription->plan->title}");
                }
            }
        }
        
        // Clean up expired notifications
        $deletedCount = UserNotification::cleanupExpiredNotifications();
        
        $this->info("Plan expiry check completed. Sent {$notificationsSent} notifications, cleaned up {$deletedCount} expired notifications.");
        
        return 0;
    }
}

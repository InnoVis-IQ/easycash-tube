<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'is_read',
        'is_important',
        'read_at',
        'expires_at'
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'is_important' => 'boolean',
        'read_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    /**
     * Get the user that owns the notification
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for important notifications
     */
    public function scopeImportant($query)
    {
        return $query->where('is_important', true);
    }

    /**
     * Scope for active notifications (not expired)
     */
    public function scopeActive($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread()
    {
        $this->update([
            'is_read' => false,
            'read_at' => null
        ]);
    }

    /**
     * Create plan expiry notification
     */
    public static function createPlanExpiryNotification($userId, $planTitle, $expiryDate)
    {
        $daysLeft = Carbon::parse($expiryDate)->diffInDays(now());
        
        if ($daysLeft <= 3) {
            $title = $daysLeft == 0 ? 'Plan Expired!' : 'Plan Expiring Soon!';
            $message = $daysLeft == 0 
                ? "Your {$planTitle} plan has expired. Consider renewing to continue earning!"
                : "Your {$planTitle} plan expires in {$daysLeft} day(s). Renew now to avoid missing out on earnings!";
            
            return self::create([
                'user_id' => $userId,
                'type' => 'plan_expiry',
                'title' => $title,
                'message' => $message,
                'data' => [
                    'plan_title' => $planTitle,
                    'expiry_date' => $expiryDate,
                    'days_left' => $daysLeft
                ],
                'is_important' => $daysLeft <= 1
            ]);
        }
        
        return null;
    }

    /**
     * Create new offer notification
     */
    public static function createNewOfferNotification($userId, $offerTitle, $offerDescription)
    {
        return self::create([
            'user_id' => $userId,
            'type' => 'new_offer',
            'title' => 'New Offer Available!',
            'message' => "Check out our new offer: {$offerTitle}. {$offerDescription}",
            'data' => [
                'offer_title' => $offerTitle,
                'offer_description' => $offerDescription
            ],
            'expires_at' => now()->addDays(7) // Offer notifications expire after 7 days
        ]);
    }

    /**
     * Create deposit approved notification
     */
    public static function createDepositApprovedNotification($userId, $amount)
    {
        return self::create([
            'user_id' => $userId,
            'type' => 'deposit_approved',
            'title' => 'Deposit Approved!',
            'message' => "Your deposit of Rs. {$amount} has been approved and added to your Main Wallet.",
            'data' => [
                'amount' => $amount,
                'wallet_type' => 'main'
            ],
            'is_important' => true
        ]);
    }

    /**
     * Create withdrawal processed notification
     */
    public static function createWithdrawalProcessedNotification($userId, $amount)
    {
        return self::create([
            'user_id' => $userId,
            'type' => 'withdrawal_processed',
            'title' => 'Withdrawal Processed!',
            'message' => "Your withdrawal of Rs. {$amount} has been processed and transferred to your account.",
            'data' => [
                'amount' => $amount
            ],
            'is_important' => true
        ]);
    }

    /**
     * Create referral bonus notification
     */
    public static function createReferralBonusNotification($userId, $amount, $referredUserName)
    {
        return self::create([
            'user_id' => $userId,
            'type' => 'referral_bonus',
            'title' => 'Referral Bonus Earned!',
            'message' => "You earned Rs. {$amount} referral bonus! {$referredUserName} bought a plan using your referral code. Bonus added to your Main Wallet.",
            'data' => [
                'amount' => $amount,
                'referred_user' => $referredUserName,
                'wallet_type' => 'main'
            ],
            'is_important' => true
        ]);
    }

    /**
     * Create lucky winner notification
     */
    public static function createLuckyWinnerNotification($userId, $planTitle, $amount)
    {
        return self::create([
            'user_id' => $userId,
            'type' => 'lucky_winner',
            'title' => 'Congratulations! You Won!',
            'message' => "🎉 You won the Lucky Withdraw contest! You received {$planTitle} (Rs. {$amount}) absolutely free!",
            'data' => [
                'plan_title' => $planTitle,
                'amount' => $amount,
                'wallet_type' => 'main'
            ],
            'is_important' => true
        ]);
    }

    /**
     * Get unread count for user
     */
    public static function getUnreadCount($userId)
    {
        return self::where('user_id', $userId)
                   ->unread()
                   ->active()
                   ->count();
    }

    /**
     * Get recent notifications for user
     */
    public static function getRecentNotifications($userId, $limit = 10)
    {
        return self::where('user_id', $userId)
                   ->active()
                   ->orderBy('created_at', 'desc')
                   ->limit($limit)
                   ->get();
    }

    /**
     * Clean up expired notifications
     */
    public static function cleanupExpiredNotifications()
    {
        return self::where('expires_at', '<=', now())->delete();
    }
}

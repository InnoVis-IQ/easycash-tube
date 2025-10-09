<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\LuckyWithdrawEntry;
use App\Models\User;
use App\Models\UserSubscription;
use App\Models\InvestmentPlan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LuckyWithdrawController extends Controller
{
    /**
     * Display the lucky withdraw page for users
     */
    public function index()
    {
        $currentUser = Auth::user();
        
        // Get current round information
        $currentRoundStart = LuckyWithdrawEntry::getCurrentRoundStartDate();
        $currentRoundEnd = LuckyWithdrawEntry::getCurrentRoundEndDate();
        $isRoundActive = LuckyWithdrawEntry::isCurrentRoundActive();
        
        // Check if user already has an entry for current round
        $userEntry = LuckyWithdrawEntry::where('user_id', $currentUser->id)
            ->where('round_start_date', $currentRoundStart)
            ->first();
        
        // Get current round entries count
        $currentRoundEntries = LuckyWithdrawEntry::currentRound()
            ->approved()
            ->activeRound()
            ->count();
        
        // Get user's entry history
        $userEntries = LuckyWithdrawEntry::where('user_id', $currentUser->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get recent winners
        $recentWinners = LuckyWithdrawEntry::where('is_winner', true)
            ->with('user')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();
        
        // Get user's wallet balances
        $mainWallet = \App\Models\UserWallet::getOrCreateWallet($currentUser->id, 'main');
        $earnWallet = \App\Models\UserWallet::getOrCreateWallet($currentUser->id, 'earn');
        $totalWalletBalance = \App\Models\UserWallet::getTotalBalance($currentUser->id);
        $withdrawableBalance = \App\Models\UserWallet::getWithdrawableBalance($currentUser->id);
        
        return view('backend.user.lucky-withdraw.index', compact(
            'currentRoundStart',
            'currentRoundEnd',
            'isRoundActive',
            'userEntry',
            'currentRoundEntries',
            'userEntries',
            'recentWinners',
            'mainWallet',
            'earnWallet',
            'totalWalletBalance',
            'withdrawableBalance'
        ));
    }

    /**
     * Show the form for creating a new entry
     */
    public function create()
    {
        $currentUser = Auth::user();
        
        // Check if user already has an entry for current round
        $currentRoundStart = LuckyWithdrawEntry::getCurrentRoundStartDate();
        $userEntry = LuckyWithdrawEntry::where('user_id', $currentUser->id)
            ->where('round_start_date', $currentRoundStart)
            ->first();
        
        if ($userEntry) {
            return redirect()->route('lucky-withdraw.index')
                ->with('error', 'You already have an entry for the current round.');
        }
        
        $isRoundActive = LuckyWithdrawEntry::isCurrentRoundActive();
        if (!$isRoundActive) {
            return redirect()->route('lucky-withdraw.index')
                ->with('error', 'Current round is not active. Please wait for the next round.');
        }
        
        // Get user's wallet balances
        $mainWallet = \App\Models\UserWallet::getOrCreateWallet($currentUser->id, 'main');
        $earnWallet = \App\Models\UserWallet::getOrCreateWallet($currentUser->id, 'earn');
        $totalWalletBalance = \App\Models\UserWallet::getTotalBalance($currentUser->id);
        $withdrawableBalance = \App\Models\UserWallet::getWithdrawableBalance($currentUser->id);
        
        // Get round dates for display
        $currentRoundEnd = LuckyWithdrawEntry::getCurrentRoundEndDate();
        
        return view('backend.user.lucky-withdraw.create', compact(
            'mainWallet',
            'earnWallet',
            'totalWalletBalance',
            'withdrawableBalance',
            'currentRoundStart',
            'currentRoundEnd'
        ));
    }

    /**
     * Store a newly created entry
     */
    public function store(Request $request)
    {
        $request->validate([
            'terms' => 'required|accepted',
        ]);

        $currentUser = Auth::user();
        
        // Check if user already has an entry for current round
        $currentRoundStart = LuckyWithdrawEntry::getCurrentRoundStartDate();
        $existingEntry = LuckyWithdrawEntry::where('user_id', $currentUser->id)
            ->where('round_start_date', $currentRoundStart)
            ->first();
        
        if ($existingEntry) {
            return redirect()->route('lucky-withdraw.index')
                ->with('error', 'You already have an entry for the current round.');
        }
        
        $isRoundActive = LuckyWithdrawEntry::isCurrentRoundActive();
        if (!$isRoundActive) {
            return redirect()->route('lucky-withdraw.index')
                ->with('error', 'Current round is not active. Please wait for the next round.');
        }

        // Check if user has sufficient balance in Main Wallet
        $mainWallet = \App\Models\UserWallet::getOrCreateWallet($currentUser->id, 'main');
        if ($mainWallet->balance < 100) {
            return redirect()->route('lucky-withdraw.index')
                ->with('error', 'Insufficient balance. You need Rs. 100 in your Main Wallet to enter the contest.');
        }

        try {
            DB::beginTransaction();
            
            // Deduct entry fee from Main Wallet
            $deductResult = $mainWallet->deductFunds(100, 'plan_purchase');
            
            if (!$deductResult) {
                throw new \Exception('Failed to deduct funds from wallet');
            }

            // Create new entry
            $entry = LuckyWithdrawEntry::create([
                'user_id' => $currentUser->id,
                'entry_amount' => 100.00,
                'payment_method' => 'wallet_deduction',
                'transaction_id' => 'WALLET-' . time(),
                'status' => 'approved', // Auto-approve wallet entries
                'round_start_date' => $currentRoundStart,
                'round_end_date' => LuckyWithdrawEntry::getCurrentRoundEndDate(),
            ]);

            // Also update legacy UserAccount for backward compatibility
            \App\Models\UserAccount::where('user_id', $currentUser->id)->decrement('net_amount', 100);

            DB::commit();

            return redirect()->route('lucky-withdraw.index')
                ->with('success', 'Congratulations! You have successfully entered the Lucky Withdraw contest! Entry Code: ' . $entry->entry_code . '. Good luck!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Lucky Draw - Entry creation failed: " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred while processing your entry. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified entry
     */
    public function show(LuckyWithdrawEntry $luckyWithdrawEntry)
    {
        // Check if user owns this entry or is admin
        if ($luckyWithdrawEntry->user_id !== Auth::id() && !in_array(Auth::user()->role, ['ADMIN', 'SUPERADMIN'])) {
            abort(403);
        }

        // Determine if current user is admin
        $isAdmin = in_array(Auth::user()->role, ['ADMIN', 'SUPERADMIN']);

        return view('backend.user.lucky-withdraw.show', compact('luckyWithdrawEntry', 'isAdmin'));
    }

    /**
     * Admin index - Display all entries for admin
     */
    public function adminIndex(Request $request)
    {
        $query = LuckyWithdrawEntry::with('user');

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by round
        if ($request->has('round_start_date') && $request->round_start_date) {
            $query->where('round_start_date', $request->round_start_date);
        }

        // Search by user
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $entries = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get current round info
        $currentRoundStart = LuckyWithdrawEntry::getCurrentRoundStartDate();
        $currentRoundEnd = LuckyWithdrawEntry::getCurrentRoundEndDate();
        
        // Get round statistics
        $roundStats = [
            'total_entries' => LuckyWithdrawEntry::currentRound()->count(),
            'approved_entries' => LuckyWithdrawEntry::currentRound()->approved()->count(),
            'pending_entries' => LuckyWithdrawEntry::currentRound()->where('status', 'pending')->count(),
            'rejected_entries' => LuckyWithdrawEntry::currentRound()->where('status', 'rejected')->count(),
        ];

        return view('backend.admin.lucky-withdraw.index', compact(
            'entries',
            'currentRoundStart',
            'currentRoundEnd',
            'roundStats',
            'request'
        ));
    }

    /**
     * Display list of contestants for current round
     */
    public function contestants(Request $request)
    {
        // Get current round info
        $currentRoundStart = LuckyWithdrawEntry::getCurrentRoundStartDate();
        $currentRoundEnd = LuckyWithdrawEntry::getCurrentRoundEndDate();
        $isRoundActive = LuckyWithdrawEntry::isCurrentRoundActive();
        
        // Get all approved entries for current round
        $query = LuckyWithdrawEntry::with(['user', 'user.subscriptions.plan'])
            ->currentRound()
            ->approved()
            ->activeRound();

        // Search by user
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $contestants = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Check if winner already selected for this round
        $existingWinner = LuckyWithdrawEntry::currentRound()
            ->where('is_winner', true)
            ->with('user')
            ->first();
        
        return view('backend.admin.lucky-withdraw.contestants', compact(
            'contestants',
            'currentRoundStart',
            'currentRoundEnd',
            'isRoundActive',
            'existingWinner',
            'request'
        ));
    }

    /**
     * Approve an entry
     */
    public function approve(LuckyWithdrawEntry $luckyWithdrawEntry)
    {
        if ($luckyWithdrawEntry->status !== 'pending') {
            return back()->with('error', 'Only pending entries can be approved.');
        }

        $luckyWithdrawEntry->update(['status' => 'approved']);

        return back()->with('success', 'Entry approved successfully.');
    }

    /**
     * Reject an entry
     */
    public function reject(Request $request, LuckyWithdrawEntry $luckyWithdrawEntry)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        if ($luckyWithdrawEntry->status !== 'pending') {
            return back()->with('error', 'Only pending entries can be rejected.');
        }

        $luckyWithdrawEntry->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes
        ]);

        return back()->with('success', 'Entry rejected successfully.');
    }

    /**
     * Select winner for current round (Random)
     */
    public function selectWinner()
    {
        $currentRoundStart = LuckyWithdrawEntry::getCurrentRoundStartDate();
        
        // Get all approved entries for current round
        $eligibleEntries = LuckyWithdrawEntry::currentRound()
            ->approved()
            ->activeRound()
            ->get();

        if ($eligibleEntries->count() === 0) {
            return back()->with('error', 'No eligible entries found for winner selection.');
        }

        // Check if winner already selected for this round
        $existingWinner = LuckyWithdrawEntry::currentRound()
            ->where('is_winner', true)
            ->first();

        if ($existingWinner) {
            return back()->with('error', 'Winner has already been selected for this round.');
        }

        try {
            DB::beginTransaction();

            // Randomly select winner
            $winner = $eligibleEntries->random();
            
            // Process winner reward
            $result = $this->processWinnerReward($winner);

            // Mark all entries for this round as completed
            LuckyWithdrawEntry::currentRound()
                ->where('status', 'approved')
                ->update(['round_status' => 'completed']);

            DB::commit();

            return back()->with('success', $result['message']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Lucky Draw - Winner selection failed: " . $e->getMessage());
            return back()->with('error', 'An error occurred while selecting the winner. Please try again.');
        }
    }

    /**
     * Select specific user as winner
     */
    public function selectSpecificWinner(Request $request, LuckyWithdrawEntry $luckyWithdrawEntry)
    {
        // Check if entry is eligible
        if ($luckyWithdrawEntry->status !== 'approved') {
            return back()->with('error', 'Only approved entries can be selected as winner.');
        }

        if ($luckyWithdrawEntry->round_status !== 'active') {
            return back()->with('error', 'Only active entries can be selected as winner.');
        }

        // Check if entry is from current round
        $currentRoundStart = LuckyWithdrawEntry::getCurrentRoundStartDate();
        if ($luckyWithdrawEntry->round_start_date !== $currentRoundStart) {
            return back()->with('error', 'Only current round entries can be selected as winner.');
        }

        // Check if winner already selected for this round
        $existingWinner = LuckyWithdrawEntry::currentRound()
            ->where('is_winner', true)
            ->first();

        if ($existingWinner) {
            return back()->with('error', 'Winner has already been selected for this round.');
        }

        try {
            DB::beginTransaction();

            // Process winner reward
            $result = $this->processWinnerReward($luckyWithdrawEntry);

            // Mark all entries for this round as completed
            LuckyWithdrawEntry::currentRound()
                ->where('status', 'approved')
                ->update(['round_status' => 'completed']);

            DB::commit();

            return back()->with('success', $result['message']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Lucky Draw - Winner selection failed: " . $e->getMessage());
            return back()->with('error', 'An error occurred while selecting the winner. Please try again.');
        }
    }

    /**
     * Process winner reward based on conditions
     */
    private function processWinnerReward(LuckyWithdrawEntry $winner)
    {
        // Mark as winner
        $winner->update(['is_winner' => true]);
        
        // Get Plan 3
        $plan3 = InvestmentPlan::find(3);
        
        if (!$plan3) {
            throw new \Exception('Plan 3 not found in the system.');
        }

        $userId = $winner->user_id;
        $rewardType = '';
        $rewardMessage = '';

        // Check condition 1: Has user subscribed to plan 3 earlier or currently?
        $hasSubscribedPlan3 = UserSubscription::where('user_id', $userId)
            ->where('investment_plan_id', 3)
            ->exists();

        // Check condition 2: Does user have 2 active plans?
        $activePlansCount = UserSubscription::where('user_id', $userId)
            ->where('expire_at', '>=', now())
            ->whereNull('deleted_at')
            ->count();

        // Determine reward based on conditions
        if (!$hasSubscribedPlan3 && $activePlansCount < 2) {
            // User meets conditions: Subscribe to Plan 3
            $expiryDate = now()->addDays($plan3->plan_days);
            
            UserSubscription::create([
                'user_id' => $userId,
                'investment_plan_id' => $plan3->id,
                'expire_at' => $expiryDate,
                'earning' => 0,
                'watched_videos' => [],
                'daily_reward_claimed' => null,
            ]);

            $rewardType = 'Plan 3 Subscription';
            $rewardMessage = 'Winner: ' . $winner->user->name . ' (Entry Code: ' . $winner->entry_code . ') - Reward: Plan 3 Subscription';
            
            // Send notification
            \App\Models\UserNotification::create([
                'user_id' => $userId,
                'title' => 'Lucky Draw Winner!',
                'message' => 'Congratulations! You won the Lucky Draw! You have been subscribed to Plan 3 for ' . $plan3->plan_days . ' days.',
                'type' => 'success',
                'is_read' => false,
            ]);

        } else {
            // User doesn't meet conditions: Add Rs. 7000 to main wallet
            $mainWallet = \App\Models\UserWallet::getOrCreateWallet($userId, 'main');
            $mainWallet->addFunds(7000, 'earned');
            
            // Also update the legacy UserAccount for backward compatibility
            $userAccount = \App\Models\UserAccount::where('user_id', $userId)->first();
            if ($userAccount) {
                $userAccount->increment('net_amount', 7000);
            } else {
                \App\Models\UserAccount::create([
                    'user_id' => $userId,
                    'net_amount' => 7000,
                ]);
            }

            $reason = '';
            if ($hasSubscribedPlan3) {
                $reason = 'User has already subscribed to Plan 3. ';
            }
            if ($activePlansCount >= 2) {
                $reason .= 'User has reached the maximum limit of 2 active plans.';
            }

            $rewardType = 'Rs. 7000 Bonus';
            $rewardMessage = 'Winner: ' . $winner->user->name . ' (Entry Code: ' . $winner->entry_code . ') - Reward: Rs. 7000 added to Main Wallet. Reason: ' . $reason;
            
            // Send notification
            \App\Models\UserNotification::create([
                'user_id' => $userId,
                'title' => 'Lucky Draw Winner!',
                'message' => 'Congratulations! You won the Lucky Draw! Rs. 7000 has been added to your Main Wallet as bonus.',
                'type' => 'success',
                'is_read' => false,
            ]);
        }

        return [
            'reward_type' => $rewardType,
            'message' => 'Winner selected successfully! ' . $rewardMessage
        ];
    }

    /**
     * Get current round statistics (API endpoint)
     */
    public function getRoundStats()
    {
        $currentRoundStart = LuckyWithdrawEntry::getCurrentRoundStartDate();
        $currentRoundEnd = LuckyWithdrawEntry::getCurrentRoundEndDate();
        
        $stats = [
            'round_start' => $currentRoundStart,
            'round_end' => $currentRoundEnd,
            'total_entries' => LuckyWithdrawEntry::currentRound()->count(),
            'approved_entries' => LuckyWithdrawEntry::currentRound()->approved()->count(),
            'pending_entries' => LuckyWithdrawEntry::currentRound()->where('status', 'pending')->count(),
            'is_round_active' => LuckyWithdrawEntry::isCurrentRoundActive(),
            'days_remaining' => max(0, (strtotime($currentRoundEnd) - strtotime(now()->format('Y-m-d'))) / (60 * 60 * 24)),
        ];

        return response()->json($stats);
    }

    /**
     * End current round manually (Admin only)
     */
    public function endCurrentRound()
    {
        $currentRoundStart = LuckyWithdrawEntry::getCurrentRoundStartDate();
        
        // Get current round entries
        $currentRoundEntries = LuckyWithdrawEntry::currentRound()
            ->where('round_status', 'active')
            ->count();

        if ($currentRoundEntries === 0) {
            return back()->with('error', 'No active round found to end.');
        }

        // Check if winner has been selected
        $hasWinner = LuckyWithdrawEntry::currentRound()
            ->where('is_winner', true)
            ->exists();

        if (!$hasWinner) {
            return back()->with('error', 'Please select a winner before ending the round.');
        }

        try {
            DB::beginTransaction();

            // Mark all entries for current round as completed
            LuckyWithdrawEntry::currentRound()
                ->where('round_status', 'active')
                ->update([
                    'round_status' => 'completed',
                    'round_end_date' => now()->format('Y-m-d') // Update end date to today
                ]);

            DB::commit();

            Log::info("Lucky Draw - Round ended manually. Round start: {$currentRoundStart}, Entries affected: {$currentRoundEntries}");

            return back()->with('success', "Current round ended successfully! {$currentRoundEntries} entries marked as completed. You can now start a new round.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Lucky Draw - Failed to end round: " . $e->getMessage());
            return back()->with('error', 'An error occurred while ending the round. Please try again.');
        }
    }

    /**
     * Start new round manually (Admin only)
     */
    public function startNewRound()
    {
        // Check if there are any active entries in the current calculated round
        $currentRoundStart = LuckyWithdrawEntry::getCurrentRoundStartDate();
        $activeEntries = LuckyWithdrawEntry::where('round_start_date', $currentRoundStart)
            ->where('round_status', 'active')
            ->count();

        if ($activeEntries > 0) {
            return back()->with('error', 'Cannot start a new round. Current round still has active entries. Please end the current round first.');
        }

        try {
            DB::beginTransaction();

            // Calculate new round dates (start from today)
            $newRoundStart = now()->format('Y-m-d');
            $newRoundEnd = now()->addDays(14)->format('Y-m-d');

            // Send notification to all users about new round
            $users = User::where('role', 'USER')->get();
            foreach ($users as $user) {
                \App\Models\UserNotification::create([
                    'user_id' => $user->id,
                    'title' => 'New Lucky Draw Round Started!',
                    'message' => "A new Lucky Draw round has started! Enter now for a chance to win Plan 3 (Rs. 7,000). Round ends on " . date('M d, Y', strtotime($newRoundEnd)) . ".",
                    'type' => 'info',
                    'is_read' => false,
                    'is_important' => false,
                ]);
            }

            DB::commit();

            Log::info("Lucky Draw - New round started manually. Start: {$newRoundStart}, End: {$newRoundEnd}");

            return back()->with('success', "New Lucky Draw round started successfully! Round runs from " . date('M d, Y', strtotime($newRoundStart)) . " to " . date('M d, Y', strtotime($newRoundEnd)) . ". Users have been notified.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Lucky Draw - Failed to start new round: " . $e->getMessage());
            return back()->with('error', 'An error occurred while starting the new round. Please try again.');
        }
    }
}

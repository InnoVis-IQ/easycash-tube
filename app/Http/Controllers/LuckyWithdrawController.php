<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\LuckyWithdrawEntry;
use App\Models\LuckyWithdrawRound;
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
        
        // Get active round
        $activeRound = LuckyWithdrawRound::getActiveRound();
        
        if (!$activeRound) {
            return view('backend.user.lucky-withdraw.index', [
                'activeRound' => null,
                'userEntry' => null,
                'userEntries' => collect(),
                'recentWinners' => collect(),
                'mainWallet' => \App\Models\UserWallet::getOrCreateWallet($currentUser->id, 'main'),
                'earnWallet' => \App\Models\UserWallet::getOrCreateWallet($currentUser->id, 'earn'),
                'totalWalletBalance' => \App\Models\UserWallet::getTotalBalance($currentUser->id),
                'withdrawableBalance' => \App\Models\UserWallet::getWithdrawableBalance($currentUser->id),
                'currentRoundStart' => null,
                'currentRoundEnd' => null
            ]);
        }
        
        // Check if user already has an entry for current round
        $userEntry = LuckyWithdrawEntry::where('user_id', $currentUser->id)
            ->where('round_id', $activeRound->id)
            ->first();
        
        // Get user's entry history
        $userEntries = LuckyWithdrawEntry::where('user_id', $currentUser->id)
            ->with('round')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get recent winners
        $recentWinners = LuckyWithdrawRound::completed()
            ->with(['winner', 'winnerEntry'])
            ->whereNotNull('winner_id')
            ->orderBy('draw_date', 'desc')
            ->take(5)
            ->get();
        
        // Get user's wallet balances
        $mainWallet = \App\Models\UserWallet::getOrCreateWallet($currentUser->id, 'main');
        $earnWallet = \App\Models\UserWallet::getOrCreateWallet($currentUser->id, 'earn');
        $totalWalletBalance = \App\Models\UserWallet::getTotalBalance($currentUser->id);
        $withdrawableBalance = \App\Models\UserWallet::getWithdrawableBalance($currentUser->id);
        
        // For backward compatibility with views
        $currentRoundStart = $activeRound ? $activeRound->start_date->format('Y-m-d') : null;
        $currentRoundEnd = $activeRound ? $activeRound->end_date->format('Y-m-d') : null;
        
        return view('backend.user.lucky-withdraw.index', compact(
            'activeRound',
            'userEntry',
            'userEntries',
            'recentWinners',
            'mainWallet',
            'earnWallet',
            'totalWalletBalance',
            'withdrawableBalance',
            'currentRoundStart',
            'currentRoundEnd'
        ));
    }

    /**
     * Show the form for creating a new entry
     */
    public function create()
    {
        $currentUser = Auth::user();
        
        // Get active round
        $activeRound = LuckyWithdrawRound::getActiveRound();
        
        if (!$activeRound) {
            return redirect()->route('lucky-withdraw.index')
                ->with('error', 'No active round available. Please wait for the next round.');
        }
        
        if (!$activeRound->isInDateRange()) {
            return redirect()->route('lucky-withdraw.index')
                ->with('error', 'Current round is not active. Please wait for the next round.');
        }
        
        // Check if user already has an entry for current round
        $userEntry = LuckyWithdrawEntry::where('user_id', $currentUser->id)
            ->where('round_id', $activeRound->id)
            ->first();
        
        if ($userEntry) {
            return redirect()->route('lucky-withdraw.index')
                ->with('error', 'You already have an entry for the current round.');
        }
        
        // Get user's wallet balances
        $mainWallet = \App\Models\UserWallet::getOrCreateWallet($currentUser->id, 'main');
        $earnWallet = \App\Models\UserWallet::getOrCreateWallet($currentUser->id, 'earn');
        $totalWalletBalance = \App\Models\UserWallet::getTotalBalance($currentUser->id);
        $withdrawableBalance = \App\Models\UserWallet::getWithdrawableBalance($currentUser->id);
        
        // For backward compatibility with views
        $currentRoundStart = $activeRound->start_date->format('Y-m-d');
        $currentRoundEnd = $activeRound->end_date->format('Y-m-d');
        
        return view('backend.user.lucky-withdraw.create', compact(
            'activeRound',
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
        
        // Get active round
        $activeRound = LuckyWithdrawRound::getActiveRound();
        
        if (!$activeRound) {
            return redirect()->route('lucky-withdraw.index')
                ->with('error', 'No active round available. Please wait for the next round.');
        }
        
        if (!$activeRound->isInDateRange()) {
            return redirect()->route('lucky-withdraw.index')
                ->with('error', 'Current round is not active. Please wait for the next round.');
        }
        
        // Check if user already has an entry for current round
        $existingEntry = LuckyWithdrawEntry::where('user_id', $currentUser->id)
            ->where('round_id', $activeRound->id)
            ->first();
        
        if ($existingEntry) {
            return redirect()->route('lucky-withdraw.index')
                ->with('error', 'You already have an entry for the current round.');
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
                'round_id' => $activeRound->id,
                'entry_amount' => 100.00,
                'payment_method' => 'wallet_deduction',
                'transaction_id' => 'WALLET-' . time(),
                'status' => 'approved', // Auto-approve wallet entries
                'round_status' => 'active',
                'round_start_date' => $activeRound->start_date,
                'round_end_date' => $activeRound->end_date,
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
        $query = LuckyWithdrawEntry::with(['user', 'round']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by round
        if ($request->has('round_id') && $request->round_id) {
            $query->where('round_id', $request->round_id);
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

        // Get active round
        $activeRound = LuckyWithdrawRound::getActiveRound();
        
        // Get all rounds for filter dropdown
        $allRounds = LuckyWithdrawRound::orderBy('round_number', 'desc')->get();

        return view('backend.admin.lucky-withdraw.index', compact(
            'entries',
            'activeRound',
            'allRounds',
            'request'
        ));
    }

    /**
     * Display list of contestants for current round
     */
    public function contestants(Request $request)
    {
        // Get active round
        $activeRound = LuckyWithdrawRound::getActiveRound();
        
        if (!$activeRound) {
            return view('backend.admin.lucky-withdraw.contestants', [
                'activeRound' => null,
                'contestants' => collect(),
                'request' => $request
            ]);
        }
        
        // Get all approved entries for active round
        $query = $activeRound->approvedEntries()->with(['user', 'user.subscriptions.plan']);

        // Search by user
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $contestants = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('backend.admin.lucky-withdraw.contestants', compact(
            'activeRound',
            'contestants',
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
        $activeRound = LuckyWithdrawRound::getActiveRound();
        
        if (!$activeRound) {
            return back()->with('error', 'No active round found.');
        }

        // Check if winner already selected for this round
        if ($activeRound->winner_id) {
            return back()->with('error', 'Winner has already been selected for this round.');
        }

        $eligibleEntries = $activeRound->approvedEntries()->get();

        if ($eligibleEntries->count() === 0) {
            return back()->with('error', 'No eligible entries found for winner selection.');
        }

        try {
            DB::beginTransaction();

            // Select winner using the round model
            $winner = $activeRound->selectWinner();
            
            if (!$winner) {
                throw new \Exception('Failed to select winner');
            }
            
            // Process winner reward
            $result = $this->processWinnerReward($winner);

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

        // Get the round
        $round = $luckyWithdrawEntry->round;
        
        if (!$round) {
            return back()->with('error', 'Round not found for this entry.');
        }

        // Check if round is active
        if ($round->status !== 'active') {
            return back()->with('error', 'Only entries from active rounds can be selected as winner.');
        }

        // Check if winner already selected for this round
        if ($round->winner_id) {
            return back()->with('error', 'Winner has already been selected for this round.');
        }

        try {
            DB::beginTransaction();

            // Complete round with this winner
            $round->completeWithWinner($luckyWithdrawEntry->id);
            
            // Process winner reward
            $result = $this->processWinnerReward($luckyWithdrawEntry);

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
            $expiryDate = now()->addDays($plan3['plan_days']);
            
            UserSubscription::create([
                'user_id' => $userId,
                'investment_plan_id' => $plan3['id'],
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
                'message' => 'Congratulations! You won the Lucky Draw! You have been subscribed to Plan 3.',
                'type' => 'lucky_winner',
                'is_read' => false,
                'is_important' => true,
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
                'type' => 'lucky_winner',
                'is_read' => false,
                'is_important' => true,
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
        $activeRound = LuckyWithdrawRound::getActiveRound();
        
        if (!$activeRound) {
            return response()->json([
                'active_round' => false,
                'message' => 'No active round'
            ]);
        }
        
        $stats = [
            'active_round' => true,
            'round_number' => $activeRound->round_number,
            'round_start' => $activeRound->start_date->format('Y-m-d'),
            'round_end' => $activeRound->end_date->format('Y-m-d'),
            'total_entries' => $activeRound->total_entries,
            'total_amount' => $activeRound->total_amount,
            'is_in_date_range' => $activeRound->isInDateRange(),
            'days_remaining' => $activeRound->days_remaining,
        ];

        return response()->json($stats);
    }

    /**
     * End current round manually (Admin only)
     */
    public function endCurrentRound()
    {
        $activeRound = LuckyWithdrawRound::getActiveRound();
        
        if (!$activeRound) {
            return back()->with('error', 'No active round found.');
        }

        // Check if winner has been selected
        if (!$activeRound->winner_id) {
            return back()->with('error', 'Please select a winner before ending the round.');
        }

        try {
            DB::beginTransaction();

            // Update round status to completed (winner was already set during selection)
            $activeRound->update([
                'status' => 'completed',
            ]);

            // Mark all remaining entries as completed
            $activeRound->entries()
                ->where('round_status', 'active')
                ->update(['round_status' => 'completed']);

            DB::commit();

            Log::info("Lucky Draw - Round #{$activeRound->round_number} ended manually.");

            return back()->with('success', "Round #{$activeRound->round_number} ended successfully!");

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
        // Check if there's already an active round
        $existingActiveRound = LuckyWithdrawRound::getActiveRound();
        
        if ($existingActiveRound) {
            return back()->with('error', 'Cannot start a new round. An active round already exists. Please end it first.');
        }

        try {
            DB::beginTransaction();

            // Calculate new round dates (start from today)
            $newRoundStart = now()->format('Y-m-d');
            $newRoundEnd = now()->addDays(14)->format('Y-m-d');

            // Create new round
            $newRound = LuckyWithdrawRound::create([
                'start_date' => $newRoundStart,
                'end_date' => $newRoundEnd,
                'status' => 'active',
            ]);

            // Send notification to all users about new round
            $users = User::where('role', 'USER')->get();
            foreach ($users as $user) {
                \App\Models\UserNotification::create([
                    'user_id' => $user->id,
                    'title' => 'New Lucky Draw Round Started!',
                    'message' => "A new Lucky Draw round #{$newRound->round_number} has started! Enter now for a chance to win Plan 3 (Rs. 7,000). Round ends on " . date('M d, Y', strtotime($newRoundEnd)) . ".",
                    'type' => 'new_offer',
                    'is_read' => false,
                    'is_important' => false,
                ]);
            }

            DB::commit();

            Log::info("Lucky Draw - New round #{$newRound->round_number} started manually. Start: {$newRoundStart}, End: {$newRoundEnd}");

            return back()->with('success', "New Lucky Draw round #{$newRound->round_number} started successfully! Round runs from " . date('M d, Y', strtotime($newRoundStart)) . " to " . date('M d, Y', strtotime($newRoundEnd)) . ". Users have been notified.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Lucky Draw - Failed to start new round: " . $e->getMessage());
            return back()->with('error', 'An error occurred while starting the new round. Please try again.');
        }
    }
}

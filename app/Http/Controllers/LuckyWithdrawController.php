<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $bonusWallet = \App\Models\UserWallet::getOrCreateWallet($currentUser->id, 'bonus');
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
            'bonusWallet',
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
        $bonusWallet = \App\Models\UserWallet::getOrCreateWallet($currentUser->id, 'bonus');
        $totalWalletBalance = \App\Models\UserWallet::getTotalBalance($currentUser->id);
        $withdrawableBalance = \App\Models\UserWallet::getWithdrawableBalance($currentUser->id);
        
        return view('backend.user.lucky-withdraw.create', compact(
            'mainWallet',
            'earnWallet',
            'bonusWallet',
            'totalWalletBalance',
            'withdrawableBalance'
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
            $mainWallet->deductFunds(100, 'plan_purchase');
            
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

        return view('backend.user.lucky-withdraw.show', compact('luckyWithdrawEntry'));
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
     * Select winner for current round
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
            
            // Mark winner
            $winner->update(['is_winner' => true]);
            
            // Send notification to winner
            \App\Models\UserNotification::createLuckyWinnerNotification($winner->user_id, 'Plan 3', 7000);
            
            // Create free Plan 3 subscription for winner
            $plan3 = InvestmentPlan::where('title', 'Plan 3')->first();
            
            if ($plan3) {
                $expiryDate = date('Y-m-d', strtotime('+' . $plan3->plan_days . ' days'));
                
                UserSubscription::create([
                    'user_id' => $winner->user_id,
                    'investment_plan_id' => $plan3->id,
                    'expire_at' => $expiryDate,
                    'earning' => 0,
                    'watched_videos' => [],
                    'daily_reward_claimed' => null,
                ]);

                // Add Plan 3 amount to main wallet as bonus
                $mainWallet = \App\Models\UserWallet::getOrCreateWallet($winner->user_id, 'main');
                $mainWallet->addFunds($plan3->amount, 'earned');
                
                // Also update the legacy UserAccount for backward compatibility
                $userAccount = \App\Models\UserAccount::where('user_id', $winner->user_id)->first();
                if ($userAccount) {
                    $userAccount->increment('net_amount', $plan3->amount);
                } else {
                    \App\Models\UserAccount::create([
                        'user_id' => $winner->user_id,
                        'net_amount' => $plan3->amount,
                    ]);
                }
            }

            // Mark all entries for this round as completed
            LuckyWithdrawEntry::currentRound()
                ->where('status', 'approved')
                ->update(['round_status' => 'completed']);

            DB::commit();

            return back()->with('success', 'Winner selected successfully! Winner: ' . $winner->user->name . ' (Entry Code: ' . $winner->entry_code . ')');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while selecting the winner. Please try again.');
        }
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
}

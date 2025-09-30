<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\Withdraw;
use App\Models\UserSubscription;
use App\Models\UserAccount;
use App\Models\UserBankAccount;
use App\Models\BankAccount;
use App\Http\Requests\StoreWithdrawRequest;
use App\Http\Requests\UpdateWithdrawRequest;

class WithdrawController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userAccounts = UserAccount::withTrashed()->get();
        $accountBalance = [];
        foreach ($userAccounts as $userAccount) {
            $accountBalance[$userAccount['user_id']] = $userAccount['net_amount'];
        }
        
        if(in_array(Auth::user()['role'],['SUPERADMIN','ADMIN'])){
            $query = Withdraw::withTrashed()->with(['user', 'bank', 'userBankAccount']);

            // Filter by status
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            // Filter by date range
            if ($request->has('date_from') && $request->date_from) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->has('date_to') && $request->date_to) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Search by user
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            $withdrawals = $query->orderBy('created_at', 'desc')->paginate(20);

            return view('backend.admin.withdraw.index')->with([
                'data' => $withdrawals,
                'pending' => Withdraw::where('status','requested')->count(),
                'accountBalance' => $accountBalance,
                'banks' => BankAccount::get(),
                'status' => $request->status,
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
                'search' => $request->search
            ]);
        }
        else{
            // Get withdrawable balance from earn wallet only
            $withdrawableBalance = \App\Models\UserWallet::getWithdrawableBalance(Auth::user()['id']);
            $userWithdraws = Withdraw::where('user_id',Auth::user()['id'])->where('status','requested')->sum('amount');
            $netAmount = $withdrawableBalance - $userWithdraws;

            // Check for active plans with withdrawal restrictions
            $activeSubscriptions = UserSubscription::where('user_id', Auth::user()['id'])
                ->where('expire_at', '>', date('Y-m-d'))
                ->with('plan')
                ->get();

            $hasNoWithdrawalPlan = false;
            $restrictedPlanName = '';
            foreach ($activeSubscriptions as $subscription) {
                if ($subscription->plan && $subscription->plan->limit === 'no_withdrawal') {
                    $hasNoWithdrawalPlan = true;
                    $restrictedPlanName = $subscription->plan->title;
                    break;
                }
            }
            
            $isEligible = !$hasNoWithdrawalPlan;
            $eligibilityMessage = '';
            
            if ($hasNoWithdrawalPlan) {
                $eligibilityMessage = 'Withdrawal not allowed. You have an active ' . $restrictedPlanName . ' plan that does not allow withdrawals.';
            } elseif ($netAmount < 500) {
                $eligibilityMessage = 'Minimum withdrawal amount is Rs. 500. Your current balance is Rs. ' . $netAmount;
            }

            // Get user's withdrawal history
            $withdrawalHistory = Withdraw::where('user_id', Auth::user()['id'])
                ->with('userBankAccount')
                ->orderBy('created_at', 'desc')
                ->get();

            return view('backend.user.withdraw.index', compact(
                'netAmount',
                'userWithdraws',
                'isEligible',
                'withdrawalHistory',
                'eligibilityMessage',
                'previousPlans'
            ));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWithdrawRequest $request)
    {
        $message = '0';
        $userBankAccount = UserBankAccount::where('account_number',$request['acc_number'])->first();

        if(!$userBankAccount){
            $newAccount = new UserBankAccount;
            $newAccount['user_id'] = $request['userID'];
            $newAccount['account_title'] = $request['acc_title'];
            $newAccount['account_number'] = $request['acc_number'];
            // Store payment method in account_title for now (since we don't have a separate field)
            $paymentMethod = $request['payment_method'] ?? 'easypaisa';
            $newAccount['account_title'] = $request['acc_title'] . ' (' . ucfirst($paymentMethod) . ')';
            $newAccount->save();

            $userBankAccountID = $newAccount->id;
        }
        else{
            $userBankAccountID = $userBankAccount['id'];
        }

        // Get withdrawable balance from earn wallet only
        $withdrawableBalance = \App\Models\UserWallet::getWithdrawableBalance(Auth::user()['id']);
        $userWithdraws = Withdraw::where('user_id',Auth::user()['id'])->where('status','requested')->sum('amount');
        $netAmount = $withdrawableBalance - $userWithdraws;

        // Check if user has any active plans with withdrawal restrictions
        $activeSubscriptions = UserSubscription::where('user_id', Auth::user()['id'])
            ->where('expire_at', '>', date('Y-m-d'))
            ->with('plan')
            ->get();

        $hasNoWithdrawalPlan = false;
        foreach ($activeSubscriptions as $subscription) {
            if ($subscription->plan && $subscription->plan->limit === 'no_withdrawal') {
                $hasNoWithdrawalPlan = true;
                break;
            }
        }

        if ($hasNoWithdrawalPlan) {
            return back()->with(['message' => 'Withdrawal not allowed. You have an active plan that does not allow withdrawals.']);
        }

        // Validate minimum and maximum amounts
        $amount = $request['amount'];
        if ($amount < 500) {
            return back()->with(['message' => 'Minimum withdrawal amount is Rs. 500']);
        }
        if ($amount > 50000) {
            return back()->with(['message' => 'Maximum withdrawal amount is Rs. 50,000']);
        }

        if($netAmount >= $amount){
            $withdrawRequest = new Withdraw;
            $withdrawRequest['user_id'] = $request['userID'];
            $withdrawRequest['amount'] = $amount;
            $withdrawRequest['user_bank_account_id'] = $userBankAccountID;
            $withdrawRequest->save();
            
            $message = "Withdrawal request submitted successfully!";
        }
        else{
            $message = "Insufficient Balance";
        }

        return back()->with(['message'=>$message]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Withdraw $withdraw)
    {
        return view('backend.admin.withdraw.show', compact('withdraw'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Withdraw $withdraw)
    {
        $banks = BankAccount::get();
        return view('backend.admin.withdraw.edit', compact('withdraw', 'banks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWithdrawRequest $request, Withdraw $withdraw)
    {
        $withdraw['bank_account_id'] = $request['bank'];
        $withdraw['status'] = 'transferred';
        $withdraw->save();

        // Deduct from earn wallet
        $earnWallet = \App\Models\UserWallet::getWalletByType($withdraw['user_id'], 'earn');
        if ($earnWallet) {
            $earnWallet->deductFunds($withdraw['amount'], 'withdrawal');
        }
        
        // Also update the legacy UserAccount for backward compatibility
        UserAccount::where('user_id',$withdraw['user_id'])->decrement('net_amount', $withdraw['amount']);
        
        // Send notification to user
        \App\Models\UserNotification::createWithdrawalProcessedNotification($withdraw['user_id'], $withdraw['amount']);

        return back()->with('success', 'Withdrawal processed successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Withdraw $withdraw)
    {
        $withdraw->delete();
        return back()->with('success', 'Withdrawal deleted successfully');
    }

    /**
     * Bulk approve withdrawals
     */
    public function bulkApprove(Request $request)
    {
        $withdrawalIds = $request->withdrawal_ids;
        
        if (!$withdrawalIds) {
            return back()->with('error', 'No withdrawals selected');
        }

        $withdrawals = Withdraw::whereIn('id', $withdrawalIds)->where('status', 'requested')->get();
        
        foreach ($withdrawals as $withdrawal) {
            $withdrawal->status = 'transferred';
            $withdrawal->save();
            
            // Deduct from earn wallet
            $earnWallet = \App\Models\UserWallet::getWalletByType($withdrawal->user_id, 'earn');
            if ($earnWallet) {
                $earnWallet->deductFunds($withdrawal->amount, 'withdrawal');
            }
            
            // Also update the legacy UserAccount for backward compatibility
            UserAccount::where('user_id', $withdrawal->user_id)->decrement('net_amount', $withdrawal->amount);
            
            // Send notification to user
            \App\Models\UserNotification::createWithdrawalProcessedNotification($withdrawal->user_id, $withdrawal->amount);
        }

        return back()->with('success', count($withdrawals) . ' withdrawals approved successfully');
    }

    /**
     * Bulk reject withdrawals
     */
    public function bulkReject(Request $request)
    {
        $withdrawalIds = $request->withdrawal_ids;
        
        if (!$withdrawalIds) {
            return back()->with('error', 'No withdrawals selected');
        }

        $withdrawals = Withdraw::whereIn('id', $withdrawalIds)->where('status', 'requested')->get();
        
        foreach ($withdrawals as $withdrawal) {
            $withdrawal->status = 'rejected';
            $withdrawal->save();
        }

        return back()->with('success', count($withdrawals) . ' withdrawals rejected successfully');
    }

    /**
     * Export withdrawals to CSV
     */
    public function export(Request $request)
    {
        $query = Withdraw::withTrashed()->with(['user', 'user_bank']);

        // Apply filters
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $withdrawals = $query->orderBy('created_at', 'desc')->get();
        
        $filename = 'withdrawals_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($withdrawals) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'ID', 'User Name', 'User Email', 'Amount', 'Status', 
                'Bank Account', 'Request Date', 'Processed Date'
            ]);
            
            // Add data
            foreach ($withdrawals as $withdrawal) {
                fputcsv($file, [
                    $withdrawal->id,
                    $withdrawal->user ? $withdrawal->user->name : 'N/A',
                    $withdrawal->user ? $withdrawal->user->email : 'N/A',
                    $withdrawal->amount,
                    $withdrawal->status,
                    $withdrawal->user_bank ? $withdrawal->user_bank->account_number : 'N/A',
                    $withdrawal->created_at->format('Y-m-d H:i:s'),
                    $withdrawal->updated_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function withdrawToAccount(Request $request){
        // UserSubscription::where('id',$request['userSubsID'])->update(['earning'=>0]);
        // UserAccount::where('user_id',$request['userID'])->increment('net_amount', $request['amount']);

        // return back()->with(['message'=>'Amount transferred to Wallet']);
    }
}

<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\UserAccount;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\UserSubscription;
use App\Models\Withdraw;
use App\Models\Deposit;
use App\Models\Video;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::withTrashed()->with(['userAccount', 'subscriptions' => function($q) {
            $q->with('plan');
        }]);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('contact', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            if ($request->status === 'active') {
                $query->whereNull('deleted_at');
            } elseif ($request->status === 'inactive') {
                $query->whereNotNull('deleted_at');
            }
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        $referrals = [];
        $allUsers = User::withTrashed()->get();
        foreach ($allUsers as $user) {
            $referrals[$user['referral_code']] = $user['name'];
        }

        return view('backend.admin.users.index')->with([
            'data' => $users,
            'referrals' => $referrals,
            'search' => $request->search,
            'role' => $request->role,
            'status' => $request->status
        ]);
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
    public function store(StoreUserRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Get user's complete activity data
        $subscriptions = UserSubscription::with(['plan'])->where('user_id', $user->id)->get();
        $withdrawals = Withdraw::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $deposits = Deposit::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        
        // Calculate user statistics
        $totalEarnings = $subscriptions->sum('earning');
        $totalInvested = $subscriptions->sum(function($sub) {
            return $sub->plan ? $sub->plan->amount : 0;
        });
        $activeSubscriptions = $subscriptions->where('expire_at', '>', date('Y-m-d'))->count();
        $totalWithdrawals = $withdrawals->sum('amount');
        $pendingWithdrawals = $withdrawals->where('status', 'requested')->sum('amount');
        
        // Get videos watched by user
        $watchedVideos = collect();
        foreach ($subscriptions as $subscription) {
            if ($subscription->watched_videos) {
                $videoIds = is_array($subscription->watched_videos) ? $subscription->watched_videos : json_decode($subscription->watched_videos, true);
                if ($videoIds) {
                    $videos = Video::whereIn('id', $videoIds)->get();
                    $watchedVideos = $watchedVideos->merge($videos);
                }
            }
        }

        return view('backend.admin.users.show')->with([
            'data' => $user,
            'referrals' => User::withTrashed()->get(),
            'subscriptions' => $subscriptions,
            'withdrawals' => $withdrawals,
            'deposits' => $deposits,
            'watchedVideos' => $watchedVideos,
            'totalEarnings' => $totalEarnings,
            'totalInvested' => $totalInvested,
            'activeSubscriptions' => $activeSubscriptions,
            'totalWithdrawals' => $totalWithdrawals,
            'pendingWithdrawals' => $pendingWithdrawals
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        if($request['role'] == 'approve'){
            $user['role'] = 'USER';
            $user->save();

            $userAccount = new UserAccount;
            $userAccount['user_id'] = $user['id'];
            $userAccount['net_amount'] = 0;
            $userAccount->save();

            return back();
        }
        
        // Handle other updates
        if ($request->has('status')) {
            if ($request->status === 'activate') {
                $user->restore();
            } elseif ($request->status === 'deactivate') {
                $user->delete();
            }
        }
        
        if ($request->has('role')) {
            $user->role = $request->role;
        }
        
        $user->save();
        
        return back()->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User deactivated successfully');
    }

    /**
     * Export users to CSV
     */
    public function export(Request $request)
    {
        $users = User::withTrashed()->with(['userAccount', 'subscriptions'])->get();
        
        $filename = 'users_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'ID', 'Name', 'Email', 'Phone', 'Role', 'Status', 
                'Signup Date', 'Wallet Balance', 'Active Plans', 
                'Total Earnings', 'Total Withdrawals'
            ]);
            
            // Add data
            foreach ($users as $user) {
                $activePlans = $user->subscriptions->where('expire_at', '>', date('Y-m-d'))->count();
                $totalEarnings = $user->subscriptions->sum('earning');
                $totalWithdrawals = Withdraw::where('user_id', $user->id)->sum('amount');
                
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->contact,
                    $user->role,
                    $user->deleted_at ? 'Inactive' : 'Active',
                    $user->created_at->format('Y-m-d H:i:s'),
                    $user->userAccount ? $user->userAccount->net_amount : 0,
                    $activePlans,
                    $totalEarnings,
                    $totalWithdrawals
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}

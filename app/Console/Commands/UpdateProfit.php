<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\UserSubscription;
use App\Models\UserAccount;

class UpdateProfit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'profit:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update users profit based on their investment plan(s)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $oneDayAgo = date('Y-m-d H:i:s',strtotime('-1 day',strtotime(date('Y-m-d H:i:s'))));
        // $userSubscriptions = UserSubscription::where('expire_at','>=',date('Y-m-d'))->where('created_at','<',$oneDayAgo)->get();
        $userSubscriptions = UserSubscription::where('expire_at','>=',date('Y-m-d'))->get();

        foreach($userSubscriptions as $userSubscription){
            // $userSubscription['earning'] += $userSubscription['plan']['daily_profit'];
            // $userSubscription->save();

            UserSubscription::where('id',$userSubscription['id'])->increment('earning', $userSubscription['plan']['daily_profit']);
            UserAccount::where('user_id',$userSubscription['user_id'])->increment('net_amount', $userSubscription['plan']['daily_profit']);
        }

    }

}

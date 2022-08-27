<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Mail;
use App\Mail\UnVerifiedUser;

class ActiveUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activeuser:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Active users but not verified, reminder for verification';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
       $activeUsers= User::where(array('is_active'=>1))->whereNull('email_verified_at')->get();
       
        foreach($activeUsers as $key=>$user){
            Mail::to($user['email'])->send(new UnVerifiedUser($user));
        }
        return 0;
    }
}
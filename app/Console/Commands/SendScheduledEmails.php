<?php

namespace App\Console\Commands;

use App\Mail\TrialExpiryReminder;
use App\Mail\TrialLastDay;
use App\Mail\TrialExpired;
use App\Mail\RenewalReminder;
use App\Mail\SubscriptionExpired;
use App\Models\User;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendScheduledEmails extends Command
{
    protected $signature = 'emails:send-scheduled';
    protected $description = 'Send automated trial/subscription reminder emails';

    public function handle()
    {
        $this->info('Sending scheduled emails...');

        // 1. Trial J-3 reminder
        $trialJ3 = User::whereDate('trial_ends_at', now()->addDays(3)->toDateString())
            ->whereDoesntHave('subscriptions', fn($q) => $q->where('status', 'active')->where('ends_at', '>', now()))
            ->get();

        foreach ($trialJ3 as $user) {
            Mail::to($user->email)->queue(new TrialExpiryReminder($user, 3));
        }
        $this->info("→ Trial J-3: {$trialJ3->count()} emails");

        // 2. Trial J-1 reminder
        $trialJ1 = User::whereDate('trial_ends_at', now()->addDay()->toDateString())
            ->whereDoesntHave('subscriptions', fn($q) => $q->where('status', 'active')->where('ends_at', '>', now()))
            ->get();

        foreach ($trialJ1 as $user) {
            Mail::to($user->email)->queue(new TrialLastDay($user));
        }
        $this->info("→ Trial J-1: {$trialJ1->count()} emails");

        // 3. Trial expired (Day 0)
        $trialExpired = User::whereDate('trial_ends_at', now()->toDateString())
            ->whereDoesntHave('subscriptions', fn($q) => $q->where('status', 'active')->where('ends_at', '>', now()))
            ->get();

        foreach ($trialExpired as $user) {
            Mail::to($user->email)->queue(new TrialExpired($user));
        }
        $this->info("→ Trial Expired: {$trialExpired->count()} emails");

        // 4. Subscription renewal J-5
        $renewalJ5 = Subscription::where('status', 'active')
            ->whereDate('ends_at', now()->addDays(5)->toDateString())
            ->with('user')
            ->get();

        foreach ($renewalJ5 as $sub) {
            Mail::to($sub->user->email)->queue(new RenewalReminder($sub->user, 5));
        }
        $this->info("→ Renewal J-5: {$renewalJ5->count()} emails");

        // 5. Subscription expired
        $subExpired = Subscription::where('status', 'active')
            ->whereDate('ends_at', now()->subDay()->toDateString())
            ->with('user')
            ->get();

        foreach ($subExpired as $sub) {
            $sub->update(['status' => 'expired']);
            Mail::to($sub->user->email)->queue(new SubscriptionExpired($sub->user));
        }
        $this->info("→ Subscription Expired: {$subExpired->count()} emails");

        $this->info('✅ Scheduled emails sent!');
        return Command::SUCCESS;
    }
}

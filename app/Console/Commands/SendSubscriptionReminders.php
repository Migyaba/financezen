<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class SendSubscriptionReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'financezen:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoie les emails de rappel pour la fin de période d\'essai ou d\'abonnement.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Début de l\'envoi des rappels...');

        // 1. Rappel J-3 (Essai)
        $trialUsers3Days = User::where('role', 'user')
            ->whereDate('trial_ends_at', Carbon::now()->addDays(3)->toDateString())
            ->whereDoesntHave('subscriptions', function ($q) {
                $q->where('status', 'active');
            })->get();

        foreach ($trialUsers3Days as $user) {
            // Mail::to($user)->send(new TrialEndingRefactoredMail($user, 3));
            $this->line("Rappel J-3 Essai envoyé à: {$user->email}");
        }

        // 2. Rappel J-1 (Essai)
        $trialUsers1Day = User::where('role', 'user')
            ->whereDate('trial_ends_at', Carbon::now()->addDays(1)->toDateString())
            ->whereDoesntHave('subscriptions', function ($q) {
                $q->where('status', 'active');
            })->get();

        foreach ($trialUsers1Day as $user) {
            // Mail::to($user)->send(new TrialEndingRefactoredMail($user, 1));
            $this->line("Rappel J-1 Essai envoyé à: {$user->email}");
        }

        // 3. Rappel J-5 (Abonnement Expiration)
        $subscribingUsers = User::whereHas('subscriptions', function ($q) {
            $q->where('status', 'active')
              ->whereDate('ends_at', Carbon::now()->addDays(5)->toDateString());
        })->get();

        foreach ($subscribingUsers as $user) {
            // Mail::to($user)->send(new SubscriptionExpiringMail($user, 5));
            $this->line("Rappel J-5 Abonnement envoyé à: {$user->email}");
        }

        $this->info('Terminé ! Rappels envoyés.');
    }
}

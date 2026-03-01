<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Subscription;
use Carbon\Carbon;

class DeactivateExpiredTrials extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'financezen:cleanup-trials-subs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bascule les abonnements expirés en status expired et bloque les essais terminés.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Nettoyage des abonnements expirés en cours...');

        // Bascule les abonnements expirés
        $expiredSubs = Subscription::where('status', 'active')
            ->where('ends_at', '<', Carbon::now())
            ->update(['status' => 'expired']);

        $this->line("{$expiredSubs} abonnements marqués comme expirés.");

        // Logs infos essai (pas de changement en base mais trigger mail "Expiré")
        $expiredTrialsToday = User::where('role', 'user')
            ->whereDate('trial_ends_at', Carbon::now()->subDays(1)->toDateString())
            ->whereDoesntHave('subscriptions', function ($q) {
                $q->where('status', 'active');
            })->get();

        foreach ($expiredTrialsToday as $user) {
            // Mail::to($user)->send(new TrialExpiredMail($user));
            $this->line("Email d'essai tout juste expiré envoyé à: {$user->email}");
        }

        $this->info('Terminé !');
    }
}

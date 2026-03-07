<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Send automated emails daily at 8:00 AM
Schedule::command('emails:send-scheduled')->dailyAt('08:00');

// Nettoyer les abonnements expirés (basculer en statut "expiré")
Schedule::command('financezen:cleanup-trials-subs')->dailyAt('00:05');

// Envoyer les emails de rappels pour les essais et abonnements (J-3, J-1, etc.)
Schedule::command('financezen:send-reminders')->dailyAt('08:15');
Schedule::command('app:process-recurring-transactions')->dailyAt('00:01');

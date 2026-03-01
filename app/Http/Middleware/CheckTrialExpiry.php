<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTrialExpiry
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        // Check trial expiry and flash warning
        if ($user->trial_ends_at) {
            $daysLeft = now()->diffInDays($user->trial_ends_at, false);

            if ($daysLeft <= 0) {
                // Trial expired - check if has active subscription
                $hasActiveSub = $user->subscriptions()
                    ->where('status', 'active')
                    ->where('ends_at', '>', now())
                    ->exists();

                if (!$hasActiveSub) {
                    session()->flash('warning', 'Votre période d\'essai a expiré. Souscrivez pour continuer à utiliser FinanceZen.');
                }
            } elseif ($daysLeft <= 3) {
                session()->flash('info', "⏰ Votre essai gratuit expire dans {$daysLeft} jour(s). Pensez à souscrire !");
            }
        }

        return $next($request);
    }
}

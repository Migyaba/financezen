<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user) {
            return $next($request);
        }

        // Admin are always allowed
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Skip check for these routes
        if ($request->is('subscription*') || $request->is('logout') || $request->is('profile*')) {
            return $next($request);
        }

        // Check if trial is active
        if ($user->trial_ends_at && $user->trial_ends_at->isFuture()) {
            return $next($request);
        }

        // Check if has active subscription
        $activeSubscription = $user->subscriptions()
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->exists();

        if ($activeSubscription) {
            return $next($request);
        }

        return redirect()->route('subscription.index')->with('warning', 'Votre accès a expiré. Veuillez souscrire à un abonnement pour continuer.');
    }
}

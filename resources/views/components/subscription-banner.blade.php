@props(['daysLeft' => null, 'expired' => false])

@if($expired)
<div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/30 rounded-2xl p-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-red-500 flex items-center justify-center text-white"><i data-lucide="alert-circle" class="w-5 h-5"></i></div>
        <div>
            <p class="font-bold text-red-700 dark:text-red-400 text-sm">Votre abonnement a expiré</p>
            <p class="text-xs text-red-500 dark:text-red-400/70">Renouvelez pour retrouver l'accès complet.</p>
        </div>
    </div>
    <a href="{{ route('subscription.index') }}" class="px-4 py-2 bg-red-500 text-white font-bold rounded-xl text-xs hover:bg-red-600 transition whitespace-nowrap">Renouveler — 1 000 FCFA/mois</a>
</div>
@elseif($daysLeft !== null && $daysLeft <= 5)
<div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/30 rounded-2xl p-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-amber-500 flex items-center justify-center text-white"><i data-lucide="clock" class="w-5 h-5"></i></div>
        <div>
            <p class="font-bold text-amber-700 dark:text-amber-400 text-sm">⚠️ Votre essai gratuit expire dans {{ $daysLeft }} jour{{ $daysLeft > 1 ? 's' : '' }}</p>
            <p class="text-xs text-amber-500 dark:text-amber-400/70">Souscrivez maintenant pour continuer sans interruption.</p>
        </div>
    </div>
    <a href="{{ route('subscription.index') }}" class="px-4 py-2 bg-primary text-white font-bold rounded-xl text-xs hover:bg-primary-dark transition whitespace-nowrap">Souscrire maintenant</a>
</div>
@endif

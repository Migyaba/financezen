<x-app-layout>
    <x-slot name="header">Abonnement</x-slot>

    <div class="space-y-6">
        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/30 text-emerald-700 dark:text-emerald-400 font-bold text-sm flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/30 text-amber-700 dark:text-amber-400 font-bold text-sm flex items-center gap-3">
                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                {{ session('warning') }}
            </div>
        @endif

        <!-- Current Status -->
        @if($trialDaysLeft !== null)
        <div class="bg-gradient-to-br from-primary/10 to-primary/5 dark:from-primary/20 dark:to-primary/5 rounded-3xl p-8 border-2 border-primary/20 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-40 h-40 bg-primary/10 rounded-full blur-3xl"></div>
            <div class="relative text-center space-y-4">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary/10 text-primary rounded-full text-xs font-bold tracking-widest uppercase">🎉 Période d'essai gratuite</div>
                <h2 class="text-3xl font-black text-slate-800 dark:text-white">
                    Il vous reste <span class="text-primary">{{ $trialDaysLeft }} jour{{ $trialDaysLeft > 1 ? 's' : '' }}</span> d'essai
                </h2>
                <p class="text-slate-500 max-w-md mx-auto">Profitez de toutes les fonctionnalités premium pendant votre période d'essai gratuite.</p>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 max-w-xl mx-auto">
                    <div class="flex items-center gap-2 text-sm font-medium text-slate-700 dark:text-slate-300"><i data-lucide="check" class="text-primary w-4 h-4"></i> Budgets illimités</div>
                    <div class="flex items-center gap-2 text-sm font-medium text-slate-700 dark:text-slate-300"><i data-lucide="check" class="text-primary w-4 h-4"></i> Suivi des dettes</div>
                    <div class="flex items-center gap-2 text-sm font-medium text-slate-700 dark:text-slate-300"><i data-lucide="check" class="text-primary w-4 h-4"></i> Objectifs d'épargne</div>
                    <div class="flex items-center gap-2 text-sm font-medium text-slate-700 dark:text-slate-300"><i data-lucide="check" class="text-primary w-4 h-4"></i> Rapports avancés</div>
                </div>
            </div>
        </div>
        @elseif($activeSubscription)
        <div class="bg-gradient-to-br from-emerald-500/10 to-emerald-500/5 dark:from-emerald-500/20 dark:to-emerald-500/5 rounded-3xl p-8 border-2 border-emerald-200/50 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-full text-xs font-bold tracking-widest uppercase mb-4">✅ Abonnement actif</div>
            <h2 class="text-2xl font-black text-slate-800 dark:text-white mb-2">Votre abonnement est actif</h2>
            <p class="text-slate-500">Expire le {{ $activeSubscription->ends_at->format('d/m/Y') }}</p>
        </div>
        @else
        <div class="bg-gradient-to-br from-red-500/10 to-red-500/5 dark:from-red-500/20 dark:to-red-500/5 rounded-3xl p-8 border-2 border-red-200/50 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-red-500/10 text-red-600 dark:text-red-400 rounded-full text-xs font-bold tracking-widest uppercase mb-4">❌ Accès expiré</div>
            <h2 class="text-2xl font-black text-slate-800 dark:text-white mb-2">Votre accès a expiré</h2>
            <p class="text-slate-500 mb-6">Renouvelez votre abonnement pour retrouver l'accès complet.</p>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            <!-- Monthly Pricing Card -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 p-8 text-center relative overflow-hidden transition hover:shadow-xl hover:border-primary/30">
                <h3 class="text-xl font-bold text-slate-800 dark:text-white mt-2 mb-2">Mensuel</h3>
                <p class="text-sm text-slate-500 mb-6">Flexibilité totale, sans engagement.</p>
                <div class="flex items-baseline justify-center gap-1 mb-8">
                    <span class="text-4xl font-black text-slate-800 dark:text-white">100</span>
                    <span class="text-slate-500 font-bold tracking-wider">FCFA/mois</span>
                </div>
                <form action="{{ route('subscription.checkout') }}" method="POST">
                    @csrf
                    <input type="hidden" name="plan" value="monthly">
                    <button type="submit" class="w-full py-4 bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-white font-bold rounded-2xl hover:bg-slate-200 dark:hover:bg-slate-600 transition text-lg">
                        Choisir ce forfait
                    </button>
                </form>
            </div>

            <!-- Yearly Pricing Card -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl border-2 border-primary/50 relative overflow-hidden p-8 text-center transition hover:shadow-2xl">
                <div class="absolute top-0 inset-x-0 bg-primary text-white py-1.5 text-[10px] font-black tracking-widest uppercase">✨ Notre recommandation - 2 Mois Gratuits ✨</div>
                <h3 class="text-xl font-bold text-slate-800 dark:text-white mt-6 mb-2">Annuel</h3>
                <p class="text-sm text-slate-500 mb-6">Économisez pour atteindre vos objectifs.</p>
                <div class="flex flex-col items-center mb-8">
                    <div class="flex items-baseline justify-center gap-1">
                        <span class="text-4xl font-black text-primary">10 000</span>
                        <span class="text-slate-500 font-bold tracking-wider">FCFA/an</span>
                    </div>
                    <span class="text-xs text-slate-400 line-through mt-1">au lieu de 12 000 FCFA</span>
                </div>
                <form action="{{ route('subscription.checkout') }}" method="POST">
                    @csrf
                    <input type="hidden" name="plan" value="yearly">
                    <button type="submit" class="w-full py-4 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/20 hover:bg-primary-dark transition text-lg">
                        Souscrire à l'Annuel
                    </button>
                </form>
            </div>
        </div>

        <!-- Payment History -->
        @if($payments->isNotEmpty())
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700">
                <h3 class="font-bold text-slate-800 dark:text-white">Historique des paiements</h3>
            </div>
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-700">
                        <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-widest text-left">Date</th>
                        <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-widest text-left">Montant</th>
                        <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-widest text-left">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach($payments as $p)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30">
                        <td class="px-6 py-4 text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $p->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm font-black text-slate-800 dark:text-white">{{ number_format($p->amount, 0, ',', ' ') }} {{ $p->currency }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider
                                {{ $p->status == 'success' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : '' }}
                                {{ $p->status == 'pending' ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400' : '' }}
                                {{ $p->status == 'failed' ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' : '' }}
                            ">{{ $p->status }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</x-app-layout>

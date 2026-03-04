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
        @if($targetDate)
        <div class="{{ $activeSubscription ? 'bg-gradient-to-br from-emerald-500/10 to-emerald-500/5 dark:from-emerald-500/20 dark:to-emerald-500/5 border-emerald-200/50' : 'bg-gradient-to-br from-primary/10 to-primary/5 dark:from-primary/20 dark:to-primary/5 border-primary/20' }} rounded-3xl p-8 border-2 relative overflow-hidden">
            @if(!$activeSubscription)
            <div class="absolute top-0 right-0 w-40 h-40 bg-primary/10 rounded-full blur-3xl"></div>
            @endif
            <div class="relative text-center space-y-6">
                <div class="inline-flex items-center gap-2 px-4 py-2 {{ $activeSubscription ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-primary/10 text-primary' }} rounded-full text-xs font-bold tracking-widest uppercase shadow-sm">
                    {{ $activeSubscription ? '✨ Abonnement Actif' : '🎉 Période d\'essai gratuite' }}
                </div>
                
                <h2 class="text-2xl md:text-3xl font-black text-slate-800 dark:text-white">
                    Temps restant
                </h2>

                <!-- Countdown -->
                <div x-data="countdownTimer('{{ $targetDate->toIso8601String() }}')" class="flex items-center justify-center gap-2 md:gap-5 mt-4 group">
                    <div class="flex flex-col items-center bg-white dark:bg-slate-800 shadow-sm group-hover:shadow-md border border-slate-100 dark:border-slate-700 w-16 h-20 md:w-24 md:h-28 justify-center rounded-2xl transition">
                        <span x-text="days" class="text-xl md:text-5xl font-black {{ $activeSubscription ? 'text-emerald-500' : 'text-primary' }}">00</span>
                        <span class="text-[8px] md:text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Jours</span>
                    </div>
                    <div class="text-lg md:text-4xl font-black text-slate-300 dark:text-slate-600 animate-pulse">:</div>
                    <div class="flex flex-col items-center bg-white dark:bg-slate-800 shadow-sm group-hover:shadow-md border border-slate-100 dark:border-slate-700 w-16 h-20 md:w-24 md:h-28 justify-center rounded-2xl transition">
                        <span x-text="hours" class="text-xl md:text-5xl font-black {{ $activeSubscription ? 'text-emerald-500' : 'text-primary' }}">00</span>
                        <span class="text-[8px] md:text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Heures</span>
                    </div>
                    <div class="text-lg md:text-4xl font-black text-slate-300 dark:text-slate-600 animate-pulse">:</div>
                    <div class="flex flex-col items-center bg-white dark:bg-slate-800 shadow-sm group-hover:shadow-md border border-slate-100 dark:border-slate-700 w-16 h-20 md:w-24 md:h-28 justify-center rounded-2xl transition">
                        <span x-text="minutes" class="text-xl md:text-5xl font-black {{ $activeSubscription ? 'text-emerald-500' : 'text-primary' }}">00</span>
                        <span class="text-[8px] md:text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Min</span>
                    </div>
                    <div class="text-lg md:text-4xl font-black text-slate-300 dark:text-slate-600 animate-pulse">:</div>
                    <div class="flex flex-col items-center bg-white dark:bg-slate-800 shadow-sm group-hover:shadow-md border border-slate-100 dark:border-slate-700 w-16 h-20 md:w-24 md:h-28 justify-center rounded-2xl transition">
                        <span x-text="seconds" class="text-xl md:text-5xl font-black text-slate-600 dark:text-slate-300">00</span>
                        <span class="text-[8px] md:text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Sec</span>
                    </div>
                </div>

                <p class="text-slate-500 max-w-lg mx-auto text-sm mt-4">
                    {{ $activeSubscription ? 'Profitez de votre abonnement sans limite jusqu\'à son expiration.' : 'Profitez de toutes les fonctionnalités premium pendant votre période d\'essai gratuite.' }}
                </p>
                
                @if(!$activeSubscription)
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 max-w-xl mx-auto">
                    <div class="flex items-center gap-2 text-sm font-medium text-slate-700 dark:text-slate-300"><i data-lucide="check" class="text-primary w-4 h-4"></i> Budgets illimités</div>
                    <div class="flex items-center gap-2 text-sm font-medium text-slate-700 dark:text-slate-300"><i data-lucide="check" class="text-primary w-4 h-4"></i> Suivi des dettes</div>
                    <div class="flex items-center gap-2 text-sm font-medium text-slate-700 dark:text-slate-300"><i data-lucide="check" class="text-primary w-4 h-4"></i> Objectifs d'épargne</div>
                    <div class="flex items-center gap-2 text-sm font-medium text-slate-700 dark:text-slate-300"><i data-lucide="check" class="text-primary w-4 h-4"></i> Rapports avancés</div>
                </div>
                @endif
            </div>
        </div>
        @else
        <div class="bg-gradient-to-br from-red-500/10 to-red-500/5 dark:from-red-500/20 dark:to-red-500/5 rounded-3xl p-8 border-2 border-red-200/50 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-red-500/10 text-red-600 dark:text-red-400 rounded-full text-xs font-bold tracking-widest uppercase mb-4">❌ Accès expiré</div>
            <h2 class="text-2xl font-black text-slate-800 dark:text-white mb-2">Votre accès a expiré</h2>
            <p class="text-slate-500 mb-6">Renouvelez votre abonnement pour retrouver l'accès complet aux fonctionnalités.</p>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            <!-- Monthly Pricing Card -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 p-8 text-center relative overflow-hidden transition hover:shadow-xl hover:border-primary/30">
                <h3 class="text-xl font-bold text-slate-800 dark:text-white mt-2 mb-2">Mensuel</h3>
                <p class="text-sm text-slate-500 mb-6">Flexibilité totale, sans engagement.</p>
                <div class="flex items-baseline justify-center gap-1 mb-8">
                    <span class="text-4xl font-black text-slate-800 dark:text-white">1 000</span>
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

            <!-- Desktop Table -->
            <div class="hidden md:block">
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
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition">
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

            <!-- Mobile Cards -->
            <div class="md:hidden divide-y divide-slate-100 dark:divide-slate-700">
                @foreach($payments as $p)
                <div class="p-5 flex items-center justify-between group">
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">{{ $p->created_at->format('d/m/Y') }}</p>
                        <span class="px-1.5 py-0.5 rounded text-[8px] font-bold uppercase tracking-tighter
                            {{ $p->status == 'success' ? 'bg-emerald-100 text-emerald-700' : '' }}
                            {{ $p->status == 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                            {{ $p->status == 'failed' ? 'bg-red-100 text-red-700' : '' }}
                        ">{{ $p->status }}</span>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-black text-slate-800 dark:text-white">{{ number_format($p->amount, 0, ',', ' ') }}</p>
                        <p class="text-[9px] text-slate-400 font-bold uppercase">{{ $p->currency }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('countdownTimer', (endDateString) => ({
                target: new Date(endDateString).getTime(),
                days: '00',
                hours: '00',
                minutes: '00',
                seconds: '00',
                init() {
                    if (!endDateString) return;
                    
                    const updateCountdown = () => {
                        const now = new Date().getTime();
                        const distance = this.target - now;

                        if (distance < 0) {
                            this.days = '00';
                            this.hours = '00';
                            this.minutes = '00';
                            this.seconds = '00';
                            return;
                        }

                        this.days = String(Math.floor(distance / (1000 * 60 * 60 * 24))).padStart(2, '0');
                        this.hours = String(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                        this.minutes = String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                        this.seconds = String(Math.floor((distance % (1000 * 60)) / 1000)).padStart(2, '0');
                    };

                    updateCountdown();
                    setInterval(updateCountdown, 1000);
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>

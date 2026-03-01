<x-admin-layout>
    <x-slot name="header">Rapports & Analyses</x-slot>

    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Revenus par mois -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700">
                    <h3 class="font-bold text-slate-800 dark:text-white">Revenus par mois (Exportable)</h3>
                </div>
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-700">
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase text-left">Mois</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase text-right">Revenus</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($revenueData as $rd)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30">
                            <td class="px-6 py-3 text-sm font-bold text-slate-800 dark:text-white">{{ \Carbon\Carbon::createFromFormat('Y-m', $rd->month)->translatedFormat('F Y') }}</td>
                            <td class="px-6 py-3 text-sm font-black text-success text-right">{{ number_format($rd->total, 0, ',', ' ') }} FCFA</td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="px-6 py-8 text-center text-sm text-slate-400">Aucune donnée de revenu.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="space-y-6">
                <!-- Utilisateurs Actifs / Inactifs -->
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                    <h3 class="font-bold text-slate-800 dark:text-white mb-6">Utilisateurs</h3>
                    <div class="flex items-center gap-6">
                        <div class="flex-1 text-center p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border border-emerald-100 dark:border-emerald-800/30">
                            <i data-lucide="user-check" class="w-8 h-8 text-emerald-500 mx-auto mb-2"></i>
                            <p class="text-sm font-bold text-slate-600 dark:text-slate-400 uppercase tracking-widest">Actifs</p>
                            <p class="text-2xl font-black text-emerald-600 mt-1">{{ $userStats['active'] }}</p>
                        </div>
                        <div class="flex-1 text-center p-4 bg-slate-50 dark:bg-slate-900/20 rounded-xl border border-slate-100 dark:border-slate-700">
                            <i data-lucide="user-minus" class="w-8 h-8 text-slate-400 mx-auto mb-2"></i>
                            <p class="text-sm font-bold text-slate-600 dark:text-slate-400 uppercase tracking-widest">Inactifs</p>
                            <p class="text-2xl font-black text-slate-800 dark:text-white mt-1">{{ $userStats['inactive'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

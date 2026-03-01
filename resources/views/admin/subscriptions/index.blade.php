<x-admin-layout>
    <x-slot name="header">Abonnements & Revenus</x-slot>

    <!-- Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                <i data-lucide="banknote" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Revenus Globaux</p>
                <p class="text-xl font-black text-slate-800 dark:text-white">{{ number_format($metrics['total_revenue'], 0, ',', ' ') }} FCFA</p>
            </div>
        </div>
        
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                <i data-lucide="repeat" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">MRR (Revenu Mensuel)</p>
                <p class="text-xl font-black text-slate-800 dark:text-white">{{ number_format($metrics['mrr'], 0, ',', ' ') }} FCFA</p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-amber-200 dark:border-slate-700 flex items-center gap-4 relative overflow-hidden">
            @if($manualPendingCount > 0)
                <div class="absolute inset-0 bg-amber-500/10 animate-pulse"></div>
            @endif
            <div class="w-12 h-12 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center shrink-0 relative z-10">
                <i data-lucide="clock" class="w-6 h-6"></i>
            </div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">En attente (Manuel)</p>
                <p class="text-xl font-black {{ $manualPendingCount > 0 ? 'text-amber-500' : 'text-slate-800 dark:text-white' }}">{{ $manualPendingCount }} paiement(s)</p>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
            
            <form action="{{ route('admin.subscriptions.index') }}" method="GET" class="w-48">
                <select name="status" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border-transparent focus:border-primary focus:ring-0 rounded-xl text-sm font-bold" onchange="this.form.submit()">
                    <option value="">Tous les statuts</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actifs</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expirés</option>
                </select>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 dark:text-slate-400">
                <thead class="bg-slate-50/50 dark:bg-slate-900/50 text-xs uppercase font-bold text-slate-500 border-b border-slate-100 dark:border-slate-700">
                    <tr>
                        <th class="px-6 py-4">Utilisateur / Réf</th>
                        <th class="px-6 py-4 text-center">Plan & Montant</th>
                        <th class="px-6 py-4 text-center">Paiement</th>
                        <th class="px-6 py-4 text-center">Dates</th>
                        <th class="px-6 py-4 text-center">Statut</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($subscriptions as $sub)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900 dark:text-white">{{ $sub->user->name ?? 'Utilisateur inconnu' }}</div>
                                <div class="text-[10px] font-mono text-slate-400 mt-1 uppercase">{{ $sub->payment_reference ?? 'REF-MANUELLE-'.$sub->id }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-bold text-slate-800 dark:text-slate-200 block">{{ ucfirst($sub->plan) }}</span>
                                <span class="text-xs font-black text-primary">{{ number_format($sub->amount, 0, ',', ' ') }} {{ $sub->currency }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($sub->payment_method === 'manual')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase bg-amber-100 text-amber-700"><i data-lucide="hand" class="w-3 h-3"></i> Manuel</span>
                                @elseif($sub->payment_method === 'fedapay')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase bg-indigo-100 text-indigo-700"><i data-lucide="smartphone" class="w-3 h-3"></i> FedaPay</span>
                                @else
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-500 text-[10px] uppercase font-bold">{{ $sub->payment_method }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($sub->status === 'pending')
                                    <span class="text-xs text-slate-400 italic">En attente</span>
                                @else
                                    <div class="text-xs font-medium text-slate-800 dark:text-slate-200">
                                        <div class="text-emerald-600 block mb-0.5"><i data-lucide="play" class="w-3 h-3 inline"></i> {{ \Carbon\Carbon::parse($sub->starts_at)->format('d/m/Y') }}</div>
                                        <div class="text-rose-600"><i data-lucide="square" class="w-3 h-3 inline"></i> {{ \Carbon\Carbon::parse($sub->ends_at)->format('d/m/Y') }}</div>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($sub->status === 'active')
                                    <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold uppercase bg-emerald-100 text-emerald-700">Actif</span>
                                @elseif($sub->status === 'pending')
                                    <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold uppercase bg-amber-100 text-amber-700 animate-pulse">En attente</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold uppercase bg-slate-100 text-slate-500">{{ $sub->status }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($sub->status === 'pending' && $sub->payment_method === 'manual')
                                    <form action="{{ route('admin.subscriptions.validate', $sub) }}" method="POST" onsubmit="return confirm('Confirmez-vous la réception des fonds ({{ $sub->amount }} {{ $sub->currency }}) pour activer cet abonnement ?');">
                                        @csrf @method('PUT')
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-500 text-white hover:bg-emerald-600 text-xs font-bold rounded-lg shadow-sm transition">
                                            <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Valider
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <i data-lucide="inbox" class="w-12 h-12 mb-4 text-slate-300 mx-auto"></i>
                                <span class="font-bold">Aucun abonnement trouvé.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($subscriptions->hasPages())
        <div class="p-6 border-t border-slate-100 dark:border-slate-700">
            {{ $subscriptions->links() }}
        </div>
        @endif
    </div>
</x-admin-layout>

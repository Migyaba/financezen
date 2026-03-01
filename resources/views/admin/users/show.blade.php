<x-admin-layout>
    <x-slot name="header">Détails de {{ $user->name }}</x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-sm font-bold text-slate-500 hover:text-primary transition flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour à la liste
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Col gauche -->
        <div class="space-y-6">
            <!-- Profil -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6 text-center">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6366f1&color=fff&size=96" class="w-24 h-24 rounded-2xl shadow-lg mx-auto mb-4 border-4 border-white dark:border-slate-700">
                <h2 class="text-xl font-black text-slate-900 dark:text-white">{{ $user->name }}</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">{{ $user->email }}</p>

                <div class="flex justify-center flex-wrap gap-2 mb-6">
                    @php
                        $activeSub = $user->subscriptions->where('status', 'active')->where('ends_at', '>', now())->first();
                        $isTrial = $user->trial_ends_at && $user->trial_ends_at > now() && !$activeSub;
                    @endphp
                    @if($activeSub)
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold uppercase">Abonné ({{ ucfirst($activeSub->plan) }})</span>
                    @elseif($isTrial)
                        <span class="px-3 py-1 bg-primary/10 text-primary rounded-lg text-xs font-bold uppercase">En essai ({{ now()->diffInDays($user->trial_ends_at) }}j)</span>
                    @else
                        <span class="px-3 py-1 bg-rose-100 text-rose-700 rounded-lg text-xs font-bold uppercase">Expiré</span>
                    @endif
                    
                    @if($user->role === 'admin')
                        <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-lg text-xs font-bold uppercase"><i data-lucide="shield" class="w-3 h-3 inline"></i> Admin</span>
                    @endif
                </div>

                <div class="border-t border-slate-100 dark:border-slate-700 py-4 grid grid-cols-2 gap-4 divide-x divide-slate-100 dark:divide-slate-700">
                    <div>
                        <p class="text-[10px] uppercase font-bold text-slate-400">Inscrit le</p>
                        <p class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $user->created_at->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold text-slate-400">Téléphone</p>
                        <p class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $user->phone ?? '—' }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions administratives -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
                <h3 class="font-bold text-slate-800 dark:text-white mb-4">Actions</h3>
                
                <form action="{{ route('admin.users.update', $user) }}" method="POST" class="mb-4">
                    @csrf @method('PUT')
                    
                    <div class="space-y-4">
                        <label class="flex items-center justify-between p-3 border border-slate-200 dark:border-slate-600 rounded-xl cursor-pointer">
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Compte Actif (non banni)</span>
                            <input type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }} class="w-5 h-5 text-primary rounded focus:ring-primary" onchange="this.form.submit()">
                        </label>

                        <label class="flex items-center justify-between p-3 border border-slate-200 dark:border-slate-600 rounded-xl cursor-pointer">
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Statut Admin</span>
                            <input type="checkbox" name="role" value="admin" {{ $user->role === 'admin' ? 'checked' : '' }} class="w-5 h-5 text-primary rounded focus:ring-primary" onchange="this.form.submit()">
                            <input type="hidden" name="role" value="user" {{ $user->role !== 'admin' ? 'disabled' : '' }} class="fallback-role">
                            <script>
                                document.querySelector('input[name="role"][type="checkbox"]').addEventListener('change', function(e) {
                                    if(!this.checked) document.querySelector('.fallback-role').disabled = false;
                                });
                            </script>
                        </label>
                    </div>
                </form>

                <div class="border-t border-slate-100 dark:border-slate-700 pt-4">
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement cet utilisateur et toutes ses données financières ?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full flex justify-center items-center gap-2 py-2 px-4 bg-rose-50 hover:bg-rose-100 dark:bg-rose-900/20 dark:hover:bg-rose-900/40 text-rose-600 rounded-xl font-bold transition text-sm">
                            <i data-lucide="trash-2" class="w-4 h-4"></i> Supprimer le compte
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Col Droite -->
        <div class="col-span-1 lg:col-span-2 space-y-6">
            
            <!-- Statistiques rapides -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-indigo-50 border border-indigo-100 dark:bg-indigo-900/20 dark:border-indigo-800 p-4 rounded-2xl text-center">
                    <i data-lucide="list" class="w-6 h-6 text-indigo-500 mx-auto mb-2"></i>
                    <p class="text-2xl font-black text-indigo-600 dark:text-indigo-400">{{ $metrics['transactions_count'] }}</p>
                    <p class="text-[10px] uppercase tracking-wider font-bold text-indigo-500/70">Transactions</p>
                </div>
                <div class="bg-emerald-50 border border-emerald-100 dark:bg-emerald-900/20 dark:border-emerald-800 p-4 rounded-2xl text-center">
                    <i data-lucide="wallet" class="w-6 h-6 text-emerald-500 mx-auto mb-2"></i>
                    <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ $metrics['budgets_count'] }}</p>
                    <p class="text-[10px] uppercase tracking-wider font-bold text-emerald-500/70">Budgets</p>
                </div>
                <div class="bg-rose-50 border border-rose-100 dark:bg-rose-900/20 dark:border-rose-800 p-4 rounded-2xl text-center">
                    <i data-lucide="trending-down" class="w-6 h-6 text-rose-500 mx-auto mb-2"></i>
                    <p class="text-2xl font-black text-rose-600 dark:text-rose-400">{{ $metrics['debts_count'] }}</p>
                    <p class="text-[10px] uppercase tracking-wider font-bold text-rose-500/70">Dettes actives</p>
                </div>
                <div class="bg-amber-50 border border-amber-100 dark:bg-amber-900/20 dark:border-amber-800 p-4 rounded-2xl text-center">
                    <i data-lucide="target" class="w-6 h-6 text-amber-500 mx-auto mb-2"></i>
                    <p class="text-2xl font-black text-amber-600 dark:text-amber-400">{{ $metrics['savings_count'] }}</p>
                    <p class="text-[10px] uppercase tracking-wider font-bold text-amber-500/70">Objectifs Épargne</p>
                </div>
            </div>

            <!-- Abonnements -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800 dark:text-white">État de l'Abonnement</h3>
                    
                    <button x-data @click="$dispatch('open-modal', 'extend-sub-modal')" class="px-3 py-1.5 bg-primary/10 text-primary hover:bg-primary/20 rounded-lg text-xs font-bold transition flex items-center gap-1.5">
                        <i data-lucide="calendar-plus" class="w-3.5 h-3.5"></i> Prolonger (Cadeau)
                    </button>
                    
                    <x-modal name="extend-sub-modal" title="Offrir du temps">
                        <form action="{{ route('admin.users.extend', $user) }}" method="POST" class="p-6">
                            @csrf
                            <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Ajouter des jours gratuits (essai ou extension abo)</p>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nombre de jours</label>
                            <input type="number" name="days" value="30" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 focus:ring-primary mb-6">
                            <div class="flex justify-end gap-3">
                                <button type="button" @click="show = false" class="px-4 py-2 border border-slate-200 text-slate-600 font-bold rounded-xl">Annuler</button>
                                <button type="submit" class="px-6 py-2 bg-primary text-white font-bold rounded-xl shadow-lg hover:bg-primary-dark">Confirmer l'ajout</button>
                            </div>
                        </form>
                    </x-modal>
                </div>
                
                <div class="p-6">
                    @if($user->subscriptions->isEmpty())
                        <div class="text-center py-6 text-slate-400">
                            <i data-lucide="credit-card" class="w-8 h-8 mb-2 mx-auto text-slate-300"></i>
                            <p>Aucun abonnement validé pour le moment.</p>
                        </div>
                    @else
                        <dl class="space-y-4">
                            @foreach($user->subscriptions->sortByDesc('created_at') as $sub)
                                <div class="bg-slate-50 dark:bg-slate-900/50 p-4 rounded-xl flex items-center justify-between border border-slate-100 dark:border-slate-700/50">
                                    <div>
                                        <div class="flex items-center gap-3 mb-1">
                                            <span class="font-bold text-slate-800 dark:text-slate-200">{{ ucfirst($sub->plan) }}</span>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $sub->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-500' }}">{{ $sub->status }}</span>
                                        </div>
                                        <p class="text-xs text-slate-500">Du {{ \Carbon\Carbon::parse($sub->starts_at)->format('d/m/Y') }} au <span class="font-bold border-b border-primary/30 pb-[1px]">{{ \Carbon\Carbon::parse($sub->ends_at)->format('d/m/Y') }}</span></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-black text-primary">{{ number_format($sub->amount, 0, ',', ' ') }} {{ $sub->currency }}</p>
                                        <p class="text-[10px] font-mono text-slate-400 uppercase">{{ $sub->payment_method }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </dl>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>

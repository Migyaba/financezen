<x-admin-layout>
    <x-slot name="header">Gestion des Utilisateurs</x-slot>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex flex-col md:flex-row gap-4 justify-between items-center">
            
            <!-- Filters & Search -->
            <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                <div class="relative w-full sm:w-64">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, email..." class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-900 border-transparent focus:border-primary focus:ring-0 rounded-xl text-sm">
                </div>
                
                <select name="status" class="w-full sm:w-40 px-4 py-2 bg-slate-50 dark:bg-slate-900 border-transparent focus:border-primary focus:ring-0 rounded-xl text-sm" onchange="this.form.submit()">
                    <option value="">Tous les statuts</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Abonnés</option>
                    <option value="trial" {{ request('status') === 'trial' ? 'selected' : '' }}>En essai</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expirés</option>
                </select>
                @if(request('search'))
                    <a href="{{ route('admin.users.index') }}" class="px-3 py-2 text-sm text-slate-500 hover:text-danger self-center">Effacer</a>
                @endif
            </form>

            <span class="text-sm font-bold text-slate-500 dark:text-slate-400">Total : {{ $users->total() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 dark:text-slate-400">
                <thead class="bg-slate-50/50 dark:bg-slate-900/50 text-xs uppercase font-bold text-slate-500 dark:text-slate-500 border-b border-slate-100 dark:border-slate-700">
                    <tr>
                        <th class="px-6 py-4">Utilisateur</th>
                        <th class="px-6 py-4">Contact</th>
                        <th class="px-6 py-4 text-center">Abonnement</th>
                        <th class="px-6 py-4">Fin abonnement/essai</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition">
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6366f1&color=fff&size=40" class="w-10 h-10 rounded-xl shadow-sm">
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-900 dark:text-white truncate">{{ $user->name }}</p>
                                        <p class="text-xs text-slate-400">Inscrit le {{ $user->created_at->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3">
                                <div class="text-sm text-slate-600 dark:text-slate-300">{{ $user->email }}</div>
                                <div class="text-xs text-slate-400">{{ $user->phone ?? '—' }}</div>
                            </td>
                            <td class="px-6 py-3 text-center">
                                @php
                                    $activeSub = $user->subscriptions->where('status', 'active')->where('ends_at', '>', now())->first();
                                    $isTrial = $user->trial_ends_at && $user->trial_ends_at > now() && !$activeSub;
                                @endphp
                                
                                @if($activeSub)
                                    <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold uppercase tracking-wider bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400">Abonné</span>
                                @elseif($isTrial)
                                    <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold uppercase tracking-wider bg-primary/10 text-primary">En Essai</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold uppercase tracking-wider bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400">Expiré</span>
                                @endif
                                
                                @if(!$user->is_active)
                                    <span class="ml-1 px-2 py-0.5 rounded text-[10px] bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold uppercase">Banni</span>
                                @endif
                            </td>
                            <td class="px-6 py-3">
                                @if($activeSub)
                                    <span class="font-medium text-slate-800 dark:text-slate-200">{{ \Carbon\Carbon::parse($activeSub->ends_at)->format('d/m/Y') }}</span>
                                @elseif($isTrial)
                                    <span class="font-medium text-slate-800 dark:text-slate-200">{{ $user->trial_ends_at->format('d/m/Y') }}</span>
                                @else
                                    <span class="text-slate-400">Terminé</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-right">
                                <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-slate-200 text-xs font-bold rounded-lg transition">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i> Détails
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <i data-lucide="users" class="w-12 h-12 mb-4 text-slate-300"></i>
                                    <p class="font-bold text-slate-500">Aucun utilisateur trouvé.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
        <div class="p-6 border-t border-slate-100 dark:border-slate-700">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</x-admin-layout>

<x-admin-layout>
    <x-slot name="header">Dashboard Admin</x-slot>

    <div class="space-y-8">
        <!-- KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <x-kpi-card title="Utilisateurs total" :value="$totalUsers" currency="" icon="users" color="indigo" />
            <x-kpi-card title="Abonnés actifs" :value="$activeSubscribers" currency="" icon="check-circle" color="success" />
            <x-kpi-card title="En essai" :value="$onTrial" currency="" icon="clock" color="warning" />
            <x-kpi-card title="Revenus du mois" :value="$revenueThisMonth" icon="banknote" color="success" />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 text-center">
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-1">Nouveaux ce mois</p>
                <p class="text-3xl font-black text-primary">{{ $newUsersThisMonth }}</p>
            </div>
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 text-center">
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-1">Expirés</p>
                <p class="text-3xl font-black text-danger">{{ $expired }}</p>
            </div>
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 text-center">
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-1">Conversion essai → payant</p>
                <p class="text-3xl font-black text-success">{{ $conversionRate }}%</p>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                <h3 class="font-bold text-slate-800 dark:text-white mb-4">Inscriptions (30 derniers jours)</h3>
                <div class="h-56"><canvas id="signupChart"></canvas></div>
            </div>
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                <h3 class="font-bold text-slate-800 dark:text-white mb-4">Revenus (12 derniers mois)</h3>
                <div class="h-56"><canvas id="revenueChart"></canvas></div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Users -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800 dark:text-white">Dernières inscriptions</h3>
                    <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-primary">Voir tout →</a>
                </div>
                <table class="w-full">
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach($recentUsers as $u)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30">
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($u->name) }}&background=4F46E5&color=fff&size=32" class="w-8 h-8 rounded-full">
                                    <div>
                                        <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $u->name }}</p>
                                        <p class="text-xs text-slate-400">{{ $u->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3 text-right text-xs text-slate-400">{{ $u->created_at->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Recent Payments -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800 dark:text-white">Derniers paiements</h3>
                    @if($pendingPayments > 0)
                    <span class="px-2 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded-lg text-[10px] font-bold">{{ $pendingPayments }} en attente</span>
                    @endif
                </div>
                <table class="w-full">
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($recentPayments as $p)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30">
                            <td class="px-6 py-3 text-sm font-bold text-slate-800 dark:text-white">{{ $p->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-3 text-sm font-black text-success">{{ number_format($p->amount, 0, ',', ' ') }} {{ $p->currency }}</td>
                            <td class="px-6 py-3"><span class="px-2 py-1 rounded-lg text-[10px] font-bold uppercase {{ $p->status == 'success' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400' }}">{{ $p->status }}</span></td>
                            <td class="px-6 py-3 text-xs text-slate-400 text-right">{{ $p->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-6 py-8 text-center text-sm text-slate-400">Aucun paiement.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const isDark = document.documentElement.classList.contains('dark');
        const tc = isDark ? '#94A3B8' : '#64748B';
        const gc = isDark ? 'rgba(148,163,184,0.1)' : 'rgba(226,232,240,0.8)';

        new Chart(document.getElementById('signupChart'), {
            type: 'line',
            data: { labels: {!! json_encode($signupLabels) !!}, datasets: [{ data: {!! json_encode($signupData) !!}, borderColor: '#4F46E5', backgroundColor: 'rgba(79,70,229,0.1)', fill: true, tension: .4, borderWidth: 2, pointRadius: 0 }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false }, ticks: { color: tc, maxTicksLimit: 8 } }, y: { grid: { color: gc }, ticks: { color: tc } } } }
        });

        new Chart(document.getElementById('revenueChart'), {
            type: 'bar',
            data: { labels: {!! json_encode($revenueLabels) !!}, datasets: [{ data: {!! json_encode($revenueData) !!}, backgroundColor: '#10B981', borderRadius: 6 }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false }, ticks: { color: tc } }, y: { grid: { color: gc }, ticks: { color: tc } } } }
        });
    });
    </script>
</x-admin-layout>

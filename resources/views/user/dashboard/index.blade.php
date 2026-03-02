<x-app-layout>
    <x-slot name="header">Tableau de Bord</x-slot>

    <x-onboarding-guide :userName="auth()->user()->name" />

    <div class="space-y-8">
        <!-- Subscription Alert -->
        <x-subscription-banner :daysLeft="$trialDaysLeft" :expired="$subscriptionExpired" />

        <!-- KPI Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <x-kpi-card title="Solde mensuel" :value="$monthlyBalance" icon="wallet" color="indigo" 
                        trend="{{ $balanceTrend > 0 ? '+' : '' }}{{ $balanceTrend }}%" :trendUp="$balanceTrend >= 0" />
            <x-kpi-card title="Dette restante" :value="$totalDebt" icon="credit-card" color="danger" />
            <x-kpi-card title="Épargne totale" :value="$totalSavings" icon="gem" color="success" />
            <x-kpi-card title="Fonds urgence %" :value="$emergencyFundPercent" currency="%" icon="target" color="warning" />
        </div>

        <!-- Feux Tricolores -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <x-indicator-card label="Solde mensuel" :status="$balanceStatus" :message="$balanceMessage" :description="$balanceDesc" icon="check-circle" />
            <x-indicator-card label="Épargne" :status="$savingsStatus" :message="$savingsMessage" :description="$savingsDesc" icon="arrow-up-right" />
            <x-indicator-card label="Dettes" :status="$debtStatus" :message="$debtMessage" :description="$debtDesc" icon="alert-triangle" />
        </div>

        <!-- Graphs Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Bar Chart: Revenus vs Dépenses -->
            <div class="lg:col-span-2 bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                <h3 class="font-bold text-slate-800 dark:text-white mb-6">Revenus vs Dépenses — 6 derniers mois</h3>
                <div class="h-64">
                    <canvas id="mainChart"></canvas>
                </div>
            </div>

            <!-- Pie Chart: Répartition dépenses -->
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                <h3 class="font-bold text-slate-800 dark:text-white mb-6">Répartition dépenses du mois</h3>
                @if($expensesByCategory->count() > 0)
                    <div class="h-52">
                        <canvas id="pieChart"></canvas>
                    </div>
                    <div class="mt-4 space-y-2">
                        @foreach($expensesByCategory as $ec)
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full" style="background: {{ $ec->category->color ?? '#94a3b8' }}"></span>
                                <span class="text-slate-600 dark:text-slate-400">{{ $ec->category->name ?? 'N/A' }}</span>
                            </div>
                            <span class="font-bold text-slate-800 dark:text-white">{{ number_format($ec->total, 0, ',', ' ') }}</span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <x-empty-state icon="pie-chart" title="Aucune dépense" description="Commencez à enregistrer vos transactions." />
                @endif
            </div>
        </div>

        <!-- Bottom Section: Transactions + Goals + Debts -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Top 5 dernières transactions -->
            <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 dark:text-white">Dernières transactions</h3>
                    <a href="{{ route('transactions.index') }}" class="text-xs font-bold text-primary hover:text-primary-dark transition">Voir tout →</a>
                </div>
                @if($recentTransactions->count() > 0)
                <table class="w-full">
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach($recentTransactions as $tx)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition">
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white" style="background: {{ $tx->category->color ?? '#94a3b8' }}">
                                        <i data-lucide="{{ $tx->category->icon ?? 'tag' }}" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $tx->description ?? $tx->category->name ?? '-' }}</p>
                                        <p class="text-xs text-slate-400">{{ $tx->transaction_date->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3 text-right">
                                <span class="text-sm font-black {{ $tx->type == 'income' ? 'text-success' : 'text-danger' }}">
                                    {{ $tx->type == 'income' ? '+' : '-' }}{{ number_format($tx->amount, 0, ',', ' ') }} FCFA
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <x-empty-state icon="arrow-left-right" title="Aucune transaction" description="Ajoutez votre première transaction." cta="Ajouter" ctaUrl="{{ route('transactions.index') }}" />
                @endif
            </div>

            <!-- Right Column: Goals + Debts -->
            <div class="space-y-6">
                <!-- Objectifs d'épargne -->
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-slate-800 dark:text-white">Objectifs d'épargne</h3>
                        <a href="{{ route('savings.index') }}" class="text-xs font-bold text-primary">Voir →</a>
                    </div>
                    @forelse($savingsGoals as $goal)
                    @php $pct = $goal->target_amount > 0 ? round(($goal->current_amount / $goal->target_amount) * 100) : 0; @endphp
                    <div class="mb-4">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-slate-600 dark:text-slate-400">{{ $goal->name }}</span>
                            <span class="font-bold text-slate-900 dark:text-white">{{ $pct }}%</span>
                        </div>
                        <div class="w-full h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-700" style="width: {{ min($pct, 100) }}%; background: {{ $goal->color ?? '#4F46E5' }}"></div>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-slate-400 text-center py-4">Aucun objectif actif.</p>
                    @endforelse
                </div>

                <!-- Prochains paiements de dette -->
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-slate-800 dark:text-white">Prochains paiements</h3>
                        <a href="{{ route('debts.index') }}" class="text-xs font-bold text-primary">Voir →</a>
                    </div>
                    @forelse($upcomingDebts as $debt)
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full" style="background: {{ $debt->color ?? '#EF4444' }}"></div>
                            <div>
                                <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $debt->name }}</p>
                                <p class="text-xs text-slate-400">{{ $debt->due_date ? \Carbon\Carbon::parse($debt->due_date)->format('d/m/Y') : 'Sans échéance' }}</p>
                            </div>
                        </div>
                        <span class="text-sm font-black text-danger">{{ number_format($debt->monthly_payment, 0, ',', ' ') }}</span>
                    </div>
                    @empty
                    <p class="text-sm text-slate-400 text-center py-4">Aucun paiement prévu.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            function getChartColors() {
                const isDark = document.documentElement.classList.contains('dark');
                return {
                    gridColor: isDark ? 'rgba(148,163,184,0.1)' : 'rgba(226,232,240,0.8)',
                    textColor: isDark ? '#94A3B8' : '#64748B',
                    expenseBg: isDark ? '#334155' : '#F1F5F9',
                };
            }

            // === Bar Chart ===
            const mainCtx = document.getElementById('mainChart').getContext('2d');
            const colors = getChartColors();
            const mainChart = new Chart(mainCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [
                        { label: 'Revenus', data: {!! json_encode($chartIncomes) !!}, backgroundColor: '#4F46E5', borderRadius: 6 },
                        { label: 'Dépenses', data: {!! json_encode($chartExpenses) !!}, backgroundColor: colors.expenseBg, borderRadius: 6 }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: colors.textColor } },
                        y: { grid: { color: colors.gridColor }, ticks: { color: colors.textColor } }
                    }
                }
            });

            // === Pie Chart ===
            @if($expensesByCategory->count() > 0)
            const pieCtx = document.getElementById('pieChart').getContext('2d');
            new Chart(pieCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($expensesByCategory->map(fn($e) => $e->category->name ?? 'N/A')) !!},
                    datasets: [{
                        data: {!! json_encode($expensesByCategory->pluck('total')) !!},
                        backgroundColor: {!! json_encode($expensesByCategory->map(fn($e) => $e->category->color ?? '#94a3b8')) !!},
                        borderWidth: 0,
                        spacing: 3,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: { legend: { display: false } }
                }
            });
            @endif

            document.addEventListener('theme-changed', (e) => {
                const c = getChartColors();
                mainChart.options.scales.x.ticks.color = c.textColor;
                mainChart.options.scales.y.ticks.color = c.textColor;
                mainChart.options.scales.y.grid.color = c.gridColor;
                mainChart.data.datasets[1].backgroundColor = c.expenseBg;
                mainChart.update();
            });
        });
    </script>
</x-app-layout>

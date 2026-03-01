<x-app-layout>
    <x-slot name="header">Rapports & Analyses</x-slot>

    <div class="space-y-6">
        <!-- Filters & Exports -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
            <form action="{{ route('reports.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="text-sm rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:ring-primary h-10 font-medium text-slate-600 dark:text-slate-300">
                <span class="text-slate-400 font-bold">à</span>
                <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="text-sm rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:ring-primary h-10 font-medium text-slate-600 dark:text-slate-300">
                
                <input type="hidden" name="period" value="custom">
                
                <button type="submit" class="px-5 h-10 bg-primary text-white font-bold rounded-xl text-sm hover:bg-primary-dark transition flex items-center gap-2">
                    <i data-lucide="filter" class="w-4 h-4"></i> Filtrer
                </button>
                
                @if(request('period') === 'custom')
                    <a href="{{ route('reports.index') }}" class="text-sm text-slate-500 font-bold hover:text-primary transition">Réinitialiser</a>
                @endif
            </form>

            <div class="flex items-center gap-2">
                <a href="{{ route('reports.export.csv', request()->all()) }}" class="px-4 h-10 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-xl text-sm hover:bg-slate-200 dark:hover:bg-slate-600 transition flex items-center gap-2">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4"></i> CSV
                </a>
                <a href="{{ route('reports.export.pdf', request()->all()) }}" class="px-4 h-10 bg-slate-800 dark:bg-slate-600 text-white font-bold rounded-xl text-sm hover:bg-slate-700 transition flex items-center gap-2">
                    <i data-lucide="file-text" class="w-4 h-4"></i> PDF
                </a>
            </div>
        </div>

        <!-- Key Metrics & Comparisons -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Revenus -->
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <i data-lucide="trending-up" class="w-5 h-5"></i>
                    </div>
                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold flex items-center gap-1 {{ $incomeChange >= 0 ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/20' : 'bg-red-50 text-red-600 dark:bg-red-900/20' }}">
                        <i data-lucide="{{ $incomeChange >= 0 ? 'arrow-up-right' : 'arrow-down-right' }}" class="w-3 h-3"></i>
                        {{ abs($incomeChange) }}% vs prec.
                    </span>
                </div>
                <h3 class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-1">Revenus (période)</h3>
                <p class="text-2xl font-black text-slate-800 dark:text-white">{{ number_format($currentIncome, 0, ',', ' ') }} <span class="text-sm text-slate-400">FCFA</span></p>
            </div>

            <!-- Dépenses -->
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center text-red-600 dark:text-red-400">
                        <i data-lucide="trending-down" class="w-5 h-5"></i>
                    </div>
                    <!-- Note: Decrease in expenses is good (emerald), increase is bad (red) -->
                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold flex items-center gap-1 {{ $expenseChange <= 0 ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/20' : 'bg-red-50 text-red-600 dark:bg-red-900/20' }}">
                        <i data-lucide="{{ $expenseChange >= 0 ? 'arrow-up-right' : 'arrow-down-right' }}" class="w-3 h-3"></i>
                        {{ abs($expenseChange) }}% vs prec.
                    </span>
                </div>
                <h3 class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-1">Dépenses (période)</h3>
                <p class="text-2xl font-black text-slate-800 dark:text-white">{{ number_format($currentExpense, 0, ',', ' ') }} <span class="text-sm text-slate-400">FCFA</span></p>
            </div>

            <!-- Global Debt Progress -->
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                <div class="w-10 h-10 rounded-xl bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center text-rose-600 dark:text-rose-400 mb-4">
                    <i data-lucide="credit-card" class="w-5 h-5"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-1">Effort Dettes (Global)</h3>
                <p class="text-2xl font-black text-slate-800 dark:text-white">{{ $debtProgress }}% <span class="text-sm font-bold text-slate-400">Remboursé</span></p>
                <div class="w-full h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full mt-3 overflow-hidden">
                    <div class="h-full bg-rose-500 rounded-full" style="width: {{ $debtProgress }}%"></div>
                </div>
            </div>

            <!-- Global Savings Progress -->
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400 mb-4">
                    <i data-lucide="piggy-bank" class="w-5 h-5"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-1">Épargne (Globale)</h3>
                <p class="text-2xl font-black text-slate-800 dark:text-white">{{ $savingsProgress }}% <span class="text-sm font-bold text-slate-400">Atteint</span></p>
                <div class="w-full h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full mt-3 overflow-hidden">
                    <div class="h-full bg-emerald-500 rounded-full" style="width: {{ min($savingsProgress, 100) }}%"></div>
                </div>
            </div>
        </div>

        <!-- Charts (Pie & Top Categories) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Pie Chart -->
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                <h3 class="font-bold text-slate-800 dark:text-white mb-6">Répartition des dépenses (Période)</h3>
                @if($pieChartData->isEmpty())
                    <div class="h-64 flex items-center justify-center flex-col text-slate-400">
                        <i data-lucide="pie-chart" class="w-12 h-12 mb-3 opacity-50"></i>
                        <p class="font-medium text-sm">Aucune donnée pour cette période</p>
                    </div>
                @else
                    <div class="h-64 relative" x-data="pieChartComponent()">
                        <canvas id="pieChart"></canvas>
                    </div>
                @endif
            </div>

            <!-- Top Categories -->
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                <h3 class="font-bold text-slate-800 dark:text-white mb-6">Top 5 Catégories de Dépenses</h3>
                <div class="space-y-4">
                    @forelse($topCategories as $tc)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white shadow-sm" style="background-color: {{ $tc->category->color ?? '#94a3b8' }}">
                                    <i data-lucide="{{ $tc->category->icon ?? 'tag' }}" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $tc->category->name ?? 'N/A' }}</p>
                                    @php $percent = $currentExpense > 0 ? round(($tc->total / $currentExpense) * 100) : 0; @endphp
                                    <p class="text-xs text-slate-400 font-medium">{{ $percent }}% du total</p>
                                </div>
                            </div>
                            <span class="text-sm font-black text-danger">{{ number_format($tc->total, 0, ',', ' ') }} FCFA</span>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-sm text-slate-500 dark:text-slate-400 font-bold">Aucune dépense enregistrée.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Monthly Trends Line Chart -->
        @if($months->isNotEmpty())
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-bold text-slate-800 dark:text-white">Tendances (6 derniers mois)</h3>
                <a href="{{ route('reports.index', ['period' => 6]) }}" class="text-xs font-bold text-primary hover:text-primary-dark">Générer les 6 mois</a>
            </div>
            
            <div class="h-72">
                <canvas id="reportsChart"></canvas>
            </div>
        </div>
        @endif
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#94A3B8' : '#64748B';
            const gridColor = isDark ? 'rgba(148,163,184,0.1)' : 'rgba(226,232,240,0.8)';

            // Pie Chart Component
            Alpine.data('pieChartComponent', () => ({
                init() {
                    const ctx = document.getElementById('pieChart');
                    const pieData = @json($pieChartData);
                    
                    if (!pieData || pieData.length === 0) return;

                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: pieData.map(d => d.label),
                            datasets: [{
                                data: pieData.map(d => d.value),
                                backgroundColor: pieData.map(d => d.color),
                                borderWidth: 0,
                                hoverOffset: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '70%',
                            plugins: {
                                legend: { position: 'right', labels: { color: textColor, font: { family: "'Inter', sans-serif", weight: 'bold' }, padding: 20 } },
                                tooltip: {
                                    backgroundColor: '#1E293B',
                                    padding: 12,
                                    titleFont: { size: 13, family: "'Inter', sans-serif" },
                                    bodyFont: { size: 14, family: "'Inter', sans-serif", weight: 'bold' },
                                    displayColors: true,
                                    callbacks: { label: function(context) { return context.label + ': ' + new Intl.NumberFormat('fr-FR').format(context.raw) + ' FCFA'; } }
                                }
                            }
                        }
                    });
                }
            }));

            // Monthly Trends Line Chart
            @if($months->isNotEmpty())
            const lineCtx = document.getElementById('reportsChart');
            new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($months->pluck('label')) !!},
                    datasets: [
                        {
                            label: 'Revenus',
                            data: {!! json_encode($months->pluck('income')) !!},
                            borderColor: '#10B981',
                            backgroundColor: 'rgba(16,185,129,0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3,
                            pointRadius: 4,
                            pointBackgroundColor: '#10B981'
                        },
                        {
                            label: 'Dépenses',
                            data: {!! json_encode($months->pluck('expense')) !!},
                            borderColor: '#EF4444',
                            backgroundColor: 'rgba(239,68,68,0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3,
                            pointRadius: 4,
                            pointBackgroundColor: '#EF4444'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom', labels:{color: textColor, font: {family: "'Inter', sans-serif", weight: 'bold'}} } },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: textColor, font: {family: "'Inter', sans-serif", weight: 'bold'} } },
                        y: { 
                            grid: { color: gridColor }, 
                            ticks: { 
                                color: textColor, 
                                font: {family: "'Inter', sans-serif", weight: 'bold'},
                                callback: function(value) { return new Intl.NumberFormat('fr-FR').format(value); }
                            } 
                        }
                    }
                }
            });
            @endif
        });
    </script>
    @endpush
</x-app-layout>

<x-app-layout>
    <x-slot name="header">{{ $saving->name }}</x-slot>

    <div class="space-y-6">
        <a href="{{ route('savings.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-primary transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour aux objectifs
        </a>

        @php $percent = $saving->target_amount > 0 ? round(($saving->current_amount / $saving->target_amount) * 100) : 0; @endphp

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-8">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-black text-slate-800 dark:text-white">{{ $saving->name }}</h2>
                @if($saving->description) <p class="text-slate-500 text-sm mt-1">{{ $saving->description }}</p> @endif
            </div>
            <div class="text-center mb-6">
                <p class="text-4xl font-black text-primary">{{ number_format($saving->current_amount, 0, ',', ' ') }}</p>
                <p class="text-sm text-slate-400 font-medium">sur {{ number_format($saving->target_amount, 0, ',', ' ') }} FCFA</p>
            </div>
            <div class="max-w-md mx-auto flex items-center gap-4">
                <div class="flex-1 h-4 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                    <div class="h-full bg-success rounded-full transition-all" style="width: {{ min($percent, 100) }}%"></div>
                </div>
                <span class="text-xl font-black text-success">{{ $percent }}%</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Progress Chart (2/3 width) -->
            <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-8">
                <h3 class="font-bold text-slate-800 dark:text-white mb-6">Évolution de l'épargne</h3>
                <div class="h-64" x-data="savingsChartComponent()">
                    <canvas id="savingsChart"></canvas>
                </div>
            </div>

            <!-- Simulation Card (1/3 width) -->
            <div class="bg-gradient-to-br from-primary to-indigo-600 rounded-2xl shadow-sm p-8 text-white flex flex-col justify-center" x-data="{
                monthlyContribution: {{ $saving->current_amount > 0 && $saving->contributions->count() > 0 ? round($saving->current_amount / $saving->contributions->count()) : 50000 }},
                get monthsLeft() {
                    const remaining = {{ $saving->target_amount }} - {{ $saving->current_amount }};
                    if (remaining <= 0) return 0;
                    if (this.monthlyContribution <= 0) return '∞';
                    return Math.ceil(remaining / this.monthlyContribution);
                }
            }">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center backdrop-blur-sm">
                        <i data-lucide="calculator" class="w-5 h-5"></i>
                    </div>
                    <h3 class="font-bold text-white">Simulation</h3>
                </div>
                
                <p class="text-sm text-white/80 mb-2">Si j'épargne chaque mois :</p>
                <div class="relative mb-6">
                    <input type="number" x-model.number="monthlyContribution" class="w-full bg-white/10 border border-white/20 rounded-xl text-white font-bold h-12 pl-4 pr-12 focus:ring-white focus:border-white transition placeholder-white/50" min="1000" step="1000">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-white/60 font-medium text-sm">FCFA</span>
                </div>

                <div class="bg-white/10 rounded-xl p-4 border border-white/10 backdrop-blur-sm">
                    <p class="text-xs text-white/70 uppercase tracking-widest font-bold mb-1">Objectif atteint dans</p>
                    <p class="text-3xl font-black">
                        <span x-text="monthsLeft"></span> <span class="text-xl font-bold text-white/80" x-show="monthsLeft !== '∞'">mois</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700">
                <h3 class="font-bold text-slate-800 dark:text-white">Historique des contributions</h3>
            </div>
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-700">
                        <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-widest text-left">Date</th>
                        <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-widest text-left">Montant</th>
                        <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-widest text-left">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($saving->contributions as $c)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition">
                        <td class="px-6 py-4 text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $c->contribution_date->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm font-black text-success">+{{ number_format($c->amount, 0, ',', ' ') }} FCFA</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $c->notes ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-6 py-12 text-center text-slate-500 text-sm">Aucune contribution enregistrée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('savingsChartComponent', () => ({
                init() {
                    const ctx = document.getElementById('savingsChart');
                    const chartData = @json($chartData);
                    
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: chartData.map(d => d.date),
                            datasets: [{
                                label: 'Montant épargné (FCFA)',
                                data: chartData.map(d => d.amount),
                                borderColor: '#10B981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: '#10B981',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                pointRadius: 4,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: '#1E293B',
                                    padding: 12,
                                    titleFont: { size: 13, family: "'Inter', sans-serif" },
                                    bodyFont: { size: 14, family: "'Inter', sans-serif", weight: 'bold' },
                                    displayColors: false,
                                    callbacks: {
                                        label: function(context) {
                                            return new Intl.NumberFormat('fr-FR').format(context.parsed.y) + ' FCFA';
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: { color: 'rgba(148, 163, 184, 0.1)' },
                                    ticks: {
                                        color: '#94A3B8',
                                        font: { family: "'Inter', sans-serif" },
                                        callback: function(value) { return new Intl.NumberFormat('fr-FR').format(value); }
                                    }
                                },
                                x: {
                                    grid: { display: false },
                                    ticks: { color: '#94A3B8', font: { family: "'Inter', sans-serif" } }
                                }
                            }
                        }
                    });
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>

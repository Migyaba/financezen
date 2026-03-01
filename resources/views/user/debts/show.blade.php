<x-app-layout>
    <x-slot name="header">{{ $debt->name }}</x-slot>

    <div class="space-y-6">
        <a href="{{ route('debts.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-primary transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour aux dettes
        </a>

        <!-- Debt Summary -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h2 class="text-2xl font-black text-slate-800 dark:text-white">{{ $debt->name }}</h2>
                    @if($debt->creditor) <p class="text-slate-400 font-medium mt-1">Créancier : {{ $debt->creditor }}</p> @endif
                    @if($debt->description) <p class="text-slate-500 text-sm mt-2">{{ $debt->description }}</p> @endif
                </div>
                <div class="flex items-center gap-6">
                    <div class="text-center">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Initial</p>
                        <p class="text-xl font-black text-slate-800 dark:text-white">{{ number_format($debt->initial_amount, 0, ',', ' ') }}</p>
                    </div>
                    <div class="h-10 w-px bg-slate-200 dark:bg-slate-700"></div>
                    <div class="text-center">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Restant</p>
                        <p class="text-xl font-black text-danger">{{ number_format($debt->current_amount, 0, ',', ' ') }}</p>
                    </div>
                </div>
            </div>
            @php $paidPercent = $debt->initial_amount > 0 ? round((($debt->initial_amount - $debt->current_amount) / $debt->initial_amount) * 100) : 0; @endphp
            <div class="mt-6 flex items-center gap-4">
                <div class="flex-1 h-3 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                    <div class="h-full bg-primary rounded-full transition-all" style="width: {{ $paidPercent }}%"></div>
                </div>
                <span class="text-lg font-black text-primary">{{ $paidPercent }}%</span>
            </div>
        </div>

        <!-- Progress Chart -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-8">
            <h3 class="font-bold text-slate-800 dark:text-white mb-6">Évolution du remboursement</h3>
            <div class="h-64" x-data="debtChartComponent()">
                <canvas id="debtChart"></canvas>
            </div>
        </div>

        <!-- Payment History -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700">
                <h3 class="font-bold text-slate-800 dark:text-white">Historique des paiements</h3>
            </div>
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-700">
                        <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-widest text-left">Date</th>
                        <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-widest text-left">Montant</th>
                        <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-widest text-left">Source</th>
                        <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-widest text-left">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($debt->payments as $payment)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition">
                        <td class="px-6 py-4 text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $payment->payment_date->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm font-black text-success">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</td>
                        <td class="px-6 py-4"><span class="px-2 py-1 rounded-md bg-slate-100 dark:bg-slate-700 text-[10px] font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">{{ $payment->source }}</span></td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $payment->notes ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-6 py-12 text-center text-slate-500 text-sm">Aucun paiement enregistré.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('debtChartComponent', () => ({
                init() {
                    const ctx = document.getElementById('debtChart');
                    const chartData = @json($chartData);
                    
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: chartData.map(d => d.date),
                            datasets: [{
                                label: 'Montant restant (FCFA)',
                                data: chartData.map(d => d.amount),
                                borderColor: '#EF4444',
                                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: '#EF4444',
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

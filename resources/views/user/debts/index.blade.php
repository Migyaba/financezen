<x-app-layout>
    <x-slot name="header">Gestion des Dettes</x-slot>

    <div class="space-y-6">
        <!-- Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <x-kpi-card title="Dettes initiales" :value="$totalInitial" icon="wallet" color="danger" />
            <x-kpi-card title="Déjà remboursé" :value="$totalPaid" icon="check-circle" color="success" />
            <x-kpi-card title="Restant à payer" :value="$totalCurrent" icon="alert-triangle" color="warning" />
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-3">Progression globale</p>
                <div class="flex items-center gap-4">
                    <div class="flex-1 h-3 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                        <div class="h-full bg-primary rounded-full transition-all duration-700" style="width: {{ $percentPaid }}%"></div>
                    </div>
                    <span class="text-lg font-black text-primary">{{ $percentPaid }}%</span>
                </div>
            </div>
        </div>

        <!-- Add Button -->
        <div class="flex justify-end">
            <button onclick="document.getElementById('add-debt').classList.remove('hidden'); document.getElementById('add-debt').classList.add('flex');"
                    class="px-6 py-3 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:bg-primary-dark transition flex items-center gap-2">
                <i data-lucide="plus-circle" class="w-5 h-5"></i>
                Ajouter une dette
            </button>
        </div>

        <!-- Debts Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($debts as $debt)
            @php
                $paidPercent = $debt->initial_amount > 0 ? round((($debt->initial_amount - $debt->current_amount) / $debt->initial_amount) * 100) : 0;
            @endphp
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden hover:shadow-md transition group">
                <!-- Color Bar -->
                <div class="h-2" style="background-color: {{ $debt->color ?? '#4F46E5' }}"></div>
                
                <div class="p-6 space-y-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-white text-lg">{{ $debt->name }}</h3>
                            @if($debt->creditor)
                                <p class="text-xs text-slate-400 font-medium">Créancier : {{ $debt->creditor }}</p>
                            @endif
                        </div>
                        <span class="px-2 py-1 text-[10px] font-bold uppercase tracking-wider rounded-lg
                            {{ $debt->status == 'active' ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400' : '' }}
                            {{ $debt->status == 'paid' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : '' }}
                            {{ $debt->status == 'paused' ? 'bg-slate-100 dark:bg-slate-700 text-slate-500' : '' }}
                        ">{{ $debt->status }}</span>
                    </div>

                    <div class="space-y-1">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500 dark:text-slate-400">Remboursé</span>
                            <span class="font-bold text-slate-800 dark:text-white">{{ $paidPercent }}%</span>
                        </div>
                        <div class="w-full h-2.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-700" style="width: {{ $paidPercent }}%; background-color: {{ $debt->color ?? '#4F46E5' }}"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-center pt-2">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Initial</p>
                            <p class="text-sm font-black text-slate-800 dark:text-white">{{ number_format($debt->initial_amount, 0, ',', ' ') }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Restant</p>
                            <p class="text-sm font-black text-danger">{{ number_format($debt->current_amount, 0, ',', ' ') }}</p>
                        </div>
                    </div>

                    @if($debt->monthly_payment > 0)
                    <p class="text-xs text-slate-400 italic text-center">Paiement mensuel : {{ number_format($debt->monthly_payment, 0, ',', ' ') }} FCFA</p>
                    @endif

                    <!-- Actions -->
                    <div class="flex gap-2 pt-2">
                        <button onclick="document.getElementById('pay-{{ $debt->id }}').classList.remove('hidden'); document.getElementById('pay-{{ $debt->id }}').classList.add('flex');"
                                class="flex-1 py-2.5 bg-primary/10 text-primary font-bold rounded-xl text-sm hover:bg-primary/20 transition text-center">
                            Enregistrer paiement
                        </button>
                        <a href="{{ route('debts.show', $debt) }}" class="py-2.5 px-4 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-xl text-sm hover:bg-slate-200 dark:hover:bg-slate-600 transition">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Payment Modal -->
            <x-modal id="pay-{{ $debt->id }}" title="Paiement — {{ $debt->name }}">
                <form action="{{ route('debts.payment', $debt) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Montant (FCFA)</label>
                        <input type="number" name="amount" required step="0.01" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 font-bold focus:ring-primary" placeholder="0">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Date</label>
                        <input type="date" name="payment_date" required value="{{ date('Y-m-d') }}" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Source</label>
                        <select name="source" required class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 focus:ring-primary">
                            <option value="salary">Salaire</option>
                            <option value="freelance">Freelance</option>
                            <option value="other">Autre</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Notes</label>
                        <textarea name="notes" rows="2" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:ring-primary"></textarea>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="this.closest('[id^=pay-]').classList.add('hidden'); this.closest('[id^=pay-]').classList.remove('flex');" class="flex-1 py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-200 transition">Annuler</button>
                        <button type="submit" class="flex-1 py-3 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:bg-primary-dark transition">Enregistrer</button>
                    </div>
                </form>
            </x-modal>
            @empty
            <div class="md:col-span-3 bg-white dark:bg-slate-800 rounded-2xl p-16 shadow-sm border border-slate-100 dark:border-slate-700 text-center">
                <div class="w-20 h-20 bg-slate-50 dark:bg-slate-900 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-6">
                    <i data-lucide="credit-card" class="w-10 h-10"></i>
                </div>
                <h3 class="font-bold text-slate-800 dark:text-white mb-2">Aucune dette enregistrée</h3>
                <p class="text-sm text-slate-500 mb-6">Commencez par ajouter vos dettes pour suivre vos remboursements.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Add Debt Modal -->
    <x-modal id="add-debt" title="Ajouter une dette">
        <form action="{{ route('debts.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Nom de la dette</label>
                <input type="text" name="name" required class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 font-bold focus:ring-primary" placeholder="Ex: Prêt personnel">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Montant (FCFA)</label>
                    <input type="number" name="initial_amount" required step="0.01" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 font-bold focus:ring-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Paiement mensuel</label>
                    <input type="number" name="monthly_payment" step="0.01" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 font-bold focus:ring-primary">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Créancier</label>
                <input type="text" name="creditor" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 focus:ring-primary" placeholder="Ex: Banque, Famille...">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Échéance</label>
                    <input type="date" name="due_date" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Couleur</label>
                    <input type="color" name="color" value="#4F46E5" class="w-full rounded-xl h-12 cursor-pointer border-slate-200">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Description</label>
                <textarea name="description" rows="2" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:ring-primary"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('add-debt').classList.add('hidden'); document.getElementById('add-debt').classList.remove('flex');" class="flex-1 py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-200 transition">Annuler</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:bg-primary-dark transition">Ajouter</button>
            </div>
        </form>
    </x-modal>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">Épargne & Objectifs</x-slot>

    <div class="space-y-6">
        <!-- Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <x-kpi-card title="Épargne totale" :value="$totalSaved" icon="gem" color="success" />
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Objectifs actifs</p>
                <h3 class="text-2xl font-bold text-primary">{{ $activeGoals }}</h3>
            </div>
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Objectifs atteints 🎉</p>
                <h3 class="text-2xl font-bold text-success">{{ $achievedGoals }}</h3>
            </div>
        </div>

        <!-- Add Button -->
        <div class="flex justify-end">
            <button onclick="document.getElementById('add-goal').classList.remove('hidden'); document.getElementById('add-goal').classList.add('flex');"
                    class="px-6 py-3 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:bg-primary-dark transition flex items-center gap-2">
                <i data-lucide="plus-circle" class="w-5 h-5"></i>
                Nouvel Objectif
            </button>
        </div>

        <!-- Goals Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($goals as $goal)
            @php
                $percent = $goal->target_amount > 0 ? round(($goal->current_amount / $goal->target_amount) * 100) : 0;
                $typeLabels = ['emergency_fund' => '🏥 Fonds d\'urgence', 'investment' => '📈 Investissement', 'project' => '🎯 Projet', 'other' => '📦 Autre'];
            @endphp
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden hover:shadow-md transition">
                <div class="h-2" style="background-color: {{ $goal->color ?? '#10B981' }}"></div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">{{ $typeLabels[$goal->type] ?? $goal->type }}</p>
                            <h3 class="font-bold text-lg text-slate-800 dark:text-white mt-1">{{ $goal->name }}</h3>
                        </div>
                        @if($goal->status == 'achieved')
                            <span class="px-2 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-[10px] font-bold rounded-lg uppercase tracking-wider">Atteint ✓</span>
                        @endif
                    </div>

                    <div class="text-center">
                        <p class="text-3xl font-black text-slate-800 dark:text-white">{{ number_format($goal->current_amount, 0, ',', ' ') }}</p>
                        <p class="text-xs text-slate-400 font-medium">sur {{ number_format($goal->target_amount, 0, ',', ' ') }} FCFA</p>
                    </div>

                    <div class="space-y-1">
                        <div class="w-full h-3 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-700" style="width: {{ min($percent, 100) }}%; background-color: {{ $goal->color ?? '#10B981' }}"></div>
                        </div>
                        <p class="text-right text-xs font-bold" style="color: {{ $goal->color ?? '#10B981' }}">{{ $percent }}%</p>
                    </div>

                    @if($goal->monthly_target > 0)
                        <p class="text-xs text-slate-400 text-center italic">Objectif mensuel : {{ number_format($goal->monthly_target, 0, ',', ' ') }} FCFA</p>
                    @endif

                    <div class="flex gap-2 pt-2">
                        <button onclick="document.getElementById('contribute-{{ $goal->id }}').classList.remove('hidden'); document.getElementById('contribute-{{ $goal->id }}').classList.add('flex');"
                                class="flex-1 py-2.5 bg-success/10 text-success font-bold rounded-xl text-sm hover:bg-success/20 transition text-center">
                            Ajouter contribution
                        </button>
                        <a href="{{ route('savings.show', $goal) }}" class="py-2.5 px-4 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-xl text-sm hover:bg-slate-200 dark:hover:bg-slate-600 transition">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contribution Modal -->
            <x-modal id="contribute-{{ $goal->id }}" title="Contribution — {{ $goal->name }}">
                <form action="{{ route('savings.contribution', $goal) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Montant (FCFA)</label>
                        <input type="number" name="amount" required step="0.01" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 font-bold focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Date</label>
                        <input type="date" name="contribution_date" required value="{{ date('Y-m-d') }}" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Notes</label>
                        <textarea name="notes" rows="2" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:ring-primary"></textarea>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="this.closest('[id^=contribute-]').classList.add('hidden'); this.closest('[id^=contribute-]').classList.remove('flex');" class="flex-1 py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-xl">Annuler</button>
                        <button type="submit" class="flex-1 py-3 bg-success text-white font-bold rounded-xl shadow-lg hover:bg-emerald-600 transition">Enregistrer</button>
                    </div>
                </form>
            </x-modal>
            @empty
            <div class="md:col-span-3 bg-white dark:bg-slate-800 rounded-2xl p-16 shadow-sm border border-slate-100 dark:border-slate-700 text-center">
                <div class="w-20 h-20 bg-slate-50 dark:bg-slate-900 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-6">
                    <i data-lucide="gem" class="w-10 h-10"></i>
                </div>
                <h3 class="font-bold text-slate-800 dark:text-white mb-2">Aucun objectif d'épargne</h3>
                <p class="text-sm text-slate-500 mb-6">Définissez vos objectifs d'épargne pour mieux gérer vos finances.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Add Goal Modal -->
    <x-modal id="add-goal" title="Nouvel objectif d'épargne">
        <form action="{{ route('savings.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Nom</label>
                <input type="text" name="name" required class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 font-bold focus:ring-primary" placeholder="Ex: Fonds d'urgence">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Type</label>
                <select name="type" required class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 focus:ring-primary">
                    <option value="emergency_fund">Fonds d'urgence</option>
                    <option value="investment">Investissement</option>
                    <option value="project">Projet</option>
                    <option value="other">Autre</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Montant cible (FCFA)</label>
                    <input type="number" name="target_amount" required step="0.01" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 font-bold focus:ring-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Mensuel cible</label>
                    <input type="number" name="monthly_target" step="0.01" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 font-bold focus:ring-primary">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Date cible</label>
                    <input type="date" name="target_date" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Couleur</label>
                    <input type="color" name="color" value="#10B981" class="w-full rounded-xl h-12 cursor-pointer border-slate-200">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Description</label>
                <textarea name="description" rows="2" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:ring-primary"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('add-goal').classList.add('hidden'); document.getElementById('add-goal').classList.remove('flex');" class="flex-1 py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-xl">Annuler</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:bg-primary-dark transition">Créer</button>
            </div>
        </form>
    </x-modal>
</x-app-layout>

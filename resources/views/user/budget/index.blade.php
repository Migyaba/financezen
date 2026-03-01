<x-app-layout>
    <x-slot name="header">Budget Mensuel</x-slot>

    <div class="space-y-6" x-data="{ activeTab: 'expenses' }" x-cloak>
        <!-- Month Navigator -->
        <div class="flex items-center justify-between bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
            <a href="{{ route('budget.index', ['month' => $month == 1 ? 12 : $month - 1, 'year' => $month == 1 ? $year - 1 : $year]) }}"
               class="p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700 transition"><i data-lucide="chevron-left" class="w-5 h-5 text-slate-500"></i></a>
            <h2 class="text-lg font-black text-slate-800 dark:text-white capitalize">{{ \Carbon\Carbon::create($year, $month)->translatedFormat('F Y') }}</h2>
            <a href="{{ route('budget.index', ['month' => $month == 12 ? 1 : $month + 1, 'year' => $month == 12 ? $year + 1 : $year]) }}"
               class="p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700 transition"><i data-lucide="chevron-right" class="w-5 h-5 text-slate-500"></i></a>
        </div>

        <!-- Onglets -->
        <div class="flex gap-4 border-b border-slate-200 dark:border-slate-700 px-2 overflow-x-auto">
            <button @click="activeTab = 'overview'" class="pb-3 text-sm font-bold border-b-2 transition whitespace-nowrap" :class="activeTab === 'overview' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-slate-300'">Vue d'ensemble</button>
            <button @click="activeTab = 'incomes'" class="pb-3 text-sm font-bold border-b-2 transition whitespace-nowrap" :class="activeTab === 'incomes' ? 'border-success text-success' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-slate-300'">Revenus</button>
            <button @click="activeTab = 'expenses'" class="pb-3 text-sm font-bold border-b-2 transition whitespace-nowrap" :class="activeTab === 'expenses' ? 'border-danger text-danger' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-slate-300'">Dépenses</button>
        </div>

        @php
            $budgetItems = collect($budget ? $budget->items : []);
            $groupedTransactions = $transactions->groupBy('category_id');
        @endphp

        <!-- VUE D'ENSEMBLE -->
        <div x-show="activeTab === 'overview'" x-transition class="space-y-6">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Revenus du mois</p>
                    <h4 class="text-2xl font-black text-success">{{ number_format($incomeTotal, 0, ',', ' ') }} FCFA</h4>
                </div>
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Dépenses du mois</p>
                    <h4 class="text-2xl font-black text-danger">{{ number_format($expenseTotal, 0, ',', ' ') }} FCFA</h4>
                </div>
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Solde Net</p>
                    <h4 class="text-2xl font-black {{ ($incomeTotal - $expenseTotal) >= 0 ? 'text-primary' : 'text-danger' }}">
                        {{ number_format($incomeTotal - $expenseTotal, 0, ',', ' ') }} FCFA
                    </h4>
                </div>
            </div>

            <!-- Global Planning -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden p-6">
                <h3 class="font-bold text-slate-800 dark:text-white mb-6">Planification Globale (Fixe / Freelance)</h3>
                <form action="{{ $budget ? route('budget.update', $budget) : route('budget.store') }}" method="POST">
                    @csrf @if($budget) @method('PUT') @endif
                    <input type="hidden" name="month" value="{{ $month }}">
                    <input type="hidden" name="year" value="{{ $year }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Salaire prévu</label><input type="number" name="salary_planned" value="{{ $budget->salary_planned ?? 0 }}" step="0.01" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-11 focus:ring-primary"></div>
                        <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Salaire réel</label><input type="number" name="salary_actual" value="{{ $budget->salary_actual ?? 0 }}" step="0.01" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-11 focus:ring-primary"></div>
                        <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Freelance prévu</label><input type="number" name="freelance_planned" value="{{ $budget->freelance_planned ?? 0 }}" step="0.01" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-11 focus:ring-primary"></div>
                        <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Freelance réel</label><input type="number" name="freelance_actual" value="{{ $budget->freelance_actual ?? 0 }}" step="0.01" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-11 focus:ring-primary"></div>
                    </div>
                    <div class="mt-6 flex justify-end"><button type="submit" class="px-6 py-2 bg-primary text-white font-bold rounded-xl text-sm">Sauvegarder</button></div>
                </form>
            </div>
        </div>

        <!-- REVENUS TAB -->
        <div x-show="activeTab === 'incomes'" x-transition class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 dark:bg-slate-900/50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase text-left w-1/3">Catégorie</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase text-right w-1/4">Montant Prévu</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase text-right w-1/4">Montant Réel</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase text-right">Écart</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach($categories->where('type', 'income') as $cat)
                        @php
                            $planned = $budgetItems->firstWhere('category_id', $cat->id)?->amount_planned ?? 0;
                            $actual = $groupedTransactions->has($cat->id) ? $groupedTransactions[$cat->id]->sum('amount') : 0;
                            $diff = $actual - $planned; // Positive diff is good for incomes
                        @endphp
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white" style="background: {{ $cat->color }}"><i data-lucide="{{ $cat->icon }}" class="w-4 h-4"></i></div>
                                    <span class="text-sm font-bold text-slate-800 dark:text-white">{{ $cat->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('budget.item.update') }}" method="POST" class="flex justify-end relative group">
                                    @csrf
                                    <input type="hidden" name="month" value="{{ $month }}">
                                    <input type="hidden" name="year" value="{{ $year }}">
                                    <input type="hidden" name="category_id" value="{{ $cat->id }}">
                                    <input type="number" name="amount_planned" value="{{ $planned }}" onchange="this.form.submit()" class="w-32 bg-transparent border-0 border-b border-transparent focus:border-success focus:ring-0 text-right font-bold p-1 text-sm text-slate-600 dark:text-slate-300 group-hover:bg-slate-100 dark:group-hover:bg-slate-900 rounded inline-block">
                                </form>
                            </td>
                            <td class="px-6 py-4 text-right font-black text-slate-800 dark:text-white">{{ number_format($actual, 0, ',', ' ') }}</td>
                            <td class="px-6 py-4 text-right font-bold text-sm {{ $diff >= 0 ? 'text-success' : 'text-danger' }}">{{ $diff > 0 ? '+' : '' }}{{ number_format($diff, 0, ',', ' ') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="text-[10px] text-slate-400">Modifier prévu inline</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- DÉPENSES TAB -->
        <div x-show="activeTab === 'expenses'" x-transition class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 dark:bg-slate-900/50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase text-left w-1/4">Catégorie</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase text-right w-1/5">Prévu</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase text-right w-1/5">Réel</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase text-right w-1/6">Écart</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase text-left min-w-[100px]">Progression</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach($categories->where('type', 'expense') as $cat)
                        @php
                            $planned = $budgetItems->firstWhere('category_id', $cat->id)?->amount_planned ?? 0;
                            $actual = $groupedTransactions->has($cat->id) ? $groupedTransactions[$cat->id]->sum('amount') : 0;
                            $diff = $planned - $actual; // Positive is good (saved money)
                            $pct = $planned > 0 ? round(($actual / $planned) * 100) : ($actual > 0 ? 100 : 0);
                            $pctColor = $pct > 100 ? 'bg-danger' : ($pct > 80 ? 'bg-warning' : 'bg-primary');
                        @endphp
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white flex-shrink-0" style="background: {{ $cat->color }}"><i data-lucide="{{ $cat->icon }}" class="w-4 h-4"></i></div>
                                    <span class="text-sm font-bold text-slate-800 dark:text-white truncate">{{ $cat->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('budget.item.update') }}" method="POST" class="flex justify-end relative group">
                                    @csrf
                                    <input type="hidden" name="month" value="{{ $month }}">
                                    <input type="hidden" name="year" value="{{ $year }}">
                                    <input type="hidden" name="category_id" value="{{ $cat->id }}">
                                    <input type="number" name="amount_planned" value="{{ $planned }}" onkeydown="if(event.key === 'Enter') this.form.submit();" onblur="this.form.submit()" class="w-full max-w-[120px] bg-transparent border border-transparent focus:border-danger hover:border-slate-300 dark:hover:border-slate-600 focus:ring-0 text-right font-bold p-1 px-2 text-sm text-slate-600 dark:text-slate-300 rounded transition duration-200">
                                </form>
                            </td>
                            <td class="px-6 py-4 text-right font-black text-slate-800 dark:text-white">{{ number_format($actual, 0, ',', ' ') }}</td>
                            <td class="px-6 py-4 text-right font-bold text-sm {{ $diff < 0 ? 'text-danger' : 'text-success' }}">{{ $diff >= 0 ? '+' : '' }}{{ number_format($diff, 0, ',', ' ') }}</td>
                            <td class="px-6 py-4">
                                <div class="w-full h-1.5 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                                    <div class="h-full {{ $pctColor }} rounded-full" style="width: {{ min($pct, 100) }}%"></div>
                                </div>
                                <div class="text-[10px] text-slate-400 mt-1 min-w-max">{{ $pct }}% consommé</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>

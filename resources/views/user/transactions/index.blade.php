<x-app-layout>
    <x-slot name="header">
        Transactions
    </x-slot>

    <div class="space-y-6">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Revenus du mois</p>
                <h4 class="text-xl font-bold text-success">{{ number_format($transactions->where('type', 'income')->sum('amount'), 0, ',', ' ') }} FCFA</h4>
            </div>
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Dépenses du mois</p>
                <h4 class="text-xl font-bold text-danger">{{ number_format($transactions->where('type', 'expense')->sum('amount'), 0, ',', ' ') }} FCFA</h4>
            </div>
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Solde Net</p>
                @php $net = $transactions->where('type', 'income')->sum('amount') - $transactions->where('type', 'expense')->sum('amount'); @endphp
                <h4 class="text-xl font-bold {{ $net >= 0 ? 'text-primary' : 'text-danger' }}">{{ number_format($net, 0, ',', ' ') }} FCFA</h4>
            </div>
        </div>

        <!-- Filters & Actions -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <form action="{{ route('transactions.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full">
                <div class="relative w-full sm:w-64">
                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" oninput="clearTimeout(this.t); this.t = setTimeout(() => this.form.submit(), 500);" placeholder="Rechercher..." class="w-full pl-10 text-sm rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-800 focus:ring-primary h-11">
                </div>
                
                <select name="type" onchange="this.form.submit()" class="w-full sm:w-auto text-sm rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-800 focus:ring-primary h-11">
                    <option value="">Tous les types</option>
                    <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Revenus</option>
                    <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Dépenses</option>
                </select>
                
                <select name="category_id" onchange="this.form.submit()" class="w-full sm:w-auto text-sm rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-800 focus:ring-primary h-11">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>

                <select name="month" onchange="this.form.submit()" class="w-full sm:w-auto text-sm rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-800 focus:ring-primary h-11">
                    <option value="">Tous les mois</option>
                    @foreach($transactionMonths as $month => $m)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::createFromFormat('Y-m', $m)->translatedFormat('F Y') }}</option>
                    @endforeach
                </select>

                <select name="payment_method" onchange="this.form.submit()" class="w-full sm:w-auto text-sm rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-800 focus:ring-primary h-11">
                    <option value="">Tous les modes</option>
                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Espèce</option>
                    <option value="mobile_money" {{ request('payment_method') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                    <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Carte</option>
                    <option value="transfer" {{ request('payment_method') == 'transfer' ? 'selected' : '' }}>Virement</option>
                </select>
                
                @if(request()->anyFilled(['search', 'type', 'category_id', 'month', 'payment_method']))
                    <a href="{{ route('transactions.index') }}" class="text-xs font-bold text-slate-400 hover:text-primary transition ml-auto border border-slate-200 dark:border-slate-700 px-3 py-2 rounded-lg">Réinitialiser</a>
                @endif
            </form>

            <div class="flex items-center gap-3 w-full md:w-auto">
                <a href="{{ route('transactions.export') }}" class="flex-1 md:flex-none px-4 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition flex items-center justify-center gap-2 text-sm border border-slate-200 dark:border-slate-600">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    Export CSV
                </a>
                <button @click="openModal('add-transaction')" class="flex-1 md:flex-none px-6 py-2.5 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:bg-primary-dark transition flex items-center justify-center gap-2 text-sm">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i>
                    Transaction
                </button>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-700">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Date</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Catégorie</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Description</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Montant</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Mode</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($transactions as $t)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition">
                        <td class="px-6 py-4">
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $t->transaction_date->format('d/m/Y') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white" style="background-color: {{ $t->category->color ?? '#94a3b8' }}">
                                    <i data-lucide="{{ $t->category->icon ?? 'tag' }}" class="w-4 h-4"></i>
                                </div>
                                <span class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $t->category->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-slate-500 dark:text-slate-400">{{ $t->description ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-black {{ $t->type == 'income' ? 'text-success' : 'text-danger' }}">
                                {{ $t->type == 'income' ? '+' : '-' }} {{ number_format($t->amount, 0, ',', ' ') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-md bg-slate-100 dark:bg-slate-700 text-[10px] font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">
                                {{ $t->payment_method }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button class="p-2 text-slate-400 hover:text-primary transition"><i data-lucide="edit-3" class="w-4 h-4"></i></button>
                                <form action="{{ route('transactions.destroy', $t) }}" method="POST" onsubmit="return confirm('Supprimer cette transaction ?')">
                                    @csrf @method('DELETE')
                                    <button class="p-2 text-slate-400 hover:text-danger transition"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-50 dark:bg-slate-900 rounded-full flex items-center justify-center text-slate-300 mb-4">
                                    <i data-lucide="inbox" class="w-8 h-8"></i>
                                </div>
                                <p class="text-slate-500 font-medium">Aucune transaction trouvée.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            @if($transactions->hasPages())
            <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-700">
                {{ $transactions->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Modals -->
    <x-modal id="add-transaction" title="Nouvelle Transaction">
        <form action="{{ route('transactions.store') }}" method="POST" class="space-y-5" x-data="{ cat: '', isRecurring: false }">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1 ml-1">Type</label>
                    <select name="type" required class="w-full rounded-xl border-slate-200 focus:ring-primary h-12">
                        <option value="expense">Dépense</option>
                        <option value="income">Revenu</option>
                        <option value="debt_payment">Remboursement Dette</option>
                        <option value="savings">Épargne</option>
                    </select>
                </div>
                
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1 ml-1">Catégorie</label>
                    <select name="category_id" x-model="cat" required class="w-full rounded-xl border-slate-200 focus:ring-primary h-12">
                        <option value="">Sélectionner...</option>
                        <option value="new" class="font-bold text-primary">+ Créer une nouvelle catégorie</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    
                    <div x-show="cat === 'new'" x-collapse class="mt-3">
                        <input type="text" name="new_category_name" placeholder="Nom de la nouvelle catégorie..." class="w-full rounded-xl border-primary focus:ring-primary h-12 bg-primary/5" x-bind:required="cat === 'new'">
                        <p class="text-[10px] text-slate-400 mt-1 ml-1 font-medium">Elle sera créée automatiquement pour ce type de transaction.</p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1 ml-1">Montant</label>
                    <input type="number" name="amount" required step="0.01" class="w-full rounded-xl border-slate-200 focus:ring-primary h-12 font-bold" placeholder="0.00">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1 ml-1">Date</label>
                    <input type="date" name="transaction_date" required value="{{ date('Y-m-d') }}" class="w-full rounded-xl border-slate-200 focus:ring-primary h-12">
                </div>

                <div class="col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1 ml-1">Description</label>
                    <input type="text" name="description" class="w-full rounded-xl border-slate-200 focus:ring-primary h-12" placeholder="Ex: Courses mensuelles">
                </div>

                <div class="col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1 ml-1">Mode de paiement</label>
                    <select name="payment_method" required class="w-full rounded-xl border-slate-200 focus:ring-primary h-12">
                        <option value="cash">Espèce</option>
                        <option value="mobile_money">Mobile Money</option>
                        <option value="card">Carte Bancaire</option>
                        <option value="transfer">Virement</option>
                        <option value="other">Autre</option>
                    </select>
                </div>
                
                <div class="col-span-2 p-4 bg-slate-50 rounded-xl border border-slate-100 dark:bg-slate-800 dark:border-slate-700">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_recurring" x-model="isRecurring" class="w-5 h-5 rounded-md border-slate-300 text-primary focus:ring-primary">
                        <div>
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-200">Transaction récurrente</span>
                            <p class="text-[10px] text-slate-500">Ex: Loyer, abonnement, salaire mensuel</p>
                        </div>
                    </label>

                    <div x-show="isRecurring" x-collapse class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-600">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1 ml-1">Fréquence</label>
                        <select name="recurring_frequency" class="w-full rounded-xl border-slate-200 focus:ring-primary h-12 bg-white" x-bind:required="isRecurring">
                            <option value="monthly">Mensuelle (tous les mois)</option>
                            <option value="weekly">Hebdomadaire (toutes les semaines)</option>
                            <option value="yearly">Annuelle (tous les ans)</option>
                            <option value="daily">Journalière (tous les jours)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="pt-4 flex gap-3">
                <button type="button" @click="closeModal()" class="flex-1 py-3 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition">Annuler</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:bg-primary-dark transition">Enregistrer</button>
            </div>
        </form>
    </x-modal>

    <script>
        window.openModal = function(id) {
            document.getElementById(id).classList.remove('hidden');
            document.getElementById(id).classList.add('flex');
        }
        window.closeModal = function() {
            document.querySelectorAll('[id^="add-"]').forEach(m => {
                m.classList.add('hidden');
                m.classList.remove('flex');
            });
        }
    </script>
</x-app-layout>

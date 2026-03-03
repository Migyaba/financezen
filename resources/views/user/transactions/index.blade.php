<x-app-layout>
    <x-slot name="header">
        Transactions
    </x-slot>

    <div class="space-y-4 sm:space-y-6" x-data="transactionManager()">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-6">
            <div class="bg-white dark:bg-slate-800 p-4 sm:p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                <p class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Revenus du mois</p>
                <h4 class="text-lg sm:text-xl font-bold text-success">{{ number_format($transactions->where('type', 'income')->sum('amount'), 0, ',', ' ') }} FCFA</h4>
            </div>
            <div class="bg-white dark:bg-slate-800 p-4 sm:p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                <p class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Dépenses du mois</p>
                <h4 class="text-lg sm:text-xl font-bold text-danger">{{ number_format($transactions->where('type', 'expense')->sum('amount'), 0, ',', ' ') }} FCFA</h4>
            </div>
            <div class="bg-white dark:bg-slate-800 p-4 sm:p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                <p class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Solde Net</p>
                @php $net = $transactions->where('type', 'income')->sum('amount') - $transactions->where('type', 'expense')->sum('amount'); @endphp
                <h4 class="text-lg sm:text-xl font-bold {{ $net >= 0 ? 'text-primary' : 'text-danger' }}">{{ number_format($net, 0, ',', ' ') }} FCFA</h4>
            </div>
        </div>

        <!-- Filters & Actions -->
        <div class="space-y-3 sm:space-y-4">
            <!-- Filters -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-3 sm:p-4">
                <form action="{{ route('transactions.index') }}" method="GET" class="space-y-3">
                    <!-- Search bar -->
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" oninput="clearTimeout(this.t); this.t = setTimeout(() => this.form.submit(), 500);" placeholder="Rechercher une transaction..." class="w-full pl-10 text-sm rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:ring-primary h-11">
                    </div>
                    
                    <!-- Filter selects - grid layout -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-3">
                        <select name="type" onchange="this.form.submit()" class="w-full text-sm rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:ring-primary h-10 sm:h-11">
                            <option value="">Tous les types</option>
                            <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Revenus</option>
                            <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Dépenses</option>
                        </select>
                        
                        <select name="category_id" onchange="this.form.submit()" class="w-full text-sm rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:ring-primary h-10 sm:h-11">
                            <option value="">Catégories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>

                        <select name="month" onchange="this.form.submit()" class="w-full text-sm rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:ring-primary h-10 sm:h-11">
                            <option value="">Tous les mois</option>
                            @foreach($transactionMonths as $month => $m)
                                <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::createFromFormat('Y-m', $m)->translatedFormat('F Y') }}</option>
                            @endforeach
                        </select>

                        <select name="payment_method" onchange="this.form.submit()" class="w-full text-sm rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:ring-primary h-10 sm:h-11">
                            <option value="">Tous les modes</option>
                            <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Espèce</option>
                            <option value="mobile_money" {{ request('payment_method') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                            <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Carte</option>
                            <option value="transfer" {{ request('payment_method') == 'transfer' ? 'selected' : '' }}>Virement</option>
                        </select>
                    </div>
                    
                    @if(request()->anyFilled(['search', 'type', 'category_id', 'month', 'payment_method']))
                        <div class="flex justify-end">
                            <a href="{{ route('transactions.index') }}" class="text-xs font-bold text-slate-400 hover:text-primary transition border border-slate-200 dark:border-slate-700 px-3 py-2 rounded-lg inline-flex items-center gap-1.5">
                                <i data-lucide="x" class="w-3 h-3"></i>
                                Réinitialiser
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2 sm:gap-3">
                <a href="{{ route('transactions.export') }}" class="flex-1 sm:flex-none px-3 sm:px-4 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition flex items-center justify-center gap-2 text-xs sm:text-sm border border-slate-200 dark:border-slate-600">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    <span class="hidden sm:inline">Export</span> CSV
                </a>
                <button @click="openModal('add-transaction')" class="flex-1 sm:flex-none px-4 sm:px-6 py-2.5 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:bg-primary-dark transition flex items-center justify-center gap-2 text-xs sm:text-sm">
                    <i data-lucide="plus-circle" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                    <span class="hidden xs:inline">Nouvelle</span> Transaction
                </button>
            </div>
        </div>

        <!-- Transactions Table/List -->
        <div>
            <!-- Desktop Table (hidden on mobile) -->
            <div class="hidden md:block bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left min-w-[700px]">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-700">
                                <th class="px-4 lg:px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Date</th>
                                <th class="px-4 lg:px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Catégorie</th>
                                <th class="px-4 lg:px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Description</th>
                                <th class="px-4 lg:px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Montant</th>
                                <th class="px-4 lg:px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Mode</th>
                                <th class="px-4 lg:px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @forelse($transactions as $t)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition">
                                <td class="px-4 lg:px-6 py-4">
                                    <span class="text-sm font-semibold text-slate-700 dark:text-slate-300 whitespace-nowrap">{{ $t->transaction_date->format('d/m/Y') }}</span>
                                </td>
                                <td class="px-4 lg:px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white flex-shrink-0" style="background-color: {{ $t->category->color ?? '#94a3b8' }}">
                                            <i data-lucide="{{ $t->category->icon ?? 'tag' }}" class="w-4 h-4"></i>
                                        </div>
                                        <span class="text-sm font-bold text-slate-800 dark:text-slate-200 truncate max-w-[120px] lg:max-w-none">{{ $t->category->name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 lg:px-6 py-4">
                                    <span class="text-sm text-slate-500 dark:text-slate-400 truncate block max-w-[150px] lg:max-w-[250px]">{{ $t->description ?? '-' }}</span>
                                </td>
                                <td class="px-4 lg:px-6 py-4">
                                    <span class="text-sm font-black whitespace-nowrap {{ $t->type == 'income' ? 'text-success' : 'text-danger' }}">
                                        {{ $t->type == 'income' ? '+' : '-' }} {{ number_format($t->amount, 0, ',', ' ') }}
                                    </span>
                                </td>
                                <td class="px-4 lg:px-6 py-4">
                                    <span class="px-2 py-1 rounded-md bg-slate-100 dark:bg-slate-700 text-[10px] font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 whitespace-nowrap">
                                        {{ $t->payment_method }}
                                    </span>
                                </td>
                                <td class="px-4 lg:px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1 text-slate-400">
                                        <button @click="editTransaction({{ json_encode([
                                            'id' => $t->id,
                                            'type' => $t->type,
                                            'category_id' => $t->category_id,
                                            'amount' => $t->amount,
                                            'description' => $t->description,
                                            'transaction_date' => $t->transaction_date->format('Y-m-d'),
                                            'payment_method' => $t->payment_method,
                                        ]) }})" class="p-2 hover:text-primary transition rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700" title="Modifier">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </button>
                                        <button @click="confirmDelete({{ $t->id }}, '{{ addslashes($t->category->name) }}', {{ $t->amount }})" class="p-2 hover:text-danger transition rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20" title="Supprimer">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center">
                                    <x-empty-state icon="inbox" title="Aucune transaction" description="Vos transactions s'afficheront ici." />
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($transactions->hasPages())
                <div class="px-4 sm:px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-700">
                    {{ $transactions->links() }}
                </div>
                @endif
            </div>

            <!-- Mobile Card List (shown on mobile) -->
            <div class="md:hidden space-y-3">
                @forelse($transactions as $t)
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 relative overflow-hidden">
                    <!-- Color accent bar -->
                    <div class="absolute left-0 top-0 bottom-0 w-1" style="background-color: {{ $t->category->color ?? '#94a3b8' }}"></div>
                    
                    <div class="p-4 pl-5">
                        <!-- Top row: category + amount -->
                        <div class="flex items-start justify-between gap-3 mb-2">
                            <div class="flex items-center gap-2.5 min-w-0 flex-1">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white flex-shrink-0 shadow-sm" style="background-color: {{ $t->category->color ?? '#94a3b8' }}">
                                    <i data-lucide="{{ $t->category->icon ?? 'tag' }}" class="w-4 h-4"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-sm font-bold text-slate-800 dark:text-white leading-tight truncate">{{ $t->category->name }}</h4>
                                    <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider mt-0.5">{{ $t->transaction_date->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-sm font-black {{ $t->type == 'income' ? 'text-success' : 'text-danger' }} whitespace-nowrap">
                                    {{ $t->type == 'income' ? '+' : '-' }}{{ number_format($t->amount, 0, ',', ' ') }}
                                </p>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">{{ $t->payment_method }}</span>
                            </div>
                        </div>

                        @if($t->description)
                        <p class="text-xs text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-900/50 p-2 rounded-lg mb-2 truncate">
                            {{ $t->description }}
                        </p>
                        @endif

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-1 pt-2 border-t border-slate-100 dark:border-slate-700/50">
                            <button @click="editTransaction({{ json_encode([
                                'id' => $t->id,
                                'type' => $t->type,
                                'category_id' => $t->category_id,
                                'amount' => $t->amount,
                                'description' => $t->description,
                                'transaction_date' => $t->transaction_date->format('Y-m-d'),
                                'payment_method' => $t->payment_method,
                            ]) }})" class="flex items-center gap-1 px-2.5 py-1.5 text-[10px] font-bold text-slate-500 hover:text-primary transition uppercase tracking-widest rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700">
                                <i data-lucide="edit-3" class="w-3 h-3"></i> Modifier
                            </button>
                            <button @click="confirmDelete({{ $t->id }}, '{{ addslashes($t->category->name) }}', {{ $t->amount }})" class="flex items-center gap-1 px-2.5 py-1.5 text-[10px] font-bold text-slate-400 hover:text-danger transition uppercase tracking-widest rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20">
                                <i data-lucide="trash-2" class="w-3 h-3"></i> Supprimer
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white dark:bg-slate-800 p-10 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 text-center">
                    <x-empty-state icon="inbox" title="Aucune transaction" description="Commencez par ajouter votre première transaction." />
                </div>
                @endforelse

                @if($transactions->hasPages())
                <div class="bg-white dark:bg-slate-800 p-3 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                    {{ $transactions->links() }}
                </div>
                @endif
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- MODAL: Ajouter une Transaction --}}
        {{-- ============================================ --}}
        <div id="add-transaction" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" @keydown.escape.window="closeModal()">
            <div class="bg-white dark:bg-slate-800 w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden max-h-[90vh] flex flex-col" @click.away="closeModal()">
                <!-- Header -->
                <div class="px-8 py-6 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/50 flex-shrink-0">
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">Nouvelle Transaction</h3>
                    <button @click="closeModal()" class="p-2 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                        <i data-lucide="x" class="w-5 h-5 text-slate-400"></i>
                    </button>
                </div>
                <!-- Body -->
                <div class="p-8 overflow-y-auto">
                    <form action="{{ route('transactions.store') }}" method="POST" class="space-y-5" x-data="{ cat: '{{ old('category_id') }}', isRecurring: {{ old('is_recurring') ? 'true' : 'false' }} }">
                        @csrf

                        @if ($errors->any())
                            <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/30 rounded-xl text-sm text-red-600 dark:text-red-400">
                                <p class="font-bold flex items-center gap-2 mb-2"><i data-lucide="alert-triangle" class="w-4 h-4"></i> Erreur d'enregistrement</p>
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Type</label>
                                <select name="type" required class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 font-medium focus:ring-primary focus:border-primary">
                                    <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>Dépense</option>
                                    <option value="income" {{ old('type') == 'income' ? 'selected' : '' }}>Revenu</option>
                                    <option value="debt_payment" {{ old('type') == 'debt_payment' ? 'selected' : '' }}>Remboursement Dette</option>
                                    <option value="savings" {{ old('type') == 'savings' ? 'selected' : '' }}>Épargne</option>
                                </select>
                            </div>
                            
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Catégorie</label>
                                <select name="category_id" x-model="cat" required class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 font-medium focus:ring-primary focus:border-primary">
                                    <option value="">Sélectionner...</option>
                                    <option value="new" class="font-bold text-primary">+ Créer une nouvelle catégorie</option>
                                    <optgroup label="── Dépenses ──">
                                        @foreach($categories->where('type', 'expense') as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="── Revenus ──">
                                        @foreach($categories->where('type', 'income') as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                                
                                <div x-cloak x-show="cat === 'new'" class="mt-3">
                                    <input type="text" name="new_category_name" value="{{ old('new_category_name') }}" placeholder="Nom de la nouvelle catégorie..." class="w-full rounded-xl border-primary dark:bg-slate-900 h-12 focus:ring-primary focus:border-primary" x-bind:required="cat === 'new'">
                                    <p class="text-[10px] text-slate-500 mt-1">Elle sera créée automatiquement.</p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Montant</label>
                                <input type="number" name="amount" value="{{ old('amount') }}" required step="0.01" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 font-bold focus:ring-primary focus:border-primary" placeholder="0.00">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Date</label>
                                <input type="date" name="transaction_date" required value="{{ old('transaction_date', date('Y-m-d')) }}" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 font-medium focus:ring-primary focus:border-primary">
                            </div>

                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Description</label>
                                <input type="text" name="description" value="{{ old('description') }}" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 font-medium focus:ring-primary focus:border-primary" placeholder="Ex: Courses mensuelles">
                            </div>

                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Mode de paiement</label>
                                <select name="payment_method" required class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 font-medium focus:ring-primary focus:border-primary">
                                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Espèce</option>
                                    <option value="mobile_money" {{ old('payment_method') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                                    <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Carte Bancaire</option>
                                    <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Virement</option>
                                    <option value="other" {{ old('payment_method') == 'other' ? 'selected' : '' }}>Autre</option>
                                </select>
                            </div>
                            
                            <div class="col-span-2 p-4 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-100 dark:border-slate-700">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="is_recurring" x-model="isRecurring" class="w-5 h-5 rounded-md border-slate-300 dark:border-slate-500 text-primary focus:ring-primary dark:bg-slate-800">
                                    <div>
                                        <span class="text-sm font-bold text-slate-800 dark:text-slate-200">Transaction récurrente</span>
                                        <p class="text-[10px] text-slate-500">Ex: Loyer, abonnement, salaire</p>
                                    </div>
                                </label>

                                <div x-cloak x-show="isRecurring" class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-600">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Fréquence</label>
                                    <select name="recurring_frequency" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 font-medium focus:ring-primary focus:border-primary" x-bind:required="isRecurring">
                                        <option value="monthly" {{ old('recurring_frequency') == 'monthly' ? 'selected' : '' }}>Mensuelle (tous les mois)</option>
                                        <option value="weekly" {{ old('recurring_frequency') == 'weekly' ? 'selected' : '' }}>Hebdomadaire (toutes les semaines)</option>
                                        <option value="yearly" {{ old('recurring_frequency') == 'yearly' ? 'selected' : '' }}>Annuelle (tous les ans)</option>
                                        <option value="daily" {{ old('recurring_frequency') == 'daily' ? 'selected' : '' }}>Journalière (tous les jours)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 flex gap-3">
                            <button type="button" @click="closeModal()" class="flex-1 py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition">Annuler</button>
                            <button type="submit" class="flex-1 py-3 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:bg-primary-dark transition">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- MODAL: Modifier une Transaction --}}
        {{-- ============================================ --}}
        <div x-show="showEditModal" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             @keydown.escape.window="showEditModal = false">
            <div class="bg-white dark:bg-slate-800 w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden max-h-[90vh] flex flex-col"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                 @click.away="showEditModal = false">
                <!-- Header -->
                <div class="px-8 py-6 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/50 flex-shrink-0">
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center">
                            <i data-lucide="edit-3" class="w-5 h-5 text-primary"></i>
                        </div>
                        Modifier la Transaction
                    </h3>
                    <button @click="showEditModal = false" class="p-2 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                        <i data-lucide="x" class="w-5 h-5 text-slate-400"></i>
                    </button>
                </div>
                <!-- Body -->
                <div class="p-8 overflow-y-auto">
                    <form :action="'/transactions/' + editData.id" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Type</label>
                                <select name="type" x-model="editData.type" required class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 font-medium focus:ring-primary focus:border-primary">
                                    <option value="expense">Dépense</option>
                                    <option value="income">Revenu</option>
                                    <option value="debt_payment">Remboursement Dette</option>
                                    <option value="savings">Épargne</option>
                                </select>
                            </div>
                            
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Catégorie</label>
                                <select name="category_id" x-model="editData.category_id" required class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 font-medium focus:ring-primary focus:border-primary">
                                    <optgroup label="── Dépenses ──">
                                        @foreach($categories->where('type', 'expense') as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="── Revenus ──">
                                        @foreach($categories->where('type', 'income') as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Montant</label>
                                <input type="number" name="amount" x-model="editData.amount" required step="0.01" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 font-bold focus:ring-primary focus:border-primary" placeholder="0.00">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Date</label>
                                <input type="date" name="transaction_date" x-model="editData.transaction_date" required class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 font-medium focus:ring-primary focus:border-primary">
                            </div>

                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Description</label>
                                <input type="text" name="description" x-model="editData.description" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 font-medium focus:ring-primary focus:border-primary" placeholder="Ex: Courses mensuelles">
                            </div>

                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Mode de paiement</label>
                                <select name="payment_method" x-model="editData.payment_method" required class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 font-medium focus:ring-primary focus:border-primary">
                                    <option value="cash">Espèce</option>
                                    <option value="mobile_money">Mobile Money</option>
                                    <option value="card">Carte Bancaire</option>
                                    <option value="transfer">Virement</option>
                                    <option value="other">Autre</option>
                                </select>
                            </div>
                        </div>

                        <div class="pt-4 flex gap-3">
                            <button type="button" @click="showEditModal = false" class="flex-1 py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition">Annuler</button>
                            <button type="submit" class="flex-1 py-3 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:bg-primary-dark transition flex items-center justify-center gap-2">
                                <i data-lucide="check" class="w-4 h-4"></i>
                                Sauvegarder
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- MODAL: Confirmation de Suppression --}}
        {{-- ============================================ --}}
        <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             @keydown.escape.window="showDeleteModal = false">
            <div class="bg-white dark:bg-slate-800 w-full max-w-md rounded-3xl shadow-2xl overflow-hidden"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
                 @click.away="showDeleteModal = false">
                
                <div class="p-8 text-center">
                    <!-- Icon -->
                    <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-5">
                        <i data-lucide="alert-triangle" class="w-8 h-8 text-red-500"></i>
                    </div>

                    <!-- Title -->
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Supprimer cette transaction ?</h3>
                    
                    <!-- Details -->
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-2">Vous êtes sur le point de supprimer :</p>
                    <div class="bg-slate-50 dark:bg-slate-900/50 rounded-xl p-4 mb-6 border border-slate-100 dark:border-slate-700">
                        <p class="text-sm font-bold text-slate-800 dark:text-white" x-text="deleteData.categoryName"></p>
                        <p class="text-lg font-black text-danger mt-1" x-text="Number(deleteData.amount).toLocaleString('fr-FR') + ' FCFA'"></p>
                    </div>

                    <p class="text-xs text-slate-400 mb-6">Cette action est <span class="font-bold text-red-500">irréversible</span>. La transaction sera définitivement supprimée.</p>

                    <!-- Actions -->
                    <div class="flex gap-3">
                        <button @click="showDeleteModal = false" class="flex-1 py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition">
                            Non, annuler
                        </button>
                        <form :action="'/transactions/' + deleteData.id" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full py-3 bg-red-500 text-white font-bold rounded-xl shadow-lg shadow-red-500/20 hover:bg-red-600 transition flex items-center justify-center gap-2">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                Oui, supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script defer>
        function transactionManager() {
            return {
                // Edit modal
                showEditModal: false,
                editData: {
                    id: null,
                    type: 'expense',
                    category_id: '',
                    amount: 0,
                    description: '',
                    transaction_date: '',
                    payment_method: 'cash',
                },

                // Delete modal
                showDeleteModal: false,
                deleteData: {
                    id: null,
                    categoryName: '',
                    amount: 0,
                },

                editTransaction(data) {
                    this.editData = { ...data };
                    // Convert category_id to string for x-model select binding
                    this.editData.category_id = String(data.category_id);
                    this.showEditModal = true;
                    this.$nextTick(() => lucide.createIcons());
                },

                confirmDelete(id, categoryName, amount) {
                    this.deleteData = { id, categoryName, amount };
                    this.showDeleteModal = true;
                    this.$nextTick(() => lucide.createIcons());
                },

                openModal(id) {
                    const modal = document.getElementById(id);
                    if (modal) {
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    }
                },

                closeModal() {
                    document.querySelectorAll('#add-transaction').forEach(m => {
                        m.classList.add('hidden');
                        m.classList.remove('flex');
                    });
                },
            }
        }

        @if ($errors->any())
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('add-transaction');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        });
        @endif
    </script>
</x-app-layout>

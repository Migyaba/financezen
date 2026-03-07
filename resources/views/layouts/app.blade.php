<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="themeManager()" :class="{ 'dark': isDark }" x-cloak>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="FinanceZen — Gérez votre budget, suivez vos dépenses, remboursez vos dettes et épargnez intelligemment.">

        <title>{{ config('app.name', 'FinanceZen') }} — @yield('title', $header ?? 'App')</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
        <link rel="alternate icon" href="{{ asset('favicon.ico') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://unpkg.com/lucide@latest"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            function themeManager() {
                return {
                    isDark: localStorage.getItem('theme') === 'dark' ||
                            (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches),
                    sidebarOpen: window.innerWidth >= 1024,
                    toggle() {
                        this.isDark = !this.isDark;
                        localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
                        document.dispatchEvent(new CustomEvent('theme-changed', { detail: { isDark: this.isDark } }));
                    }
                }
            }
        </script>
    </head>
    <body class="font-sans antialiased bg-light dark:bg-slate-900 text-dark dark:text-slate-100 h-screen flex overflow-hidden">
        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" 
             class="fixed inset-0 bg-black/50 z-30 lg:hidden"
             x-transition:enter="transition-opacity ease-out duration-300"
             x-transition:leave="transition-opacity ease-in duration-200"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed lg:static inset-y-0 left-0 z-40 w-64 bg-slate-800 dark:bg-slate-950 text-slate-300 flex-shrink-0 flex flex-col transition-transform duration-300 ease-in-out">
            <!-- Logo -->
            <div class="p-6 flex items-center gap-3">
                <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center text-white shadow-lg">
                    <i data-lucide="trending-up"></i>
                </div>
                <span class="text-xl font-bold text-white tracking-tight">FinanceZen</span>
                <button @click="sidebarOpen = false" class="lg:hidden ml-auto p-1 rounded-lg hover:bg-slate-700 transition">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 space-y-2 mt-4 overflow-y-auto">
                <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="layout-dashboard">Dashboard</x-nav-link>
                <x-nav-link href="{{ route('budget.index') }}" :active="request()->routeIs('budget.*')" icon="pie-chart">Budget</x-nav-link>
                <x-nav-link href="{{ route('transactions.index') }}" :active="request()->routeIs('transactions.*')" icon="arrow-left-right">Transactions</x-nav-link>
                <x-nav-link href="{{ route('debts.index') }}" :active="request()->routeIs('debts.*')" icon="credit-card">Dettes</x-nav-link>
                <x-nav-link href="{{ route('savings.index') }}" :active="request()->routeIs('savings.*')" icon="gem">Épargne</x-nav-link>
                <x-nav-link href="{{ route('reports.index') }}" :active="request()->routeIs('reports.*')" icon="bar-chart-3">Rapports</x-nav-link>

                <div class="pt-4 mt-4 border-t border-slate-700/50">
                    <x-nav-link href="{{ route('subscription.index') }}" :active="request()->routeIs('subscription.*')" icon="sparkles">Abonnement</x-nav-link>
                    <x-nav-link href="{{ route('profile.edit') }}" :active="request()->routeIs('profile.*')" icon="user">Profil</x-nav-link>
                </div>

                @if(auth()->check() && auth()->user()->isAdmin())
                <div class="pt-4 mt-4 border-t border-slate-700/50">
                    <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Administration</p>
                    <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.*')" icon="shield">Panel Admin</x-nav-link>
                </div>
                @endif
            </nav>

            <!-- User Info Bottom -->
            <div class="p-4 border-t border-slate-700/50 mt-auto">
                <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-slate-700/50 transition cursor-pointer">
                    <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=4F46E5&color=fff' }}" 
                         class="w-10 h-10 rounded-full border-2 border-slate-600" alt="Avatar">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-400 truncate tracking-wide">
                            @if(auth()->user()->isAdmin()) Admin
                            @elseif(auth()->user()->trial_ends_at?->isFuture()) Essai gratuit
                            @else Utilisateur
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="h-16 bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between px-4 sm:px-8">
                <div class="flex items-center gap-3">
                    <!-- Mobile hamburger -->
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                        <i data-lucide="menu" class="w-5 h-5 text-slate-500"></i>
                    </button>
                    <h1 class="text-lg font-bold text-slate-800 dark:text-white">{{ $header ?? '' }}</h1>
                </div>

                <div class="flex items-center gap-2 sm:gap-4">
                    <!-- Theme Toggle -->
                    <button @click="toggle()" class="p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-500 dark:text-slate-400 transition shadow-sm border border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800">
                        <i data-lucide="sun" x-show="isDark" class="w-5 h-5 text-yellow-500"></i>
                        <i data-lucide="moon" x-show="!isDark" class="w-5 h-5"></i>
                    </button>

                    <!-- Notifications -->
                    <button class="p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-500 dark:text-slate-400 transition relative shadow-sm border border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                    </button>

                    <div class="h-8 w-px bg-slate-200 dark:bg-slate-700 mx-1 hidden sm:block"></div>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-red-500 dark:hover:text-red-400 transition">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                            <span class="hidden sm:inline">Quitter</span>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-8 bg-light dark:bg-slate-900">
                <div class="min-h-[calc(100vh-14rem)]">
                    {{ $slot }}
                </div>

                <!-- Non-pinned Minimalist Footer -->
                <footer class="mt-12 py-6 border-t border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row items-center justify-between gap-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    <div>
                    © {{ date('Y') }} FinanceZen • Simplifiez votre prospérité
                    </div>
                    <div class="flex items-center gap-6">
                        <a href="{{ route('legal') }}" class="hover:text-primary transition-colors">Légal</a>
                        <a href="{{ route('privacy') }}" class="hover:text-primary transition-colors">Vie privée</a>
                        <a href="{{ route('contact') }}" class="hover:text-primary transition-colors">Aide</a>
                        <span class="text-slate-300 dark:text-slate-600 hidden sm:inline">v1.2.0</span>
                    </div>
                </footer>
            </main>
        </div>

        <!-- Toast Notifications -->
        <x-toast />

        {{-- ============================================ --}}
        {{-- FAB: Bouton flottant Ajout Rapide --}}
        {{-- ============================================ --}}
        @auth
        <div x-data="quickTransaction()" @open-quick-add.window="showQuickAdd = true" x-init="@if($errors->quick_transaction->any()) showQuickAdd = true @endif" x-cloak>
            <!-- FAB Button -->
            <div class="fixed bottom-6 right-6 z-50 flex items-center gap-3" x-data="{ showLabel: false }">
                <!-- Tooltip label -->
                <div x-show="showLabel" 
                     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-2" x-transition:enter-end="opacity-100 translate-x-0"
                     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-2"
                     class="bg-slate-800 dark:bg-slate-700 text-white text-xs font-bold py-2 px-4 rounded-xl shadow-lg whitespace-nowrap">
                    Nouvelle Transaction
                </div>

                <!-- Button -->
                <button @click="showQuickAdd = true" 
                        @mouseenter="showLabel = true" @mouseleave="showLabel = false"
                        class="group w-14 h-14 bg-primary rounded-2xl shadow-xl shadow-primary/30 hover:shadow-primary/50 hover:scale-110 active:scale-95 transition-all duration-300 flex items-center justify-center text-white relative overflow-hidden">
                    <!-- Pulse ring -->
                    <span class="absolute inset-0 rounded-2xl bg-primary animate-ping opacity-20"></span>
                    <!-- Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 relative z-10 transition-transform duration-300 group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14" />
                    </svg>
                </button>
            </div>

            <!-- Quick Add Transaction Modal -->
            <div x-show="showQuickAdd" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 @keydown.escape.window="showQuickAdd = false">
                <div class="bg-white dark:bg-slate-800 w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden max-h-[90vh] flex flex-col"
                     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                     @click.away="showQuickAdd = false">
                    <!-- Header -->
                    <div class="px-6 sm:px-8 py-5 sm:py-6 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between bg-gradient-to-r from-primary/5 to-transparent dark:from-primary/10 flex-shrink-0">
                        <h3 class="text-lg sm:text-xl font-bold text-slate-800 dark:text-white flex items-center gap-3">
                            <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center shadow-lg shadow-primary/20">
                                <i data-lucide="plus" class="w-5 h-5 text-white"></i>
                            </div>
                            Nouvelle Transaction
                        </h3>
                        <button @click="showQuickAdd = false" class="p-2 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                            <i data-lucide="x" class="w-5 h-5 text-slate-400"></i>
                        </button>
                    </div>
                    <!-- Body -->
                    <div class="p-6 sm:p-8 overflow-y-auto">
                        <form action="{{ route('transactions.store') }}" method="POST" class="space-y-4 sm:space-y-5">
                            @csrf

                            @if ($errors->quick_transaction->any())
                                <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/30 rounded-2xl text-xs text-red-600 dark:text-red-400">
                                    <p class="font-bold flex items-center gap-2 mb-1"><i data-lucide="alert-triangle" class="w-4 h-4"></i> Erreur d'enregistrement</p>
                                    <ul class="list-disc list-inside opacity-80">
                                        @foreach ($errors->quick_transaction->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="grid grid-cols-2 gap-3 sm:gap-4">
                                <div class="col-span-2">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Type</label>
                                    <select name="type" required class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 font-medium focus:ring-primary focus:border-primary">
                                        <option value="expense">Dépense</option>
                                        <option value="income">Revenu</option>
                                    </select>
                                </div>
                                
                                <div class="col-span-2">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Catégorie</label>
                                    <select name="category_id" x-model="quickCat" required class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 font-medium focus:ring-primary focus:border-primary">
                                        <option value="">Sélectionner...</option>
                                        <option value="new" class="font-bold text-primary">+ Créer une nouvelle catégorie</option>
                                        @isset($globalCategories)
                                        <optgroup label="── Dépenses ──">
                                            @foreach($globalCategories->where('type', 'expense')->whereNotIn('name', ['Remboursement Dettes', 'Épargne Objectifs']) as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </optgroup>
                                        <optgroup label="── Revenus ──">
                                            @foreach($globalCategories->where('type', 'income') as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </optgroup>
                                        @endisset
                                    </select>
                                    
                                    <div x-cloak x-show="quickCat === 'new'" class="mt-3">
                                        <input type="text" name="new_category_name" placeholder="Nom de la nouvelle catégorie..." class="w-full rounded-xl border-primary dark:bg-slate-900 h-12 focus:ring-primary focus:border-primary" x-bind:required="quickCat === 'new'">
                                        <p class="text-[10px] text-slate-500 mt-1">Elle sera créée automatiquement.</p>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Montant</label>
                                    <input type="number" name="amount" required step="0.01" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 font-bold focus:ring-primary focus:border-primary" placeholder="0.00">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Date</label>
                                    <input type="date" name="transaction_date" required value="{{ date('Y-m-d') }}" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 font-medium focus:ring-primary focus:border-primary">
                                </div>

                                <div class="col-span-2">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Description</label>
                                    <input type="text" name="description" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 font-medium focus:ring-primary focus:border-primary" placeholder="Ex: Courses mensuelles">
                                </div>

                                <div class="col-span-2">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Mode de paiement</label>
                                    <select name="payment_method" required class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 font-medium focus:ring-primary focus:border-primary">
                                        <option value="cash">Espèce</option>
                                        <option value="mobile_money">Mobile Money</option>
                                        <option value="card">Carte Bancaire</option>
                                        <option value="transfer">Virement</option>
                                        <option value="other">Autre</option>
                                    </select>
                                </div>
                                
                                <div class="col-span-2 p-3 sm:p-4 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-100 dark:border-slate-700">
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" name="is_recurring" x-model="quickRecurring" class="w-5 h-5 rounded-md border-slate-300 dark:border-slate-500 text-primary focus:ring-primary dark:bg-slate-800">
                                        <div>
                                            <span class="text-sm font-bold text-slate-800 dark:text-slate-200">Récurrente</span>
                                            <p class="text-[10px] text-slate-500">Loyer, abonnement, salaire...</p>
                                        </div>
                                    </label>

                                    <div x-cloak x-show="quickRecurring" class="mt-3 pt-3 border-t border-slate-200 dark:border-slate-600">
                                        <select name="recurring_frequency" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-11 text-sm font-medium focus:ring-primary focus:border-primary" x-bind:required="quickRecurring">
                                            <option value="monthly">Mensuelle</option>
                                            <option value="weekly">Hebdomadaire</option>
                                            <option value="yearly">Annuelle</option>
                                            <option value="daily">Journalière</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-3 sm:pt-4 flex gap-3">
                                <button type="button" @click="showQuickAdd = false" class="flex-1 py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition text-sm">Annuler</button>
                                <button type="submit" class="flex-1 py-3 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:bg-primary-dark transition flex items-center justify-center gap-2 text-sm">
                                    <i data-lucide="check" class="w-4 h-4"></i>
                                    Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endauth

        <script>
            // FAB Quick Transaction Manager
            function quickTransaction() {
                return {
                    showQuickAdd: false,
                    quickCat: '',
                    quickRecurring: false,
                }
            }

            lucide.createIcons();
            document.addEventListener('theme-changed', () => lucide.createIcons());
            // Re-render icons after Alpine updates
            document.addEventListener('alpine:initialized', () => lucide.createIcons());
        </script>
        @stack('scripts')
    </body>
</html>

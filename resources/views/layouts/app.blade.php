<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="themeManager()" :class="{ 'dark': isDark }" x-cloak>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="FinanceZen — Gérez votre budget, suivez vos dépenses, remboursez vos dettes et épargnez intelligemment.">

        <title>{{ config('app.name', 'FinanceZen') }} — @yield('title', $header ?? 'App')</title>

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
                {{ $slot }}
            </main>
        </div>

        <!-- Toast Notifications -->
        <x-toast />

        <script>
            lucide.createIcons();
            document.addEventListener('theme-changed', () => lucide.createIcons());
            // Re-render icons after Alpine updates
            document.addEventListener('alpine:initialized', () => lucide.createIcons());
        </script>
    </body>
</html>

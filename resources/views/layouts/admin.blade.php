<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="themeManager()" :class="{ 'dark': isDark }" x-cloak>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Admin — {{ config('app.name', 'FinanceZen') }}</title>
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
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-30 lg:hidden"></div>

        <!-- Admin Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed lg:static inset-y-0 left-0 z-40 w-64 bg-slate-900 dark:bg-black text-slate-300 flex-shrink-0 flex flex-col transition-transform duration-300">
            <div class="p-6 flex items-center gap-3">
                <div class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center text-white shadow-lg">
                    <i data-lucide="shield"></i>
                </div>
                <span class="text-xl font-bold text-white tracking-tight">Admin Panel</span>
            </div>

            <nav class="flex-1 px-4 space-y-2 mt-4 overflow-y-auto">
                <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')" icon="layout-dashboard">Dashboard</x-nav-link>
                <x-nav-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')" icon="users">Utilisateurs</x-nav-link>
                <x-nav-link href="{{ route('admin.subscriptions.index') }}" :active="request()->routeIs('admin.subscriptions.*')" icon="credit-card">Abonnements</x-nav-link>
                <x-nav-link href="{{ route('admin.reports') }}" :active="request()->routeIs('admin.reports')" icon="bar-chart-3">Rapports</x-nav-link>
                <x-nav-link href="{{ route('admin.settings.index') }}" :active="request()->routeIs('admin.settings.*')" icon="settings">Paramètres</x-nav-link>

                <div class="pt-4 mt-4 border-t border-slate-700/50">
                    <x-nav-link href="{{ route('dashboard') }}" icon="arrow-left">Retour App</x-nav-link>
                </div>
            </nav>

            <div class="p-4 border-t border-slate-700/50 mt-auto">
                <div class="flex items-center gap-3 p-2">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=EF4444&color=fff" class="w-10 h-10 rounded-full" alt="">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-red-400 uppercase tracking-widest font-bold">Super Admin</p>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="h-16 bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between px-4 sm:px-8">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                        <i data-lucide="menu" class="w-5 h-5 text-slate-500"></i>
                    </button>
                    <h1 class="text-lg font-bold text-slate-800 dark:text-white">{{ $header ?? 'Administration' }}</h1>
                </div>
                <div class="flex items-center gap-4">
                    <button @click="toggle()" class="p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                        <i data-lucide="sun" x-show="isDark" class="w-5 h-5 text-yellow-500"></i>
                        <i data-lucide="moon" x-show="!isDark" class="w-5 h-5 text-slate-500"></i>
                    </button>
                    <form method="POST" action="{{ route('logout') }}">@csrf
                        <button type="submit" class="text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-red-500 transition flex items-center gap-2">
                            <i data-lucide="log-out" class="w-4 h-4"></i> Quitter
                        </button>
                    </form>
                </div>
            </header>
            <main class="flex-1 overflow-y-auto p-4 sm:p-8 bg-light dark:bg-slate-900">
                {{ $slot }}
            </main>
        </div>

        <x-toast />
        <script>lucide.createIcons(); document.addEventListener('theme-changed', () => lucide.createIcons());</script>
    </body>
</html>

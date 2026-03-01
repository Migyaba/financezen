<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'FinanceZen' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800 flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav x-data="{ mobileMenuOpen: false }" class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="{{ route('welcome') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 bg-gradient-to-br from-primary to-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-primary/30 group-hover:scale-105 transition-transform duration-300">
                    <i data-lucide="trending-up" class="w-5 h-5"></i>
                </div>
                <span class="text-xl font-black tracking-tight text-slate-900">FinanceZen</span>
            </a>

            <div class="hidden md:flex items-center gap-6">
                <a href="{{ route('welcome') }}#features" class="text-sm font-bold text-slate-600 hover:text-primary transition-colors">Fonctionnalités</a>
                <a href="{{ route('welcome') }}#pricing" class="text-sm font-bold text-slate-600 hover:text-primary transition-colors">Tarifs</a>
                <a href="{{ route('contact') }}" class="text-sm font-bold text-slate-600 hover:text-primary transition-colors">Contact</a>
            </div>

            <div class="hidden md:flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-primary text-white text-sm font-bold rounded-xl shadow-lg shadow-primary/20 hover:bg-primary-dark transition-all flex items-center gap-2">
                        Mon Espace <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 hover:text-primary transition-colors">Connexion</a>
                    <a href="{{ route('register') }}" class="px-5 py-2.5 bg-slate-900 text-white text-sm font-bold rounded-xl shadow-xl hover:bg-slate-800 transition-all flex items-center gap-2">
                        Démarrer <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                @endauth
            </div>

            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-slate-600">
                <i data-lucide="menu" class="w-6 h-6" x-show="!mobileMenuOpen"></i>
                <i data-lucide="x" class="w-6 h-6" x-show="mobileMenuOpen" x-cloak></i>
            </button>
        </div>

        <div x-show="mobileMenuOpen" x-transition class="md:hidden border-t border-slate-100 bg-white" x-cloak>
            <div class="px-4 py-6 space-y-4 flex flex-col">
                <a href="{{ route('welcome') }}#features" @click="mobileMenuOpen=false" class="text-base font-bold text-slate-700">Fonctionnalités</a>
                <a href="{{ route('welcome') }}#pricing" @click="mobileMenuOpen=false" class="text-base font-bold text-slate-700">Tarifs</a>
                <a href="{{ route('contact') }}" class="text-base font-bold text-slate-700">Contact</a>
                <hr class="border-slate-100">
                @auth
                    <a href="{{ route('dashboard') }}" class="w-full text-center px-5 py-3 bg-primary text-white text-sm font-bold rounded-xl">Mon Espace</a>
                @else
                    <a href="{{ route('login') }}" class="w-full text-center px-5 py-3 bg-slate-100 text-slate-800 text-sm font-bold rounded-xl">Connexion</a>
                    <a href="{{ route('register') }}" class="w-full text-center px-5 py-3 bg-slate-900 text-white text-sm font-bold rounded-xl">Créer un compte</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main class="flex-1">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 pt-16 pb-8 px-4 sm:px-6">
        <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-12 mb-12">
            <div>
                <a href="{{ route('welcome') }}" class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-slate-900 rounded-lg flex items-center justify-center text-white">
                        <i data-lucide="trending-up" class="w-5 h-5"></i>
                    </div>
                    <span class="text-lg font-black text-slate-900">FinanceZen</span>
                </a>
                <p class="text-xs text-slate-500 leading-relaxed">Conçu pour simplifier l'organisation budgétaire personnelle et maximiser vos économies au quotidien.</p>
            </div>
            <div>
                <h4 class="text-slate-900 font-black mb-4 uppercase text-[10px] tracking-widest">Produit</h4>
                <ul class="space-y-3 text-sm font-medium">
                    <li><a href="{{ route('welcome') }}#features" class="text-slate-500 hover:text-primary transition">Avantages</a></li>
                    <li><a href="{{ route('welcome') }}#how-it-works" class="text-slate-500 hover:text-primary transition">Méthode</a></li>
                    <li><a href="{{ route('welcome') }}#pricing" class="text-slate-500 hover:text-primary transition">Prix</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-slate-900 font-black mb-4 uppercase text-[10px] tracking-widest">Légal</h4>
                <ul class="space-y-3 text-sm font-medium">
                    <li><a href="{{ route('legal') }}" class="text-slate-500 hover:text-primary transition">Mentions Légales</a></li>
                    <li><a href="{{ route('privacy') }}" class="text-slate-500 hover:text-primary transition">Confidentialité</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-slate-900 font-black mb-4 uppercase text-[10px] tracking-widest">Contact</h4>
                <ul class="space-y-3 text-sm font-medium">
                    <li><a href="{{ route('contact') }}" class="text-slate-500 hover:text-primary transition">Support</a></li>
                    <li><a href="{{ route('login') }}" class="text-slate-500 hover:text-primary transition">Espace client</a></li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto pt-8 border-t border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-xs font-bold text-slate-400">© {{ date('Y') }} FinanceZen. Tous droits réservés.</p>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                Fait par <a href="https://miguelmissetcho.com" class="hover:text-primary transition">Miguel M.</a> pour votre sérénité
            </p>
        </div>
    </footer>

    <script>lucide.createIcons();</script>
</body>
</html>

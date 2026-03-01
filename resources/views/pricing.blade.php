<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Tarifs — FinanceZen</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://unpkg.com/lucide@latest"></script>
    </head>
    <body class="font-sans antialiased text-slate-800 bg-slate-50 relative selection:bg-primary selection:text-white">

        <!-- Navigation -->
        <nav class="fixed w-full z-50 bg-white/80 backdrop-blur-md border-b border-slate-100 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center text-white shadow-lg shadow-primary/30">
                            <i data-lucide="wallet" class="w-6 h-6"></i>
                        </div>
                        <span class="text-2xl font-black tracking-tight text-slate-900">Finance<span class="text-primary">Zen</span></span>
                    </div>

                    <div class="hidden md:flex items-center space-x-8">
                        <a href="{{ route('welcome') }}" class="font-bold text-sm text-slate-600 hover:text-primary transition">Accueil</a>
                        <a href="{{ route('pricing') }}" class="font-bold text-sm text-primary transition">Tarifs</a>
                    </div>

                    <div class="flex items-center gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="font-bold text-sm text-slate-600 hover:text-primary transition">Tableau de bord</a>
                        @else
                            <a href="{{ route('login') }}" class="font-bold text-sm text-slate-600 hover:text-primary transition">Connexion</a>
                            <a href="{{ route('register') }}" class="px-6 py-2.5 bg-primary text-white font-bold text-sm rounded-xl shadow-lg shadow-primary/30 hover:bg-primary-dark transition hover:-translate-y-0.5">Démarrer l'essai</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <section class="pt-32 pb-24 relative overflow-hidden min-h-screen flex items-center">
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 mix-blend-overlay pointer-events-none"></div>
            <div class="absolute top-0 right-0 -mr-40 -mt-40 w-96 h-96 rounded-full bg-primary/10 blur-3xl"></div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 text-primary font-bold text-sm mb-6 border border-primary/20">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                        </span>
                        Simple et transparent
                    </div>
                    <h1 class="text-5xl md:text-6xl font-black text-slate-900 mb-6 tracking-tight">Un prix unique, <span class="bg-clip-text text-transparent bg-gradient-to-r from-primary to-indigo-600">zéro surprise</span></h1>
                    <p class="text-xl text-slate-600 leading-relaxed font-medium">Reprenez le contrôle de vos finances avec un accès complet à toutes les fonctionnalités.</p>
                </div>

                <div class="max-w-md mx-auto">
                    <div class="bg-white rounded-3xl shadow-2xl p-8 border border-slate-100 relative overflow-hidden group hover:border-primary/50 transition-colors duration-500">
                        <div class="absolute top-0 right-0 bg-primary text-white text-xs font-bold px-4 py-1.5 rounded-bl-xl z-10">POPULAIRE</div>
                        
                        <div class="text-center mb-8">
                            <h3 class="text-2xl font-black text-slate-900 mb-2">Premium</h3>
                            <div class="flex items-center justify-center gap-1">
                                <span class="text-5xl font-black text-slate-900">1 000</span>
                                <span class="text-xl font-bold text-slate-400 mt-3 flex items-center gap-1">
                                    FCFA <span class="text-sm">/ mois</span>
                                </span>
                            </div>
                            <p class="text-sm text-slate-500 mt-3 font-medium">L'équivalent d'un café par mois.</p>
                        </div>

                        <ul class="space-y-4 mb-8">
                            @php
                                $features = [
                                    "Gestion de budgets illimitée",
                                    "Suivi détaillé des transactions",
                                    "Gestion des dettes complexes",
                                    "Objectifs d'épargne avec progression",
                                    "Analyses et rapports complets",
                                    "Support prioritaire"
                                ];
                            @endphp
                            @foreach($features as $f)
                            <li class="flex items-start gap-3">
                                <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i data-lucide="check" class="w-4 h-4 text-success"></i>
                                </div>
                                <span class="font-bold text-slate-700">{{ $f }}</span>
                            </li>
                            @endforeach
                        </ul>

                        <div class="mt-8 bg-slate-50 rounded-2xl p-4 mb-8 border border-slate-100 text-center">
                            <span class="text-sm font-bold text-slate-600 block mb-2"><i data-lucide="gift" class="w-4 h-4 inline-block -mt-1 text-primary mr-1"></i> Bonus d'inscription</span>
                            <span class="text-slate-800 font-black">7 jours d'essai offerts</span>
                        </div>

                        <a href="{{ route('register') }}" class="block w-full text-center px-8 py-4 bg-primary text-white font-black text-lg rounded-2xl shadow-xl shadow-primary/30 hover:bg-primary-dark transition hover:-translate-y-1">Démarrer maintenant</a>
                        <p class="text-center text-xs font-bold text-slate-400 mt-4">Paiement sécurisé via CinetPay</p>
                    </div>
                </div>
            </div>
        </section>

        <script>lucide.createIcons();</script>
    </body>
</html>

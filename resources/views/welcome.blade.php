<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FinanceZen — Prenez le contrôle de vos finances</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .hero-pattern {
            background-image: radial-gradient(#6366f1 1px, transparent 1px);
            background-size: 32px 32px;
            opacity: 0.07;
        }
        /* Scroll reveal animations */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.5, 0, 0, 1);
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }
        .delay-100 { transition-delay: 100ms; }
        .delay-200 { transition-delay: 200ms; }
        .delay-300 { transition-delay: 300ms; }
        
        /* Floating animation for hero elements */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800 overflow-x-hidden selection:bg-primary selection:text-white flex flex-col min-h-screen">
    
    <!-- Navbar -->
    <nav x-data="{ scrolled: false, mobileMenuOpen: false }" 
         @scroll.window="scrolled = (window.pageYOffset > 20)" 
         :class="{'bg-white/90 backdrop-blur-md border-b border-slate-200 shadow-sm': scrolled, 'bg-transparent border-transparent': !scrolled}"
         class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="{{ route('welcome') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 bg-gradient-to-br from-primary to-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-primary/30 group-hover:scale-105 transition-transform duration-300">
                    <i data-lucide="trending-up" class="w-5 h-5"></i>
                </div>
                <span class="text-xl font-black tracking-tight text-slate-900">FinanceZen</span>
            </a>
            
            <!-- Desktop Menu -->
            <div class="hidden lg:flex items-center gap-8 bg-white/50 backdrop-blur border border-slate-200/50 px-6 py-2 rounded-full shadow-sm">
                <a href="#features" class="text-sm font-bold text-slate-600 hover:text-primary transition-colors">Fonctionnalités</a>
                <a href="#how-it-works" class="text-sm font-bold text-slate-600 hover:text-primary transition-colors">Méthode</a>
                <a href="#pricing" class="text-sm font-bold text-slate-600 hover:text-primary transition-colors">Tarifs</a>
                <a href="#testimonials" class="text-sm font-bold text-slate-600 hover:text-primary transition-colors">Avis</a>
            </div>

            <div class="hidden lg:flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-primary text-white text-sm font-bold rounded-xl shadow-lg shadow-primary/20 hover:bg-primary-dark transition-all hover:-translate-y-0.5 tracking-wide flex items-center gap-2">
                        Mon Espace <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 hover:text-primary transition-colors">Connexion</a>
                    <a href="{{ route('register') }}" class="px-5 py-2.5 bg-slate-900 text-white text-sm font-bold rounded-xl shadow-xl hover:bg-slate-800 transition-all hover:-translate-y-0.5 tracking-wide flex items-center gap-2">
                        Démarrer <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 text-slate-600 focus:outline-none">
                <i data-lucide="menu" class="w-6 h-6" x-show="!mobileMenuOpen"></i>
                <i data-lucide="x" class="w-6 h-6" x-show="mobileMenuOpen" x-cloak></i>
            </button>
        </div>

        <!-- Mobile Menu Panel -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="lg:hidden absolute top-20 left-0 right-0 bg-white border-b border-slate-200 shadow-xl" x-cloak>
            <div class="px-4 py-6 space-y-4 flex flex-col">
                <a href="#features" @click="mobileMenuOpen = false" class="text-base font-bold text-slate-700">Fonctionnalités</a>
                <a href="#how-it-works" @click="mobileMenuOpen = false" class="text-base font-bold text-slate-700">Méthode</a>
                <a href="#pricing" @click="mobileMenuOpen = false" class="text-base font-bold text-slate-700">Tarifs</a>
                <a href="#testimonials" @click="mobileMenuOpen = false" class="text-base font-bold text-slate-700">Avis</a>
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

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 px-4 sm:px-6 overflow-hidden">
        <div class="absolute inset-0 bg-white rounded-b-[3rem] lg:rounded-b-[5rem] shadow-sm -z-20"></div>
        <div class="absolute inset-0 hero-pattern pointer-events-none -z-10"></div>
        
        <!-- Animated Blobs -->
        <div class="absolute top-20 right-[-10%] w-72 h-72 md:w-96 md:h-96 bg-primary/20 rounded-full blur-[80px] -z-10 animate-float"></div>
        <div class="absolute bottom-10 left-[-10%] w-72 h-72 md:w-96 md:h-96 bg-emerald-400/20 rounded-full blur-[80px] -z-10 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-4xl mx-auto text-center relative z-10 reveal">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-indigo-50 border border-indigo-100 text-primary rounded-full text-xs font-black mb-8 tracking-widest uppercase shadow-sm hover:scale-105 transition-transform cursor-default">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                </span>
                La Révolution Budgétaire
            </div>
            
            <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-[4.5rem] font-black text-slate-900 mb-6 tracking-tight leading-[1.1]">
                Reprenez le contrôle de votre <span class="relative whitespace-nowrap"><span class="absolute -bottom-2 md:-bottom-3 left-0 right-0 h-3 md:h-4 bg-emerald-300/40 -z-10 -rotate-1"></span><span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-indigo-600">argent</span></span>.
            </h1>
            
            <p class="text-lg md:text-xl text-slate-600 mb-10 max-w-2xl mx-auto leading-relaxed font-medium">
                FinanceZen remplace vos fichiers Excel complexes par une interface belle, intuitive et intelligente pour bâtir votre prospérité jour après jour.
            </p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-2xl shadow-xl hover:shadow-2xl hover:shadow-slate-900/20 transform hover:-translate-y-1 transition-all text-base flex items-center justify-center gap-2">
                    Essayer gratuitement (7 jours) <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
                <a href="#how-it-works" class="w-full sm:w-auto px-8 py-4 bg-white text-slate-800 font-bold rounded-2xl border-2 border-slate-200 hover:border-slate-300 hover:bg-slate-50 transition-all text-base flex items-center justify-center gap-2">
                    Comment ça marche ?
                </a>
            </div>

            <div class="mt-8 flex flex-wrap items-center justify-center gap-4 sm:gap-6 text-xs sm:text-sm font-bold text-slate-500">
                <div class="flex items-center gap-2"><i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-500"></i> Sans CB à l'inscription</div>
                <div class="flex items-center gap-2"><i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-500"></i> Annulation en 1 clic</div>
                <div class="flex items-center gap-2"><i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-500"></i> Données ultra-sécurisées</div>
            </div>
        </div>

        <!-- Dashboard Abstract Preview -->
        <div class="max-w-5xl mx-auto mt-20 relative z-20 reveal delay-200 hidden md:block">
            <div class="bg-white/80 backdrop-blur-md rounded-3xl p-3 shadow-2xl shadow-indigo-900/5 border border-white transform perspective-1000 rotate-x-6 hover:rotate-x-0 transition-transform duration-700">
                <div class="bg-slate-100/50 rounded-2xl overflow-hidden border border-slate-200/50 relative">
                    <!-- Fausse barre de menu Mac -->
                    <div class="h-8 bg-slate-200/50 flex items-center px-4 gap-1.5 backdrop-blur-sm">
                        <div class="w-2.5 h-2.5 rounded-full bg-rose-400"></div>
                        <div class="w-2.5 h-2.5 rounded-full bg-amber-400"></div>
                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-400"></div>
                    </div>
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=2070&auto=format&fit=crop" class="w-full h-[400px] object-cover opacity-90 sepia-[.1] hue-rotate-15 blur-[1px] hover:blur-none transition-all duration-700" alt="FinanceZen Preview">
                    <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-t from-white via-white/40 to-transparent">
                        <div class="bg-white/90 backdrop-blur shrink-0 px-6 py-4 rounded-2xl shadow-xl flex items-center gap-4 animate-float">
                            <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center"><i data-lucide="trending-down" class="w-6 h-6"></i></div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase">Dette remboursée</p>
                                <p class="text-xl font-black text-slate-800">-450 000 FCFA</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Social Proof -->
    <section class="py-10 border-b border-slate-200/60 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center reveal">
            <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-6">Un outil approuvé par vos pairs</p>
            <div class="flex flex-wrap justify-center items-center gap-8 md:gap-16 opacity-40 grayscale hover:grayscale-0 transition-all duration-500">
                <div class="text-xl font-black flex items-center gap-1"><i data-lucide="briefcase" class="w-5 h-5"></i> Entrepreneurs</div>
                <div class="text-xl font-bold italic flex items-center gap-1"><i data-lucide="coffee" class="w-5 h-5"></i> Freelances</div>
                <div class="text-xl font-black tracking-widest flex items-center gap-1"><i data-lucide="home" class="w-5 h-5"></i> Familles</div>
                <div class="text-xl font-bold flex items-center gap-1"><i data-lucide="graduation-cap" class="w-5 h-5"></i> Étudiants</div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section id="features" class="py-24 px-4 sm:px-6 bg-slate-50">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16 reveal">
                <h2 class="text-3xl md:text-4xl font-black text-slate-900 mb-4 tracking-tight">Une gestion financière sans angles morts</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">Chaque outil a été pensé pour vous offrir une clarté maximale et encourager la prise de décisions éclairées.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 hover-group">
                <!-- Feature 1 -->
                <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-xl hover:border-primary/20 hover:-translate-y-1 transition-all duration-300 reveal">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-6"><i data-lucide="pie-chart" class="w-6 h-6"></i></div>
                    <h3 class="text-xl font-black mb-3">Budgets Intelligents</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Divisez vos revenus selon la règle 50/30/20. Fixez des limites par catégorie et suivez votre évolution en temps réel.</p>
                </div>
                <!-- Feature 2 -->
                <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-xl hover:border-emerald-500/20 hover:-translate-y-1 transition-all duration-300 reveal delay-100">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mb-6"><i data-lucide="shield-check" class="w-6 h-6"></i></div>
                    <h3 class="text-xl font-black mb-3">Éradication des Dettes</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Suivez précisément vos remboursements. Visualisez votre ligne d'arrivée vers une vie 100% sans pression de créanciers.</p>
                </div>
                <!-- Feature 3 -->
                <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-xl hover:border-amber-500/20 hover:-translate-y-1 transition-all duration-300 reveal delay-200">
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center mb-6"><i data-lucide="target" class="w-6 h-6"></i></div>
                    <h3 class="text-xl font-black mb-3">Objectifs d'Épargne</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Fonds d'urgence, prochaines vacances... Créez vos cibles et laissez FinanceZen vous motiver à les atteindre.</p>
                </div>
                <!-- Feature 4 -->
                <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-xl hover:border-blue-500/20 hover:-translate-y-1 transition-all duration-300 reveal">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-6"><i data-lucide="file-bar-chart-2" class="w-6 h-6"></i></div>
                    <h3 class="text-xl font-black mb-3">Rapports Complets</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Générez des rapports visuels puissants. Comprenez où part votre argent grâce à des graphiques dynamiques et fluides.</p>
                </div>
                <!-- Feature 5 -->
                <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-xl hover:border-purple-500/20 hover:-translate-y-1 transition-all duration-300 reveal delay-100">
                    <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center mb-6"><i data-lucide="refresh-cw" class="w-6 h-6"></i></div>
                    <h3 class="text-xl font-black mb-3">Saisies Rapides</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Transactions récurrentes, création de catégories à la volée. Un UX pensé pour ne pas vous faire perdre une seconde.</p>
                </div>
                <!-- Feature 6 -->
                <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-xl hover:border-rose-500/20 hover:-translate-y-1 transition-all duration-300 reveal delay-200">
                    <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center mb-6"><i data-lucide="smartphone" class="w-6 h-6"></i></div>
                    <h3 class="text-xl font-black mb-3">Multi-Devises & Mobile</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Paiement via FedaPay (Mobile Money), interface 100% responsive : gérez votre empire financier depuis n'importe où.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How it works -->
    <section id="how-it-works" class="py-24 bg-white px-4 sm:px-6 relative overflow-hidden">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="text-center mb-16 reveal">
                <h2 class="text-3xl md:text-4xl font-black text-slate-900 mb-4 tracking-tight">Simple comme bonjour</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">Trois étapes limpides pour transformer votre rapport à l'argent.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative max-w-5xl mx-auto">
                <!-- Connect line hidden on mobile -->
                <div class="hidden md:block absolute top-[2.5rem] left-[15%] right-[15%] h-[2px] bg-gradient-to-r from-slate-100 via-primary/30 to-slate-100 z-0"></div>
                
                <div class="relative z-10 text-center reveal">
                    <div class="w-20 h-20 mx-auto bg-white border-[3px] border-slate-100 text-slate-400 rounded-2xl flex items-center justify-center text-2xl font-black mb-6 shadow-sm rotate-3 transform group hover:rotate-0 transition">
                        1
                    </div>
                    <h3 class="text-lg font-bold mb-3 text-slate-800">Paramétrez</h3>
                    <p class="text-sm text-slate-500 leading-relaxed px-4">Entrez votre salaire fixe ou freelance, configurez votre devise et listez vos objectifs initiaux.</p>
                </div>
                <div class="relative z-10 text-center reveal delay-100">
                    <div class="w-20 h-20 mx-auto bg-primary border-[3px] border-primary/20 text-white rounded-2xl flex items-center justify-center text-2xl font-black mb-6 shadow-lg shadow-primary/30 -rotate-3 transform hover:rotate-0 hover:scale-105 transition">
                        2
                    </div>
                    <h3 class="text-lg font-bold mb-3 text-slate-800">Notez au quotidien</h3>
                    <p class="text-sm text-slate-500 leading-relaxed px-4">Ajoutez vos dépenses en deux clics depuis votre mobile. Le tableau de bord se met à jour instantanément.</p>
                </div>
                <div class="relative z-10 text-center reveal delay-200">
                    <div class="w-20 h-20 mx-auto bg-white border-[3px] border-slate-100 text-slate-400 rounded-2xl flex items-center justify-center text-2xl font-black mb-6 shadow-sm rotate-3 transform hover:rotate-0 transition">
                        3
                    </div>
                    <h3 class="text-lg font-bold mb-3 text-slate-800">Analysez</h3>
                    <p class="text-sm text-slate-500 leading-relaxed px-4">À la fin du mois, générez vos bilans, admirez vos dettes baisser et votre sécurité financière augmenter.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section id="testimonials" class="py-24 bg-slate-900 text-white px-4 sm:px-6 relative overflow-hidden">
        <div class="absolute inset-0 hero-pattern opacity-5"></div>
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="text-center mb-16 reveal">
                <h2 class="text-3xl md:text-4xl font-black mb-4 tracking-tight">Leurs finances s'apaisent</h2>
                <p class="text-lg text-slate-400 max-w-2xl mx-auto">Quand la clarté financière remplace l'angoisse des fins de mois.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-slate-800/50 p-8 rounded-3xl border border-slate-700/50 hover:bg-slate-800 transition reveal">
                    <div class="flex items-center gap-1 mb-6 text-amber-400">
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i><i data-lucide="star" class="w-4 h-4 fill-current"></i><i data-lucide="star" class="w-4 h-4 fill-current"></i><i data-lucide="star" class="w-4 h-4 fill-current"></i><i data-lucide="star" class="w-4 h-4 fill-current"></i>
                    </div>
                    <p class="text-slate-300 text-sm mb-6 leading-relaxed italic">"Avant, j'étais toujours à découvert le 20. Le suivi visuel a recadré mes dépenses. Je viens d'économiser mes premiers 100 000 FCFA !"</p>
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-slate-700 flex items-center justify-center font-bold text-sm">MD</div>
                        <div>
                            <h4 class="font-bold text-sm text-white">Marie D.</h4>
                            <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">Freelance</p>
                        </div>
                    </div>
                </div>

                <div class="bg-primary/10 p-8 rounded-3xl border border-primary/20 hover:bg-primary/20 transition reveal delay-100 transform md:-translate-y-4">
                    <div class="flex items-center gap-1 mb-6 text-amber-400">
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i><i data-lucide="star" class="w-4 h-4 fill-current"></i><i data-lucide="star" class="w-4 h-4 fill-current"></i><i data-lucide="star" class="w-4 h-4 fill-current"></i><i data-lucide="star" class="w-4 h-4 fill-current"></i>
                    </div>
                    <p class="text-white text-sm mb-6 leading-relaxed font-medium">"Interface ultra professionnelle. Je gère les finances de notre foyer hyper facilement, et le mode sombre est juste parfait le soir."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center font-bold text-sm">TL</div>
                        <div>
                            <h4 class="font-bold text-sm text-white">Thomas L.</h4>
                            <p class="text-[10px] text-primary-200 uppercase tracking-widest font-bold">Chef de Projet</p>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-800/50 p-8 rounded-3xl border border-slate-700/50 hover:bg-slate-800 transition reveal delay-200">
                    <div class="flex items-center gap-1 mb-6 text-amber-400">
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i><i data-lucide="star" class="w-4 h-4 fill-current"></i><i data-lucide="star" class="w-4 h-4 fill-current"></i><i data-lucide="star" class="w-4 h-4 fill-current"></i><i data-lucide="star" class="w-4 h-4 fill-current"></i>
                    </div>
                    <p class="text-slate-300 text-sm mb-6 leading-relaxed italic">"J'ai pu structurer le remboursement d'un gros crédit. Voir la jauge avancer vers les 100% chaque mois est ma meilleure motivation."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-slate-700 flex items-center justify-center font-bold text-sm">SM</div>
                        <div>
                            <h4 class="font-bold text-sm text-white">Sarah M.</h4>
                            <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">Commerçante</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-24 px-4 sm:px-6 bg-slate-50 relative">
         <div class="max-w-5xl mx-auto">
             <div class="text-center mb-16 reveal">
                <h2 class="text-3xl md:text-4xl font-black text-slate-900 mb-4 tracking-tight">Le prix de la sérénité</h2>
                <p class="text-lg text-slate-600 max-w-xl mx-auto">Un outil premium, sans coûts cachés. 7 jours pour tester.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <!-- Mensuel -->
                <div class="p-8 bg-white rounded-[2rem] border border-slate-200 hover:border-slate-300 transition-all shadow-sm reveal">
                    <h3 class="text-2xl font-black mb-2 text-slate-800">Mensuel</h3>
                    <p class="text-slate-500 text-sm mb-6">La flexibilité totale, mois par mois.</p>
                    <div class="flex items-baseline gap-1 mb-8">
                        <span class="text-4xl font-black text-slate-900">1 000</span>
                        <span class="text-slate-500 font-bold text-sm">FCFA/mois</span>
                    </div>
                    <ul class="space-y-3 mb-8 text-sm">
                        <li class="flex items-center gap-3 text-slate-700"><i data-lucide="check" class="text-emerald-500 w-5 h-5"></i> Accès complet au Tableau de bord</li>
                        <li class="flex items-center gap-3 text-slate-700"><i data-lucide="check" class="text-emerald-500 w-5 h-5"></i> Dettes et Épargnes illimitées</li>
                        <li class="flex items-center gap-3 text-slate-700"><i data-lucide="check" class="text-emerald-500 w-5 h-5"></i> Exportations PDF / CSV</li>
                    </ul>
                    <a href="{{ route('register') }}" class="block w-full py-3.5 text-center bg-slate-100 text-slate-800 font-bold rounded-xl hover:bg-slate-200 transition">Démarrer Mensuel</a>
                </div>

                <!-- Annuel -->
                <div class="p-8 bg-slate-900 text-white rounded-[2rem] shadow-2xl relative transform md:scale-105 border border-slate-800 reveal delay-100">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary/10 to-transparent rounded-[2rem] pointer-events-none"></div>
                    <div class="absolute top-0 right-8 py-1.5 px-4 bg-primary text-white font-bold text-[10px] uppercase tracking-widest rounded-b-lg shadow-sm">Le Choix Malin</div>
                    <div class="relative z-10">
                        <h3 class="text-2xl font-black mb-2">Annuel</h3>
                        <p class="text-slate-400 text-sm mb-6">L'engagement vers la réussite financière.</p>
                        <div class="flex flex-col mb-8">
                            <div class="flex items-baseline gap-1">
                                <span class="text-5xl font-black text-white">10 000</span>
                                <span class="text-slate-400 font-bold text-sm">FCFA/an</span>
                            </div>
                            <span class="text-primary font-bold mt-2 bg-primary/10 inline-block w-max px-3 py-1 rounded-lg text-xs tracking-wide">2 MOIS OFFERTS</span>
                        </div>
                        <ul class="space-y-3 mb-8 text-sm">
                            <li class="flex items-center gap-3 text-slate-200"><i data-lucide="check" class="text-primary w-5 h-5"></i> Tout l'accès Mensuel</li>
                            <li class="flex items-center gap-3 text-slate-200"><i data-lucide="check" class="text-primary w-5 h-5"></i> Support ultra-prioritaire</li>
                            <li class="flex items-center gap-3 text-slate-200"><i data-lucide="check" class="text-primary w-5 h-5"></i> Sérénité garantie pour 365 jours</li>
                        </ul>
                        <a href="{{ route('register') }}" class="block w-full py-3.5 text-center bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:bg-primary-dark transition hover:shadow-primary/30 transform hover:-translate-y-0.5">Démarrer Annuel</a>
                    </div>
                </div>
            </div>
            
            <div class="mt-12 text-center reveal delay-200">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center justify-center gap-2">
                    <i data-lucide="lock" class="w-4 h-4"></i> Paiement sécurisé via FedaPay
                </p>
            </div>
        </div>
    </section>

    <!-- Bottom CTA -->
    <section class="py-20 px-4 sm:px-6 bg-white relative">
        <div class="max-w-4xl mx-auto bg-slate-900 rounded-[3rem] p-10 md:p-16 text-center shadow-2xl relative overflow-hidden reveal">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/20 to-indigo-600/20 pointer-events-none"></div>
            <div class="relative z-10">
                <h2 class="text-3xl md:text-4xl font-black mb-6 text-white leading-tight">La tranquillité d'esprit est à portée de clic.</h2>
                <p class="text-lg text-slate-300 mb-10 max-w-xl mx-auto">Ne laissez plus votre argent dicter vos choix. Commencez votre essai gratuit aujourd'hui.</p>
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-slate-900 font-black rounded-xl shadow-xl hover:bg-slate-50 transform hover:-translate-y-1 transition text-sm md:text-base">
                    Créer mon compte (Lancement direct) <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 pt-16 pb-8 px-4 sm:px-6">
        <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-12 mb-12">
            <!-- Brand -->
            <div class="col-span-1 md:col-span-1">
                <a href="{{ route('welcome') }}" class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-slate-900 rounded-lg flex items-center justify-center text-white">
                        <i data-lucide="trending-up" class="w-5 h-5"></i>
                    </div>
                    <span class="text-lg font-black text-slate-900">FinanceZen</span>
                </a>
                <p class="text-xs text-slate-500 leading-relaxed mb-6">Conçu pour simplifier l'organisation budgétaire personnelle et maximiser vos économies au quotidien.</p>
            </div>

            <!-- Links -->
            <div>
                <h4 class="text-slate-900 font-black mb-4 uppercase text-[10px] tracking-widest">Produit</h4>
                <ul class="space-y-3 text-sm font-medium">
                    <li><a href="#features" class="text-slate-500 hover:text-primary transition">Avantages</a></li>
                    <li><a href="#how-it-works" class="text-slate-500 hover:text-primary transition">Méthode</a></li>
                    <li><a href="#pricing" class="text-slate-500 hover:text-primary transition">Prix</a></li>
                </ul>
            </div>

            <!-- Legal -->
            <div>
                <h4 class="text-slate-900 font-black mb-4 uppercase text-[10px] tracking-widest">Légal & Conformité</h4>
                <ul class="space-y-3 text-sm font-medium">
                    <li><a href="{{ route('legal') }}" class="text-slate-500 hover:text-primary transition">Mentions Légales</a></li>
                    <li><a href="{{ route('privacy') }}" class="text-slate-500 hover:text-primary transition">Confidentialité</a></li>
                </ul>
            </div>

            <!-- Support -->
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
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1">
                Fait par <a href="https://miguelmissetcho.com">Miguel M.</a> pour votre sérénité
            </p>
        </div>
    </footer>

    <!-- Init Icons & Reveal script -->
    <script>
        lucide.createIcons();
        
        // Scroll Reveal Logic
        document.addEventListener('DOMContentLoaded', () => {
            const reveals = document.querySelectorAll('.reveal');
            
            const revealOnScroll = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if(entry.isIntersecting) {
                        entry.target.classList.add('active');
                        // Optionnel : ne plus l'observer une fois affiché
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1, // Déclenche quand 10% de l'élément est visible
                rootMargin: "0px 0px -50px 0px"
            });
            
            reveals.forEach(reveal => {
                revealOnScroll.observe(reveal);
            });
            
            // Trigger une première fois pour les éléments déjà dans le viewport
            setTimeout(() => {
                reveals.forEach(reveal => {
                    const rect = reveal.getBoundingClientRect();
                    if(rect.top < window.innerHeight) {
                        reveal.classList.add('active');
                    }
                });
            }, 100);
        });
    </script>
</body>
</html>

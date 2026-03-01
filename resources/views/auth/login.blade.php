<x-public-layout :title="'Connexion — FinanceZen'">
    <section class="py-16 md:py-24 px-4 sm:px-6">
        <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">

            <!-- Colonne gauche : Argumentaire -->
            <div class="hidden lg:block">
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 mb-6 tracking-tight leading-tight">Reprenez le contrôle de vos finances.</h1>
                <p class="text-lg text-slate-500 mb-10 leading-relaxed">Connectez-vous pour accéder à votre tableau de bord et continuer à bâtir votre prospérité financière.</p>

                <div class="space-y-5">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center shrink-0"><i data-lucide="pie-chart" class="w-5 h-5 text-indigo-600"></i></div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-sm">Budgets Intelligents</h3>
                            <p class="text-xs text-slate-500">Suivez vos dépenses par catégorie en temps réel.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center shrink-0"><i data-lucide="trending-down" class="w-5 h-5 text-emerald-600"></i></div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-sm">Éradication des Dettes</h3>
                            <p class="text-xs text-slate-500">Visualisez votre progression vers une vie sans créanciers.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center shrink-0"><i data-lucide="target" class="w-5 h-5 text-amber-600"></i></div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-sm">Objectifs d'Épargne</h3>
                            <p class="text-xs text-slate-500">Atteignez vos cibles financières mois après mois.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne droite : Formulaire -->
            <div class="w-full max-w-md mx-auto lg:mx-0">
                <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8 md:p-10 relative overflow-hidden">
                    <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-primary to-indigo-600"></div>

                    <h2 class="text-2xl font-black text-slate-900 mb-2">Connexion</h2>
                    <p class="text-sm text-slate-500 mb-8">Accédez à votre espace personnel.</p>

                    <x-auth-session-status class="mb-6" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Email</label>
                            <div class="relative group">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition"><i data-lucide="mail" class="w-5 h-5"></i></div>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                       class="w-full pl-12 rounded-xl border-slate-200 focus:border-primary focus:ring-primary h-12 font-medium">
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Mot de passe</label>
                            <div class="relative group">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition"><i data-lucide="lock" class="w-5 h-5"></i></div>
                                <input id="password" type="password" name="password" required autocomplete="current-password"
                                       class="w-full pl-12 rounded-xl border-slate-200 focus:border-primary focus:ring-primary h-12 font-medium">
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-between">
                            <label for="remember_me" class="inline-flex items-center">
                                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-primary focus:ring-primary h-4 w-4" name="remember">
                                <span class="ml-2 text-sm font-medium text-slate-600">Se souvenir de moi</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a class="text-sm font-bold text-primary hover:text-primary-dark transition" href="{{ route('password.request') }}">Mot de passe oublié ?</a>
                            @endif
                        </div>

                        <button type="submit" class="w-full py-3.5 bg-slate-900 text-white font-bold rounded-xl shadow-lg hover:bg-slate-800 transition text-base flex items-center justify-center gap-2">
                            Se connecter <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </button>

                        <div class="text-center pt-4 border-t border-slate-100">
                            <p class="text-sm text-slate-500">
                                Pas encore de compte ?
                                <a href="{{ route('register') }}" class="text-primary font-bold hover:underline ml-1">Créer un compte</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </section>
</x-public-layout>

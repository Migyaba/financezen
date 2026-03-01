<x-public-layout :title="'Créer un compte — FinanceZen'">
    <section class="py-16 md:py-24 px-4 sm:px-6">
        <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">

            <!-- Colonne gauche : Argumentaire -->
            <div class="hidden lg:block">
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 mb-6 tracking-tight leading-tight">Commencez votre voyage vers la liberté financière.</h1>
                <p class="text-lg text-slate-500 mb-10 leading-relaxed">Créez votre compte gratuitement et profitez de 7 jours d'essai sans engagement pour découvrir FinanceZen.</p>

                <div class="space-y-5">
                    <div class="flex items-center gap-3 text-sm">
                        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center"><i data-lucide="check" class="w-4 h-4 text-emerald-600"></i></div>
                        <span class="font-medium text-slate-700">7 jours d'essai gratuit, sans carte bancaire</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center"><i data-lucide="check" class="w-4 h-4 text-emerald-600"></i></div>
                        <span class="font-medium text-slate-700">Accès complet à toutes les fonctionnalités</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center"><i data-lucide="check" class="w-4 h-4 text-emerald-600"></i></div>
                        <span class="font-medium text-slate-700">Annulation en un clic, sans justification</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center"><i data-lucide="check" class="w-4 h-4 text-emerald-600"></i></div>
                        <span class="font-medium text-slate-700">Données chiffrées et 100% sécurisées</span>
                    </div>
                </div>

                <div class="mt-12 p-6 bg-slate-900 rounded-2xl text-white">
                    <p class="text-sm italic leading-relaxed mb-4">"Avant, j'étais toujours à découvert le 20. Le suivi visuel a recadré mes dépenses. Je viens d'économiser mes premiers 100 000 FCFA !"</p>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center text-xs font-bold">MD</div>
                        <div>
                            <p class="text-sm font-bold">Marie D.</p>
                            <p class="text-[10px] text-slate-400 uppercase tracking-widest">Freelance</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne droite : Formulaire -->
            <div class="w-full max-w-md mx-auto lg:mx-0">
                <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8 md:p-10 relative overflow-hidden">
                    <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-primary to-indigo-600"></div>

                    <h2 class="text-2xl font-black text-slate-900 mb-2">Créer un compte</h2>
                    <p class="text-sm text-slate-500 mb-8">Inscription rapide en moins de 30 secondes.</p>

                    <form method="POST" action="{{ route('register') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label for="name" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Nom Complet</label>
                            <div class="relative group">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition"><i data-lucide="user" class="w-5 h-5"></i></div>
                                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                                       class="w-full pl-12 rounded-xl border-slate-200 focus:border-primary focus:ring-primary h-12 font-medium" placeholder="Jean Dupont">
                            </div>
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Email</label>
                            <div class="relative group">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition"><i data-lucide="mail" class="w-5 h-5"></i></div>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                                       class="w-full pl-12 rounded-xl border-slate-200 focus:border-primary focus:ring-primary h-12 font-medium" placeholder="jean@example.com">
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <label for="phone" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Téléphone</label>
                            <div class="relative group">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition"><i data-lucide="phone" class="w-5 h-5"></i></div>
                                <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" autocomplete="tel"
                                       class="w-full pl-12 rounded-xl border-slate-200 focus:border-primary focus:ring-primary h-12 font-medium" placeholder="+229 00 00 00 00">
                            </div>
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Mot de passe</label>
                            <div class="relative group">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition"><i data-lucide="lock" class="w-5 h-5"></i></div>
                                <input id="password" type="password" name="password" required autocomplete="new-password"
                                       class="w-full pl-12 rounded-xl border-slate-200 focus:border-primary focus:ring-primary h-12 font-medium" placeholder="8 caractères minimum">
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Confirmer mot de passe</label>
                            <div class="relative group">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition"><i data-lucide="check-circle" class="w-5 h-5"></i></div>
                                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                                       class="w-full pl-12 rounded-xl border-slate-200 focus:border-primary focus:ring-primary h-12 font-medium" placeholder="Retapez votre mot de passe">
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <button type="submit" class="w-full py-3.5 bg-slate-900 text-white font-bold rounded-xl shadow-lg hover:bg-slate-800 transition text-base flex items-center justify-center gap-2">
                            Créer mon compte <i data-lucide="sparkles" class="w-4 h-4"></i>
                        </button>

                        <div class="text-center pt-4 border-t border-slate-100">
                            <p class="text-sm text-slate-500">
                                Déjà inscrit ?
                                <a href="{{ route('login') }}" class="text-primary font-bold hover:underline ml-1">Se connecter</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </section>
</x-public-layout>

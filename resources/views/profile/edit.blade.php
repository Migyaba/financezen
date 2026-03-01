<x-app-layout>
    <x-slot name="header">Mon Profil</x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Profile Card -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-8">
            <div class="flex items-center gap-6 mb-8">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=4F46E5&color=fff&size=80" class="w-20 h-20 rounded-2xl shadow-lg" alt="Avatar">
                <div>
                    <h2 class="text-2xl font-black text-slate-800 dark:text-white">{{ $user->name }}</h2>
                    <p class="text-slate-500 dark:text-slate-400">{{ $user->email }}</p>
                    <p class="text-xs text-slate-400 mt-1">Membre depuis {{ $user->created_at->translatedFormat('F Y') }}</p>
                </div>
            </div>

            <h3 class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                <i data-lucide="user" class="w-4 h-4"></i> Informations du profil
            </h3>

            <form id="send-verification" method="post" action="{{ route('verification.send') }}">@csrf</form>

            <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
                @csrf @method('patch')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Nom Complet</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required
                               class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 font-medium focus:ring-primary focus:border-primary">
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <label for="phone" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Téléphone</label>
                        <input id="phone" name="phone" type="tel" value="{{ old('phone', $user->phone) }}"
                               class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 font-medium focus:ring-primary focus:border-primary">
                        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                           class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 font-medium focus:ring-primary focus:border-primary">
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="mt-2">
                            <p class="text-sm text-amber-600 dark:text-amber-400">
                                Votre email n'est pas vérifié.
                                <button form="send-verification" class="underline text-sm text-primary hover:text-primary-dark font-bold">Renvoyer</button>
                            </p>
                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 text-sm text-success font-bold">Un nouveau lien a été envoyé.</p>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-4 border-t border-slate-100 dark:border-slate-700">
                    <div>
                        <label for="currency" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 flex items-center gap-2">
                            <i data-lucide="coins" class="w-3 h-3"></i> Devise Principale
                        </label>
                        <select id="currency" name="currency" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 font-bold focus:ring-primary focus:border-primary">
                            <option value="FCFA" {{ old('currency', $user->currency) == 'FCFA' ? 'selected' : '' }}>Franc CFA (FCFA)</option>
                            <option value="EUR" {{ old('currency', $user->currency) == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                            <option value="USD" {{ old('currency', $user->currency) == 'USD' ? 'selected' : '' }}>Dollar US ($)</option>
                            <option value="CAD" {{ old('currency', $user->currency) == 'CAD' ? 'selected' : '' }}>Dollar Canadien (CAD)</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('currency')" />
                    </div>

                    <div>
                        <label for="monthly_salary" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 flex items-center gap-2">
                            <i data-lucide="wallet" class="w-3 h-3"></i> Revenu / Salaire Mensuel Fixe
                        </label>
                        <input id="monthly_salary" name="monthly_salary" type="number" step="0.01" value="{{ old('monthly_salary', $user->monthly_salary) }}"
                               placeholder="Ex: 500000" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 font-bold focus:ring-primary focus:border-primary">
                        <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-widest">Utilisé pour les simulations</p>
                        <x-input-error class="mt-2" :messages="$errors->get('monthly_salary')" />
                    </div>

                    <div class="md:col-span-2">
                        <label for="freelance_split" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 flex items-center gap-2">
                            <i data-lucide="pie-chart" class="w-3 h-3"></i> Règle de répartition 50/30/20
                        </label>
                        <input id="freelance_split" name="freelance_split" type="text" value="{{ old('freelance_split', $user->freelance_split ?? '50/30/20') }}"
                               placeholder="Ex: 50/30/20" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 font-bold focus:ring-primary focus:border-primary">
                        <p class="text-xs text-slate-400 mt-1">Format: <b>{Besoins}/{Envies}/{Épargne}</b> (Ex: <span class="text-slate-500">50/30/20</span>). Option très utile pour les freelances/indépendants.</p>
                        <x-input-error class="mt-2" :messages="$errors->get('freelance_split')" />
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 pt-2">
                    @if (session('status') === 'profile-updated')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-success font-bold">✅ Sauvegardé !</p>
                    @endif
                    <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:bg-primary-dark transition flex items-center gap-2">
                        <i data-lucide="save" class="w-5 h-5"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>

        <!-- Password -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-8">
            <h3 class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                <i data-lucide="lock" class="w-4 h-4"></i> Changer le mot de passe
            </h3>

            <form method="post" action="{{ route('password.update') }}" class="space-y-5">
                @csrf @method('put')

                <div>
                    <label for="current_password" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Mot de passe actuel</label>
                    <input id="current_password" name="current_password" type="password" autocomplete="current-password"
                           class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 focus:ring-primary focus:border-primary">
                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Nouveau mot de passe</label>
                        <input id="password" name="password" type="password" autocomplete="new-password"
                               class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 focus:ring-primary focus:border-primary">
                        <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Confirmer</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                               class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-12 focus:ring-primary focus:border-primary">
                        <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 pt-2">
                    @if (session('status') === 'password-updated')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-success font-bold">✅ Mot de passe mis à jour !</p>
                    @endif
                    <button type="submit" class="px-6 py-2.5 bg-slate-800 dark:bg-slate-600 text-white font-bold rounded-xl hover:bg-slate-700 transition text-sm">Modifier le mot de passe</button>
                </div>
            </form>
        </div>

        <!-- Delete Account -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-red-100 dark:border-red-900/30 p-8">
            <h3 class="text-sm font-bold text-red-500 uppercase tracking-widest mb-2 flex items-center gap-2">
                <i data-lucide="alert-triangle" class="w-4 h-4"></i> Zone dangereuse
            </h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">La suppression de votre compte est irréversible. Toutes vos données seront définitivement perdues.</p>

            <div x-data="{ confirmDelete: false }">
                <button @click="confirmDelete = true" x-show="!confirmDelete" class="px-6 py-2.5 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-bold rounded-xl border border-red-200 dark:border-red-800/30 hover:bg-red-100 dark:hover:bg-red-900/40 transition text-sm">
                    Supprimer mon compte
                </button>

                <form method="post" action="{{ route('profile.destroy') }}" x-show="confirmDelete" x-transition class="space-y-4">
                    @csrf @method('delete')
                    <p class="text-sm text-red-600 dark:text-red-400 font-bold">Confirmez en saisissant votre mot de passe :</p>
                    <input type="password" name="password" required placeholder="Votre mot de passe" class="w-full rounded-xl border-red-200 dark:border-red-800 dark:bg-slate-900 h-11 focus:ring-red-500 focus:border-red-500">
                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                    <div class="flex gap-3">
                        <button type="button" @click="confirmDelete = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-xl text-sm">Annuler</button>
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white font-bold rounded-xl text-sm hover:bg-red-600 transition">Supprimer définitivement</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

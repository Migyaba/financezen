<x-app-layout>
    <x-slot name="header">Mon Profil</x-slot>

    <div class="max-w-6xl mx-auto px-0 sm:px-6 lg:px-8" x-data="{ activeTab: 'general' }">
        <div class="flex flex-col lg:flex-row gap-4 lg:gap-8">
            
            <!-- Sidebar / Mobile Navigation -->
            <div class="w-full lg:w-72 flex-shrink-0 lg:block sticky top-0 lg:top-8 z-30 lg:z-10">
                <!-- Mobile Horizontal Nav (Sticky & Glass) -->
                <div class="lg:hidden bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-100 dark:border-slate-800 px-4 py-2 overflow-x-auto no-scrollbar flex items-center gap-2">
                    <button @click="activeTab = 'general'" 
                            :class="activeTab === 'general' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800'"
                            class="flex-shrink-0 flex items-center gap-2 px-4 py-2.5 rounded-xl transition-all duration-300">
                        <i data-lucide="user" class="w-4 h-4"></i>
                        <span class="font-bold text-xs uppercase tracking-wider">Profil</span>
                    </button>
                    <button @click="activeTab = 'finance'" 
                            :class="activeTab === 'finance' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800'"
                            class="flex-shrink-0 flex items-center gap-2 px-4 py-2.5 rounded-xl transition-all duration-300">
                        <i data-lucide="wallet" class="w-4 h-4"></i>
                        <span class="font-bold text-xs uppercase tracking-wider">Finance</span>
                    </button>
                    <button @click="activeTab = 'security'" 
                            :class="activeTab === 'security' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800'"
                            class="flex-shrink-0 flex items-center gap-2 px-4 py-2.5 rounded-xl transition-all duration-300">
                        <i data-lucide="lock" class="w-4 h-4"></i>
                        <span class="font-bold text-xs uppercase tracking-wider">Sécurité</span>
                    </button>
                    <button @click="activeTab = 'danger'" 
                            :class="activeTab === 'danger' ? 'bg-rose-500 text-white shadow-lg shadow-rose-500/20' : 'text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/10'"
                            class="flex-shrink-0 flex items-center gap-2 px-4 py-2.5 rounded-xl transition-all duration-300">
                        <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                        <span class="font-bold text-xs uppercase tracking-wider">Compte</span>
                    </button>
                </div>

                <!-- Desktop Sidebar (Same as before but hidden on mobile) -->
                <div class="hidden lg:block bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-700/50 p-4">
                    <div class="flex flex-col gap-1">
                        <button @click="activeTab = 'general'" 
                                :class="activeTab === 'general' ? 'bg-primary/10 text-primary' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50'"
                                class="flex items-center gap-3 px-5 py-4 rounded-2xl transition-all duration-300 group">
                            <i data-lucide="user" class="w-5 h-5 transition-transform group-hover:scale-110"></i>
                            <span class="font-bold text-sm tracking-wide">Informations</span>
                        </button>

                        <button @click="activeTab = 'finance'" 
                                :class="activeTab === 'finance' ? 'bg-primary/10 text-primary' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50'"
                                class="flex items-center gap-3 px-5 py-4 rounded-2xl transition-all duration-300 group">
                            <i data-lucide="wallet" class="w-5 h-5 transition-transform group-hover:scale-110"></i>
                            <span class="font-bold text-sm tracking-wide">Finance & Budget</span>
                        </button>

                        <button @click="activeTab = 'security'" 
                                :class="activeTab === 'security' ? 'bg-primary/10 text-primary' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50'"
                                class="flex items-center gap-3 px-5 py-4 rounded-2xl transition-all duration-300 group">
                            <i data-lucide="lock" class="w-5 h-5 transition-transform group-hover:scale-110"></i>
                            <span class="font-bold text-sm tracking-wide">Sécurité</span>
                        </button>

                        <div class="h-px bg-slate-100 dark:bg-slate-700/50 my-2 mx-4"></div>

                        <button @click="activeTab = 'danger'" 
                                :class="activeTab === 'danger' ? 'bg-rose-50 text-rose-600' : 'text-slate-400 hover:bg-rose-50 dark:hover:bg-rose-900/10 hover:text-rose-500'"
                                class="flex items-center gap-3 px-5 py-4 rounded-2xl transition-all duration-300 group">
                            <i data-lucide="alert-triangle" class="w-5 h-5 transition-transform group-hover:scale-110"></i>
                            <span class="font-bold text-sm tracking-wide">Zone de danger</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1 px-4 sm:px-0">
                <!-- Profile Header Card -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl lg:rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden mb-6 lg:mb-8">
                    <div class="h-20 lg:h-32 bg-gradient-to-r from-primary/20 via-primary/10 to-transparent dark:from-primary/20"></div>
                    <div class="px-6 lg:px-8 pb-6 lg:pb-8 -mt-10 lg:-mt-12 flex flex-col sm:flex-row items-center sm:items-end gap-4 lg:gap-6">
                        <div class="relative group">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=4F46E5&color=fff&size=128" 
                                 class="w-24 h-24 lg:w-32 lg:h-32 rounded-3xl shadow-2xl shadow-primary/20 border-4 border-white dark:border-slate-800 object-cover" alt="Avatar">
                            <div class="absolute inset-0 bg-black/20 rounded-3xl opacity-0 lg:group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer">
                                <i data-lucide="camera" class="w-6 h-6 lg:w-8 lg:h-8 text-white"></i>
                            </div>
                        </div>
                        <div class="text-center sm:text-left flex-1 min-w-0 pb-2">
                            <h2 class="text-xl sm:text-3xl font-black text-slate-900 dark:text-white truncate">{{ $user->name }}</h2>
                            <div class="flex flex-wrap justify-center sm:justify-start items-center gap-x-4 gap-y-2 mt-1">
                                <span class="text-slate-500 dark:text-slate-400 font-medium flex items-center gap-1.5 text-sm">
                                    <i data-lucide="mail" class="w-4 h-4"></i> {{ $user->email }}
                                </span>
                                <span class="text-slate-400 dark:text-slate-500 text-sm flex items-center gap-1.5">
                                    <i data-lucide="calendar" class="w-4 h-4"></i> Membre depuis {{ $user->created_at->translatedFormat('M Y') }}
                                </span>
                            </div>
                        </div>
                        @if(session('status') === 'profile-updated' || session('status') === 'password-updated')
                            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
                                 class="bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest border border-emerald-100 dark:border-emerald-800/50 flex items-center gap-3 self-center sm:self-end mb-2">
                                <div class="w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center">
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-white"></i>
                                </div>
                                Changements enregistrés
                            </div>
                        @endif
                    </div>
                </div>

                <div class="space-y-8">
                    <!-- General Tab -->
                    <div x-show="activeTab === 'general'" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-700/50 p-8 sm:p-10">
                            <div class="mb-10 text-center sm:text-left">
                                <h3 class="text-xl font-black text-slate-900 dark:text-white">Informations Personnelles</h3>
                                <p class="text-slate-500 dark:text-slate-400 text-sm">Mettez à jour vos informations de base et vos préférences de contact.</p>
                            </div>

                            <form method="post" action="{{ route('profile.update') }}" class="space-y-8">
                                @csrf @method('patch')
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="space-y-2">
                                        <label for="name" class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Nom complet</label>
                                        <div class="relative">
                                            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required
                                                   class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-2xl h-14 px-6 font-bold text-slate-800 dark:text-white focus:ring-2 focus:ring-primary/20 transition-all">
                                        </div>
                                        <x-input-error :messages="$errors->get('name')" />
                                    </div>

                                    <div class="space-y-2">
                                        <label for="phone" class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Numéro de Téléphone</label>
                                        <input id="phone" name="phone" type="tel" value="{{ old('phone', $user->phone) }}"
                                               class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-2xl h-14 px-6 font-bold text-slate-800 dark:text-white focus:ring-2 focus:ring-primary/20 transition-all">
                                        <x-input-error :messages="$errors->get('phone')" />
                                    </div>

                                    <div class="md:col-span-2 space-y-2">
                                        <label for="email" class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Adresse Email</label>
                                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                                               class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-2xl h-14 px-6 font-bold text-slate-800 dark:text-white focus:ring-2 focus:ring-primary/20 transition-all">
                                        <x-input-error :messages="$errors->get('email')" />

                                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                            <div class="mt-4 p-4 bg-amber-50 dark:bg-amber-900/10 rounded-2xl flex items-center justify-between gap-4">
                                                <div class="flex items-center gap-3">
                                                    <i data-lucide="alert-circle" class="w-5 h-5 text-amber-500"></i>
                                                    <p class="text-xs font-bold text-amber-600 dark:text-amber-400">Email non vérifié.</p>
                                                </div>
                                                <button form="send-verification" class="px-4 py-2 bg-white dark:bg-slate-900 rounded-xl text-xs font-black uppercase text-amber-600 border border-amber-200 shadow-sm">Renvoyer le lien</button>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="pt-6 flex justify-end">
                                    <button type="submit" class="px-10 py-4 bg-primary text-white font-black rounded-2xl shadow-xl shadow-primary/20 hover:scale-105 active:scale-95 transition-all">
                                        Sauvegarder
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Finance Tab -->
                    <div x-show="activeTab === 'finance'" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
                        <form method="post" action="{{ route('profile.update') }}" class="space-y-8">
                            @csrf @method('patch')
                            
                            <!-- Base Config -->
                            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-700/50 p-8 sm:p-10">
                                <div class="mb-10">
                                    <h3 class="text-xl font-black text-slate-900 dark:text-white">Configuration Financière</h3>
                                    <p class="text-slate-500 dark:text-slate-400 text-sm">Ces données alimentent vos simulations et calculs budgétaires.</p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="space-y-4">
                                        <div class="space-y-2">
                                            <label for="currency" class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Devise</label>
                                            <select id="currency" name="currency" class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-2xl h-14 px-6 font-bold text-slate-800 dark:text-white focus:ring-2 focus:ring-primary/20 transition-all">
                                                <option value="FCFA" {{ old('currency', $user->currency) == 'FCFA' ? 'selected' : '' }}>Franc CFA (FCFA)</option>
                                                <option value="EUR" {{ old('currency', $user->currency) == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                                                <option value="USD" {{ old('currency', $user->currency) == 'USD' ? 'selected' : '' }}>Dollar US ($)</option>
                                                <option value="CAD" {{ old('currency', $user->currency) == 'CAD' ? 'selected' : '' }}>Dollar Canadien (CAD)</option>
                                            </select>
                                            <x-input-error :messages="$errors->get('currency')" />
                                        </div>

                                        <div class="space-y-2">
                                            <label for="monthly_salary" class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Revenu Mensuel Fixe</label>
                                            <div class="relative">
                                                <input id="monthly_salary" name="monthly_salary" type="number" step="0.01" value="{{ old('monthly_salary', $user->monthly_salary) }}"
                                                       class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-2xl h-14 px-6 font-bold text-slate-800 dark:text-white focus:ring-2 focus:ring-primary/20 transition-all pl-12">
                                                <div class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-400">
                                                    <i data-lucide="wallet" class="w-5 h-5"></i>
                                                </div>
                                            </div>
                                            <x-input-error :messages="$errors->get('monthly_salary')" />
                                            <p class="text-[10px] text-slate-400 italic font-medium ml-1">Utilisé comme base pour votre budget mensuel.</p>
                                        </div>
                                    </div>

                                    <div class="space-y-2 p-6 bg-slate-50 dark:bg-slate-900/50 rounded-3xl border border-slate-100 dark:border-slate-700/50">
                                        <label for="freelance_split" class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] flex items-center gap-2">
                                            <i data-lucide="pie-chart" class="w-4 h-4 text-primary"></i> Règle 50/30/20
                                        </label>
                                        <input id="freelance_split" name="freelance_split" type="text" value="{{ old('freelance_split', $user->freelance_split ?? '50/30/20') }}"
                                               class="w-full bg-white dark:bg-slate-800 border-none rounded-2xl h-14 px-6 font-bold text-primary focus:ring-2 focus:ring-primary/20 transition-all text-center text-xl tracking-widest">
                                        <x-input-error :messages="$errors->get('freelance_split')" />
                                        <p class="text-xs text-slate-500 leading-relaxed mt-2 text-center font-medium">
                                            Format: <span class="bg-primary/10 text-primary px-1.5 py-0.5 rounded">Besoins / Envies / Épargne</span>. Utile pour automatiser vos objectifs de virements.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Fixed Expenses -->
                            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-700/50 p-8 sm:p-10">
                                <div class="mb-10 flex items-center justify-between">
                                    <div>
                                        <h3 class="text-xl font-black text-slate-900 dark:text-white">Dépenses Fixes Par Défaut</h3>
                                        <p class="text-slate-500 dark:text-slate-400 text-sm">Valeurs pré-remplies lors de la création d'un nouveau budget.</p>
                                    </div>
                                    <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/20 text-primary rounded-2xl flex items-center justify-center">
                                        <i data-lucide="home" class="w-6 h-6"></i>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                                    @foreach([
                                        ['id' => 'loyer', 'label' => 'Loyer', 'icon' => 'home'],
                                        ['id' => 'eau_electricite', 'label' => 'Eau & Élec', 'icon' => 'zap'],
                                        ['id' => 'internet', 'label' => 'Internet', 'icon' => 'globe'],
                                        ['id' => 'nourriture', 'label' => 'Nourriture', 'icon' => 'shopping-cart'],
                                        ['id' => 'essence', 'label' => 'Transport', 'icon' => 'fuel'],
                                    ] as $field)
                                    <div class="space-y-2">
                                        <label for="{{ $field['id'] }}" class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-1.5 ml-1">
                                            <i data-lucide="{{ $field['icon'] }}" class="w-3 h-3"></i> {{ $field['label'] }}
                                        </label>
                                        <input id="{{ $field['id'] }}" name="{{ $field['id'] }}" type="number" step="0.01" value="{{ old($field['id'], $user->{$field['id']} ?? 0) }}"
                                               class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl h-12 px-5 font-bold text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-primary/20 transition-all">
                                        <x-input-error :messages="$errors->get($field['id'])" />
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Financial Goals -->
                            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-700/50 p-8 sm:p-10">
                                <div class="mb-10 flex items-center justify-between">
                                    <div>
                                        <h3 class="text-xl font-black text-slate-900 dark:text-white">Objectifs & État Initial</h3>
                                        <p class="text-slate-500 dark:text-slate-400 text-sm">Définissez vos cibles critiques à atteindre.</p>
                                    </div>
                                    <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 rounded-2xl flex items-center justify-center">
                                        <i data-lucide="target" class="w-6 h-6"></i>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="space-y-2">
                                        <label for="dette_initiale" class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Dette Initiale Globale</label>
                                        <input id="dette_initiale" name="dette_initiale" type="number" step="0.01" value="{{ old('dette_initiale', $user->dette_initiale ?? 0) }}"
                                               class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-2xl h-14 px-6 font-bold text-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all">
                                        <x-input-error :messages="$errors->get('dette_initiale')" />
                                        <p class="text-[10px] text-slate-400 italic ml-1">Le point de départ de votre désendettement.</p>
                                    </div>

                                    <div class="space-y-2">
                                        <label for="objectif_fonds_urgence" class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Cible Fonds d'Urgence</label>
                                        <input id="objectif_fonds_urgence" name="objectif_fonds_urgence" type="number" step="0.01" value="{{ old('objectif_fonds_urgence', $user->objectif_fonds_urgence ?? 500000) }}"
                                               class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-2xl h-14 px-6 font-bold text-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">
                                        <x-input-error :messages="$errors->get('objectif_fonds_urgence')" />
                                        <p class="text-[10px] text-slate-400 italic ml-1">L'épargne de sécurité à bâtir en priorité.</p>
                                    </div>
                                </div>

                                <div class="pt-10 flex justify-end">
                                    <button type="submit" class="px-10 py-4 bg-primary text-white font-black rounded-2xl shadow-xl shadow-primary/20 hover:scale-105 active:scale-95 transition-all flex items-center gap-3">
                                        <i data-lucide="save" class="w-5 h-5"></i>
                                        Enregistrer les données financières
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Security Tab -->
                    <div x-show="activeTab === 'security'" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
                        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-700/50 p-8 sm:p-10">
                            <div class="mb-10">
                                <h3 class="text-xl font-black text-slate-900 dark:text-white">Sécurité du Compte</h3>
                                <p class="text-slate-500 dark:text-slate-400 text-sm">Gérez votre mot de passe et vos paramètres d'accès.</p>
                            </div>

                            <form method="post" action="{{ route('password.update') }}" class="space-y-8">
                                @csrf @method('put')
                                
                                <div class="space-y-2">
                                    <label for="current_password" class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Mot de passe actuel</label>
                                    <input id="current_password" name="current_password" type="password" autocomplete="current-password"
                                           class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-2xl h-14 px-6 font-bold text-slate-800 dark:text-white focus:ring-2 focus:ring-primary/20 transition-all">
                                    <x-input-error :messages="$errors->updatePassword->get('current_password')" />
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="space-y-2">
                                        <label for="password" class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Nouveau mot de passe</label>
                                        <input id="password" name="password" type="password" autocomplete="new-password"
                                               class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-2xl h-14 px-6 font-bold text-slate-800 dark:text-white focus:ring-2 focus:ring-primary/20 transition-all">
                                        <x-input-error :messages="$errors->updatePassword->get('password')" />
                                    </div>
                                    <div class="space-y-2">
                                        <label for="password_confirmation" class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Confirmer le mot de passe</label>
                                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                                               class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-2xl h-14 px-6 font-bold text-slate-800 dark:text-white focus:ring-2 focus:ring-primary/20 transition-all">
                                        <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" />
                                    </div>
                                </div>

                                <div class="pt-6 flex justify-end">
                                    <button type="submit" class="px-10 py-4 bg-slate-900 dark:bg-slate-600 text-white font-black rounded-2xl hover:bg-slate-800 transition-all flex items-center gap-3">
                                        <i data-lucide="shield-check" class="w-5 h-5"></i>
                                        Mettre à jour le mot de passe
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Danger Tab -->
                    <div x-show="activeTab === 'danger'" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
                        <div class="bg-rose-50/50 dark:bg-rose-950/10 rounded-[2.5rem] border border-rose-100 dark:border-rose-900/30 p-8 sm:p-10">
                            <div class="flex flex-col md:flex-row items-center gap-8">
                                <div class="w-20 h-20 bg-rose-100 dark:bg-rose-900/40 text-rose-600 rounded-[2rem] flex items-center justify-center flex-shrink-0 animate-pulse">
                                    <i data-lucide="alert-octagon" class="w-10 h-10"></i>
                                </div>
                                <div class="flex-1 text-center md:text-left">
                                    <h3 class="text-xl font-black text-rose-900 dark:text-rose-400">Supprimer définitivement le compte</h3>
                                    <p class="text-rose-700/70 dark:text-rose-500/70 text-sm mt-2 font-medium leading-relaxed">
                                        Une fois votre compte supprimé, toutes vos ressources et données seront effacées de manière permanente.
                                        Veuillez télécharger toutes les données ou informations que vous souhaitez conserver.
                                    </p>
                                </div>
                            </div>

                            <div class="mt-10 p-8 bg-white dark:bg-slate-800 rounded-3xl border border-rose-100 dark:border-rose-900/30 shadow-sm shadow-rose-900/5">
                                <div x-data="{ confirmingUserDeletion: false }">
                                    <button @click="confirmingUserDeletion = true" x-show="!confirmingUserDeletion"
                                            class="w-full md:w-auto px-8 py-4 bg-rose-600 text-white font-black rounded-2xl shadow-xl shadow-rose-600/20 hover:bg-rose-700 transition-all">
                                        Supprimer mon compte
                                    </button>

                                    <div x-show="confirmingUserDeletion" x-transition class="space-y-6">
                                        <p class="text-sm font-bold text-slate-600 dark:text-slate-400 italic">Êtes-vous absolument sûr ? Saisissez votre mot de passe pour confirmer.</p>
                                        <form method="post" action="{{ route('profile.destroy') }}" class="flex flex-col md:flex-row gap-4">
                                            @csrf @method('delete')
                                            <input type="password" name="password" required placeholder="Confirmez avec votre mot de passe"
                                                   class="flex-1 bg-slate-50 dark:bg-slate-900 border-rose-200 dark:border-rose-900 border rounded-2xl h-14 px-6 font-bold text-slate-800 dark:text-white focus:ring-2 focus:ring-rose-500/20 transition-all">
                                            <div class="flex gap-3">
                                                <button type="button" @click="confirmingUserDeletion = false" class="px-6 py-4 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-2xl">Annuler</button>
                                                <button type="submit" class="px-6 py-4 bg-rose-600 text-white font-black rounded-2xl hover:bg-rose-700">Confirmer</button>
                                            </div>
                                        </form>
                                        <x-input-error :messages="$errors->userDeletion->get('password')" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">@csrf</form>

    @push('scripts')
    <script>
        // Lucide icons need to be re-initialized if we were using dynamic switching, 
        // but since we are using x-show it's fine.
        document.addEventListener('alpine:init', () => {
            lucide.createIcons();
        });
    </script>
    @endpush
</x-app-layout>

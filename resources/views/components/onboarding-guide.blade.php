@props(['userName' => ''])

<div x-data="{ 
        show: false, 
        step: 1, 
        totalSteps: 5,
        init() {
            if (!localStorage.getItem('financezen_onboarding_completed')) {
                setTimeout(() => { 
                    this.show = true;
                }, 1000);
            }
        },
        next() {
            if (this.step < this.totalSteps) {
                this.step++;
                this.$nextTick(() => { if(window.lucide) window.lucide.createIcons() });
            } else {
                this.complete();
            }
        },
        prev() {
            if (this.step > 1) {
                this.step--;
                this.$nextTick(() => { if(window.lucide) window.lucide.createIcons() });
            }
        },
        complete() {
            this.show = false;
            localStorage.setItem('financezen_onboarding_completed', 'true');
        },
        skip() {
            this.show = false;
            localStorage.setItem('financezen_onboarding_completed', 'true');
        }
    }" 
    x-show="show" 
    x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md">
    
    <div class="bg-white dark:bg-slate-800 w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden relative border border-slate-100 dark:border-slate-700 animate-in zoom-in slide-in-from-bottom-8 duration-500"
         @click.away="show = false">
        
        <!-- Top Banner Decoration -->
        <div class="h-24 bg-gradient-to-r from-primary to-indigo-600 relative overflow-hidden">
            <div class="absolute inset-0 opacity-20">
                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white"></path>
                </svg>
            </div>
            <div class="absolute inset-0 flex items-center justify-center">
                 <div class="w-16 h-16 bg-white/20 rounded-2xl backdrop-blur-md flex items-center justify-center text-white border border-white/30">
                    <i data-lucide="sparkles" class="w-8 h-8"></i>
                </div>
            </div>
        </div>

        <button @click="skip()" class="absolute top-4 right-6 text-white/70 hover:text-white transition z-10">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>

        <div class="p-8 pb-10">
            <div class="min-h-[220px]">
                <!-- Step 1: Welcome -->
                <div x-show="step === 1" class="text-center space-y-4">
                    <h2 class="text-2xl font-black text-slate-800 dark:text-white">Bienvenue sur FinanceZen !</h2>
                    <p class="text-slate-500 dark:text-slate-400 leading-relaxed">
                        Bonjour <strong>{{ $userName }}</strong> 👋. FinanceZen est votre compagnon pour une liberté financière totale. Voulez-vous un rapide tour du propriétaire ?
                    </p>
                    <div class="flex flex-wrap justify-center gap-2 pt-2">
                        <span class="px-3 py-1 bg-primary/10 text-primary rounded-full text-[10px] font-bold uppercase tracking-wider">🚀 Boostez votre épargne</span>
                        <span class="px-3 py-1 bg-success/10 text-success rounded-full text-[10px] font-bold uppercase tracking-wider">📉 Réduisez vos dettes</span>
                    </div>
                </div>

                <!-- Step 2: Profile -->
                <div x-show="step === 2" class="text-center space-y-4">
                    <div class="w-12 h-12 bg-indigo-500/10 rounded-xl flex items-center justify-center mx-auto text-indigo-500 mb-2">
                        <i data-lucide="user-cog" class="w-6 h-6"></i>
                    </div>
                    <h2 class="text-2xl font-black text-slate-800 dark:text-white">Le Profil</h2>
                    <p class="text-slate-500 dark:text-slate-400 leading-relaxed">
                        C'est ici que tout commence. Renseignez votre <strong>Revenu mensuel</strong> et vos <strong>Charges fixes</strong> (loyer, net, elec). 
                        Notre algorithme fera le reste pour vous !
                    </p>
                </div>

                <!-- Step 3: Budget -->
                <div x-show="step === 3" class="text-center space-y-4">
                    <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center mx-auto text-amber-500 mb-2">
                        <i data-lucide="pie-chart" class="w-6 h-6"></i>
                    </div>
                    <h2 class="text-2xl font-black text-slate-800 dark:text-white">Le Budget</h2>
                    <p class="text-slate-500 dark:text-slate-400 leading-relaxed">
                        Fixez-vous des limites ! Allouez un budget par catégorie (Courses, Loisirs...). 
                        Nous vous alerterons avec un système de feux tricolores si vous dépassez les bornes.
                    </p>
                </div>

                <!-- Step 4: Transactions -->
                <div x-show="step === 4" class="text-center space-y-4">
                    <div class="w-12 h-12 bg-emerald-500/10 rounded-xl flex items-center justify-center mx-auto text-emerald-500 mb-2">
                        <i data-lucide="plus-circle" class="w-6 h-6"></i>
                    </div>
                    <h2 class="text-2xl font-black text-slate-800 dark:text-white">Transactions</h2>
                    <p class="text-slate-500 dark:text-slate-400 leading-relaxed">
                        Ajoutez vos transactions quotidiennes. Simple, rapide et précis. 
                        C'est le seul moyen d'avoir des <strong>Rapports</strong> qui reflètent vraiment votre réalité.
                    </p>
                </div>

                <!-- Step 5: Start -->
                <div x-show="step === 5" class="text-center space-y-4">
                    <div class="w-16 h-16 bg-success/10 rounded-full flex items-center justify-center mx-auto text-success mb-2">
                        <i data-lucide="rocket" class="w-8 h-8"></i>
                    </div>
                    <h2 class="text-2xl font-black text-slate-800 dark:text-white">C'est parti !</h2>
                    <p class="text-slate-500 dark:text-slate-400 leading-relaxed">
                        Vous avez maintenant toutes les clés. Regardez votre épargne grandir et profitez d'une vie financière Zen.
                    </p>
                </div>
            </div>

            <!-- Footer Progress -->
            <div class="flex items-center justify-center gap-2 mt-2 py-4">
                <template x-for="i in totalSteps">
                    <div class="h-1.5 rounded-full transition-all duration-300" 
                         :class="step === i ? 'w-6 bg-primary' : 'w-2 bg-slate-200 dark:bg-slate-700'"></div>
                </template>
            </div>

            <!-- Actions -->
            <div class="mt-4 flex gap-3">
                <button x-show="step > 1" @click="prev()" class="flex-1 py-3.5 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-2xl hover:bg-slate-200 dark:hover:bg-slate-600 transition">
                    Retour
                </button>
                <button @click="next()" 
                    class="flex-[2] py-3.5 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/20 hover:bg-primary-dark transition"
                    x-text="step === totalSteps ? 'Finaliser' : 'Continuer'">
                </button>
            </div>
            
            <div class="mt-4 text-center">
                <button @click="skip()" class="text-xs font-bold text-slate-400 hover:text-slate-500 dark:hover:text-slate-300 transition uppercase tracking-widest">
                    Ignorer le guide
                </button>
            </div>
        </div>
    </div>
</div>

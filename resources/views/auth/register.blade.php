<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div class="space-y-1">
            <x-input-label for="name" value="Nom Complet" class="font-bold text-slate-700 ml-1" />
            <div class="relative group">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition"><i data-lucide="user" class="w-5 h-5"></i></div>
                <x-text-input id="name" class="block mt-1 w-full pl-12 rounded-2xl border-slate-200 focus:border-primary focus:ring-primary shadow-sm h-14 font-medium" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="space-y-1">
            <x-input-label for="email" value="Email" class="font-bold text-slate-700 ml-1" />
            <div class="relative group">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition"><i data-lucide="mail" class="w-5 h-5"></i></div>
                <x-text-input id="email" class="block mt-1 w-full pl-12 rounded-2xl border-slate-200 focus:border-primary focus:ring-primary shadow-sm h-14 font-medium" type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone (Téléphone) -->
        <div class="space-y-1">
            <x-input-label for="phone" value="Téléphone" class="font-bold text-slate-700 ml-1" />
            <div class="relative group">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition"><i data-lucide="phone" class="w-5 h-5"></i></div>
                <x-text-input id="phone" class="block mt-1 w-full pl-12 rounded-2xl border-slate-200 focus:border-primary focus:ring-primary shadow-sm h-14 font-medium" type="tel" name="phone" :value="old('phone')" autocomplete="tel" />
            </div>
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="space-y-1">
            <x-input-label for="password" value="Mot de passe" class="font-bold text-slate-700 ml-1" />
            <div class="relative group">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition"><i data-lucide="lock" class="w-5 h-5"></i></div>
                <x-text-input id="password" class="block mt-1 w-full pl-12 rounded-2xl border-slate-200 focus:border-primary focus:ring-primary shadow-sm h-14 font-medium"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="space-y-1">
            <x-input-label for="password_confirmation" value="Confirmer mot de passe" class="font-bold text-slate-700 ml-1" />
            <div class="relative group">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition"><i data-lucide="check-circle" class="w-5 h-5"></i></div>
                <x-text-input id="password_confirmation" class="block mt-1 w-full pl-12 rounded-2xl border-slate-200 focus:border-primary focus:ring-primary shadow-sm h-14 font-medium"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div>
            <x-primary-button class="w-full h-14 rounded-2xl bg-slate-900 border-none justify-center text-lg font-bold shadow-xl hover:bg-slate-800 transition tracking-wide active:scale-[0.98]">
                S'inscrire <i data-lucide="sparkles" class="w-5 h-5 ml-2"></i>
            </x-primary-button>
        </div>

        <div class="text-center pt-4 border-t border-slate-50">
            <p class="text-sm font-medium text-slate-500 italic">
                Déjà inscrit ? 
                <a href="{{ route('login') }}" class="text-primary font-bold hover:underline ml-1">Se connecter</a>
            </p>
        </div>
    </form>
</x-guest-layout>

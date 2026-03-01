<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="space-y-1">
            <x-input-label for="email" value="Email" class="font-bold text-slate-700 ml-1" />
            <div class="relative group">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition"><i data-lucide="mail" class="w-5 h-5"></i></div>
                <x-text-input id="email" class="block mt-1 w-full pl-12 rounded-2xl border-slate-200 focus:border-primary focus:ring-primary shadow-sm h-14 font-medium" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="space-y-1">
            <x-input-label for="password" value="Mot de passe" class="font-bold text-slate-700 ml-1" />
            <div class="relative group">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition"><i data-lucide="lock" class="w-5 h-5"></i></div>
                <x-text-input id="password" class="block mt-1 w-full pl-12 rounded-2xl border-slate-200 focus:border-primary focus:ring-primary shadow-sm h-14 font-medium"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded-lg border-slate-300 text-primary shadow-sm focus:ring-primary h-5 w-5" name="remember">
                <span class="ms-2 text-sm font-semibold text-slate-600">Se souvenir de moi</span>
            </label>
            
            @if (Route::has('password.request'))
                <a class="text-sm font-bold text-primary hover:text-primary-dark transition tracking-tight" href="{{ route('password.request') }}">
                    Oublié ?
                </a>
            @endif
        </div>

        <div>
            <x-primary-button class="w-full h-14 rounded-2xl bg-slate-900 border-none justify-center text-lg font-bold shadow-xl hover:bg-slate-800 transition tracking-wide active:scale-[0.98]">
                Connexion <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
            </x-primary-button>
        </div>

        <div class="text-center pt-4 border-t border-slate-50">
            <p class="text-sm font-medium text-slate-500 italic">
                Pas encore de compte ? 
                <a href="{{ route('register') }}" class="text-primary font-bold hover:underline ml-1">Créer un compte</a>
            </p>
        </div>
    </form>
</x-guest-layout>

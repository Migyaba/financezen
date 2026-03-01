<x-admin-layout>
    <x-slot name="header">Paramètres Globaux</x-slot>

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf @method('PUT')

        <!-- Général -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
            <h3 class="font-bold text-slate-800 dark:text-white mb-4 flex items-center gap-2"><i data-lucide="settings" class="w-5 h-5 text-primary"></i> Général</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Nom de l'application</label>
                    <input type="text" name="app_name" value="{{ $settings['app_name']->value ?? 'FinanceZen' }}" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-11 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Email de support</label>
                    <input type="email" name="support_email" value="{{ $settings['support_email']->value ?? '' }}" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-11 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Email de contact</label>
                    <input type="email" name="contact_email" value="{{ $settings['contact_email']->value ?? '' }}" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-11 focus:ring-primary">
                </div>
            </div>
        </div>

        <!-- Tarification -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
            <h3 class="font-bold text-slate-800 dark:text-white mb-4 flex items-center gap-2"><i data-lucide="banknote" class="w-5 h-5 text-success"></i> Tarification</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Prix mensuel (FCFA)</label>
                    <input type="number" name="subscription_price" value="{{ $settings['subscription_price']->value ?? 1000 }}" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-11 font-bold focus:ring-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Durée essai (jours)</label>
                    <input type="number" name="trial_days" value="{{ $settings['trial_days']->value ?? 7 }}" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-11 font-bold focus:ring-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Devise</label>
                    <input type="text" name="currency" value="{{ $settings['currency']->value ?? 'FCFA' }}" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-11 focus:ring-primary">
                </div>
            </div>
        </div>

        <!-- FedaPay -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
            <h3 class="font-bold text-slate-800 dark:text-white mb-4 flex items-center gap-2"><i data-lucide="credit-card" class="w-5 h-5 text-warning"></i> FedaPay</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Public Key</label>
                    <input type="text" name="fedapay_public_key" value="{{ $settings['fedapay_public_key']->value ?? '' }}" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-11 focus:ring-primary font-mono text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Secret Key</label>
                    <input type="password" name="fedapay_secret_key" value="{{ $settings['fedapay_secret_key']->value ?? '' }}" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-11 focus:ring-primary font-mono text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Mode</label>
                    <select name="fedapay_mode" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-11 focus:ring-primary">
                        <option value="sandbox" {{ ($settings['fedapay_mode']->value ?? '') == 'sandbox' ? 'selected' : '' }}>Sandbox</option>
                        <option value="live" {{ ($settings['fedapay_mode']->value ?? '') == 'live' ? 'selected' : '' }}>Live</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Webhook URL</label>
                    <input type="text" value="{{ url('/webhook/fedapay') }}" disabled class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-11 bg-slate-50 dark:bg-slate-950 text-slate-400 font-mono text-sm">
                </div>
            </div>
        </div>

        <!-- Email -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
            <h3 class="font-bold text-slate-800 dark:text-white mb-4 flex items-center gap-2"><i data-lucide="mail" class="w-5 h-5 text-danger"></i> Configuration Email</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">SMTP Host</label>
                    <input type="text" name="mail_host" value="{{ $settings['mail_host']->value ?? '' }}" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-11 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Port</label>
                    <input type="number" name="mail_port" value="{{ $settings['mail_port']->value ?? 465 }}" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-11 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Email expéditeur / Username</label>
                    <input type="email" name="mail_username" value="{{ $settings['mail_username']->value ?? '' }}" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-11 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Nom expéditeur</label>
                    <input type="text" name="mail_from_name" value="{{ $settings['mail_from_name']->value ?? 'FinanceZen' }}" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 h-11 focus:ring-primary">
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
            <h3 class="font-bold text-slate-800 dark:text-white mb-4 flex items-center gap-2"><i data-lucide="megaphone" class="w-5 h-5 text-indigo-500"></i> Notifications</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Message de maintenance</label>
                    <textarea name="maintenance_message" rows="2" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:ring-primary text-sm">{{ $settings['maintenance_message']->value ?? '' }}</textarea>
                </div>
                <div class="flex items-center gap-3 pt-6">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="registration_enabled" value="0">
                        <input type="checkbox" name="registration_enabled" value="1" class="sr-only peer" {{ ($settings['registration_enabled']->value ?? '1') == '1' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-checked:bg-primary rounded-full peer transition-colors after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                    </label>
                    <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Inscriptions ouvertes</span>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:bg-primary-dark transition flex items-center gap-2">
                <i data-lucide="save" class="w-5 h-5"></i> Sauvegarder
            </button>
        </div>
    </form>
</x-admin-layout>

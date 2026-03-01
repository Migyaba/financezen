<x-app-layout>
    <x-slot name="header">Contactez-nous</x-slot>

    <div class="max-w-3xl mx-auto py-12 px-6">
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-slate-100 dark:border-slate-700 p-8 md:p-12 relative overflow-hidden text-center">
            <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center text-primary mx-auto mb-6">
                <i data-lucide="mail" class="w-10 h-10"></i>
            </div>
            
            <h1 class="text-3xl font-black text-slate-800 dark:text-white mb-4">Besoin d'aide ?</h1>
            <p class="text-slate-500 max-w-lg mx-auto mb-8">Notre équipe est là pour vous assister. N'hésitez pas à nous envoyer un message pour toute question concernant FinanceZen.</p>
            
            <form action="#" method="POST" class="space-y-5 text-left max-w-xl mx-auto" onsubmit="event.preventDefault(); alert('Message envoyé avec succès ! Nous vous répondrons sous 24h.'); this.reset();">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Nom Complet</label>
                    <input type="text" required class="w-full rounded-xl border-slate-200 focus:ring-primary h-12" placeholder="Jean Dupont">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Email</label>
                    <input type="email" required class="w-full rounded-xl border-slate-200 focus:ring-primary h-12" placeholder="jean@example.com">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Message</label>
                    <textarea required rows="5" class="w-full rounded-xl border-slate-200 focus:ring-primary" placeholder="Comment pouvons-nous vous aider ?"></textarea>
                </div>
                <button type="submit" class="w-full py-4 bg-primary text-white font-bold rounded-2xl shadow-lg hover:bg-primary-dark transition text-lg mt-4">
                    Envoyer le message
                </button>
            </form>
            
            <div class="mt-12 pt-8 border-t border-slate-100 dark:border-slate-700 flex flex-col md:flex-row justify-center items-center gap-8">
                <div class="flex items-center gap-3 text-slate-600 dark:text-slate-300">
                    <i data-lucide="phone" class="w-5 h-5 text-primary"></i> <span class="font-bold">+229 00 00 00 00</span>
                </div>
                <div class="flex items-center gap-3 text-slate-600 dark:text-slate-300">
                    <i data-lucide="mail-open" class="w-5 h-5 text-primary"></i> <span class="font-bold">support@financezen.com</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

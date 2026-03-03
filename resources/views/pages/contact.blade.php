<x-public-layout :title="'Contactez-nous — FinanceZen'">
    <section class="py-16 md:py-24 px-4 sm:px-6">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-start">

                <!-- Colonne gauche : Informations -->
                <div>
                    <h1 class="text-3xl md:text-4xl font-black text-slate-900 mb-4 tracking-tight">Parlons de vos finances.</h1>
                    <p class="text-lg text-slate-500 mb-10 leading-relaxed">Une question sur FinanceZen ? Un problème technique ? Notre équipe vous répond sous 24 heures.</p>
                    
                    <div class="space-y-6">
                        <div class="flex items-start gap-4 p-5 bg-white rounded-2xl border border-slate-100 shadow-sm">
                            <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
                                <i data-lucide="mail" class="w-5 h-5 text-primary"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 mb-1">Par Email</h3>
                                <p class="text-sm text-slate-500">info@financezen.com</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4 p-5 bg-white rounded-2xl border border-slate-100 shadow-sm">
                            <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center shrink-0">
                                <i data-lucide="phone" class="w-5 h-5 text-emerald-600"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 mb-1">Par Téléphone</h3>
                                <p class="text-sm text-slate-500">+229 01 61 78 59 62</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4 p-5 bg-white rounded-2xl border border-slate-100 shadow-sm">
                            <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center shrink-0">
                                <i data-lucide="clock" class="w-5 h-5 text-amber-600"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 mb-1">Temps de réponse</h3>
                                <p class="text-sm text-slate-500">Nous répondons en moins de 24 heures, du lundi au samedi.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Colonne droite : Formulaire -->
                <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8 md:p-10">
                    <h2 class="text-xl font-black text-slate-800 mb-6">Envoyez-nous un message</h2>
                    <form action="#" method="POST" class="space-y-5" onsubmit="event.preventDefault(); alert('Message envoyé avec succès ! Nous vous répondrons sous 24h.'); this.reset();">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Nom Complet</label>
                            <input type="text" required class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary h-12 font-medium" placeholder="Jean Dupont">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Email</label>
                            <input type="email" required class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary h-12 font-medium" placeholder="jean@example.com">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Sujet</label>
                            <select class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary h-12 font-medium text-slate-600">
                                <option>Question générale</option>
                                <option>Problème technique</option>
                                <option>Abonnement et paiement</option>
                                <option>Suggestion d'amélioration</option>
                                <option>Autre</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Message</label>
                            <textarea required rows="5" class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary font-medium" placeholder="Décrivez votre demande..."></textarea>
                        </div>
                        <button type="submit" class="w-full py-4 bg-slate-900 text-white font-bold rounded-xl shadow-lg hover:bg-slate-800 transition text-base">
                            Envoyer le message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-public-layout>

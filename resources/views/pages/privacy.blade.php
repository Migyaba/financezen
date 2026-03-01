<!DOCTYPE html>
<html lang="fr" class="bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Politique de Confidentialité - FinanceZen</title>
    @vite(['resources/css/app.css'])
</head>
<body class="font-sans text-slate-800 antialiased p-8 md:p-20">
    <div class="max-w-3xl mx-auto bg-white rounded-3xl shadow-sm border border-slate-200 p-10 md:p-14">
        <a href="{{ route('welcome') }}" class="inline-flex items-center gap-2 text-primary font-bold hover:underline mb-8">
            ← Retour à l'accueil
        </a>
        <h1 class="text-4xl font-black text-slate-900 mb-8">Politique de Confidentialité</h1>
        
        <div class="prose prose-slate max-w-none">
            <p class="text-slate-500 mb-8">Dernière mise à jour : 28 Février 2026</p>

            <h2 class="text-xl font-bold mt-8 mb-4">1. Collecte des données</h2>
            <p>Nous collectons les informations suivantes lorsque vous utilisez FinanceZen : Nom, prénom, adresse e-mail, données de transaction financière saisies manuellement, et informations de connexion (mot de passe haché).</p>
            
            <h2 class="text-xl font-bold mt-8 mb-4">2. Utilisation des données</h2>
            <p>Vos données sont exclusivement utilisées pour le fonctionnement du service FinanceZen à savoir :</p>
            <ul class="list-disc pl-5 mb-4 space-y-2 text-slate-600">
                <li>Générer vos rapports financiers et graphiques (budgets, dettes, épargne).</li>
                <li>Authentifier votre accès.</li>
                <li>Gérer votre abonnement (via FedaPay de manière sécurisée).</li>
                <li>Vous envoyer des notifications systèmes importantes (reçus, alertes de budget).</li>
            </ul>
            
            <h2 class="text-xl font-bold mt-8 mb-4">3. Protection de vos données</h2>
            <p>FinanceZen met en œuvre toutes les mesures techniques et organisationnelles nécessaires pour garantir la sécurité de vos données financières. Nous ne vendons, ni ne louons, ni ne partageons vos données financières à des tiers à des fins publicitaires.</p>
            
            <h2 class="text-xl font-bold mt-8 mb-4">4. Vos droits</h2>
            <p>Conformément à la réglementation (RGPD), vous disposez d'un droit d'accès, de rectification et de suppression de vos données. Vous pouvez supprimer définitivement votre compte et l'intégralité de vos données directement depuis l'interface de "Mon Profil" > "Zone dangereuse".</p>
            
            <h2 class="text-xl font-bold mt-8 mb-4">5. Contact</h2>
            <p>Pour toute demande relative à la confidentialité de vos données : privacy@financezen.com</p>
        </div>
    </div>
</body>
</html>

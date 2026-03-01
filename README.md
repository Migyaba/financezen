<p align="center">
  <div style="background-color: #0f172a; padding: 2rem; border-radius: 1rem; text-align: center;">
    <h1 style="color: white; font-size: 3rem; margin-bottom: 0;">💸 FinanceZen</h1>
    <p style="color: #94a3b8; font-size: 1.2rem; margin-top: 0.5rem;">La Révolution Budgétaire Personnelle</p>
  </div>
</p>

## À propos de FinanceZen

**FinanceZen** est une application web moderne (SaaS) conçue pour aider les particuliers, entrepreneurs, et freelances à reprendre le contrôle de leurs finances personnelles. Fini les fichiers Excel complexes, FinanceZen propose une interface claire, des tableaux de bord interactifs et des outils de planification financière intuitifs pour bâtir sa prospérité financière jour après jour.

L'application intègre un système d'abonnement complet géré via **FedaPay** avec des rappels automatiques et une architecture multi-devises.

## ✨ Fonctionnalités Principales

*   📊 **Tableau de Bord Global :** Vue d'ensemble de la santé financière (Revenus, Dépenses, Dette restante, Épargne).
*   💰 **Gestion des Budgets :** Définition de budgets par catégorie (Loyer, Courses, Loisirs) avec suivi et alertes en direct.
*   💳 **Transactions Rapides :** Saisie ultra-rapide des flux avec catégorisation automatique.
*   📉 **Éradication des Dettes :** Planification des remboursements (Crédits, prêts personnels) et suivi visuel de l'avancement.
*   🎯 **Objectifs d'Épargne :** Fixation de cibles (Voyage, Voiture, Fonds d'urgence) avec barre de progression.
*   📑 **Rapports Analytiques :** Graphiques visuels précis sur les habitudes de dépenses et exports (PDF/CSV).
*   🛍️ **Système d'Abonnement SaaS :** Intégration FedaPay (Mobile Money & CB), avec formules mensuelles/annuelles, période d'essai de 7 jours et renouvellement automatique.
*   📱 **Interface 100% Responsive :** Utilisable fluidement sur Mobile, Tablette et Desktop.
*   🛡️ **Mode Administrateur :** Un panneau de contrôle exclusif pour gérer les utilisateurs, surveiller les revenus (MRR, Chiffre d'Affaires) et dépanner les abonnements.

## 🛠️ Stack Technique

*   **Backend :** [Laravel 11](https://laravel.com) (PHP 8.2+)
*   **Base de Données :** PostgreSQL
*   **Frontend :** Blade Templates, [Tailwind CSS 3](https://tailwindcss.com) & [Alpine.js](https://alpinejs.dev)
*   **Paiements :** API FedaPay
*   **Déploiement Continu :** GitHub Actions -> Transfert FTP sécurisé (o2switch)

## 🚀 Installation & Lancement en Local

### Prérequis
* PHP 8.2 ou supérieur
* Composer
* Node.js & NPM
* PostgreSQL

### Étapes d'installation

1. **Cloner le projet**
```bash
git clone https://github.com/Migyaba/financezen.git
cd financezen
```

2. **Installer les dépendances PHP et Node**
```bash
composer install
npm install
```

3. **Configurer l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```
*Configurez ensuite votre fichier `.env` avec vos identifiants PostgreSQL locaux et vos clés API de test FedaPay (`FEDAPAY_SECRET_KEY`, `FEDAPAY_PUBLIC_KEY`, etc.).*

4. **Préparer la Base de données**
```bash
# Lancer les migrations pour créer les tables
php artisan migrate

# Lancer le seeder pour insérer des catégories de budget par défaut
php artisan db:seed
```

5. **Compiler les ressources frontend (CSS/JS)**
```bash
# Pour le développement en temps réel
npm run dev

# Ou pour simuler la production
npm run build
```

6. **Lancer le serveur de développement Laravel**
```bash
php artisan serve
```
Le projet sera accessible sur `http://localhost:8000`.

## ⚙️ Déploiement en Production (CI/CD o2switch)

Ce projet est configuré pour se déployer **automatiquement** à chaque `push` sur la branche `main` via les Actions GitHub, vers un hébergement standard cPanel tel que o2switch.

### Configuration des Secrets GitHub
Pour que le déploiement fonctionne, vous devez déclarer 3 variables secrètes dans votre dépôt GitHub *(Settings > Secrets and variables > Actions)* :
* `FTP_SERVER` (l'IP ou le nom de votre serveur d'hébergement o2switch)
* `FTP_USERNAME` (votre identifiant cPanel)
* `FTP_PASSWORD` (votre mot de passe cPanel)

Dès qu'un code est validé sur `main`, GitHub se connecte en FTP, supprime les exceptions, compile le build Vite, et pousse les nouveautés dans `/financezen.miguelmissetcho.com/`.

*(N'oubliez pas d'exécuter `php artisan migrate` manuellement dans le Terminal o2switch lors de la première installation).*

## ⏱️ Tâches Planifiées (Cron Jobs)

FinanceZen utilise le planificateur de tâches de Laravel pour gérer les expirations d'abonnement et l'envoi d'emails. Sur votre serveur de production, vous devez ajouter cette tâche CRON globale s'exécutant **chaque minute** (`* * * * *`) :

```bash
cd /chemin/vers/votre/dossier/financezen && php artisan schedule:run >> /dev/null 2>&1
```

**Tâches internes exécutées automatiquement par Laravel :**
* `financezen:cleanup-trials-subs` : À minuit `00:05`, détecte et coupe tous les comptes dont l'abonnement ou la période d'essai est arrivée à terme.
* `financezen:send-reminders` : À `08:15` tous les jours, envoie des emails d'alerte aux utilisateurs (J-3 fin d'essai, J-1 coupure imminente, J-5 renouvellement, etc.).

## 👨‍💻 Créateur

Développé par **Miguel M.** pour révolutionner l'approche de la budgétisation.

---
*Ce projet est une solution complète, de l'inscription de l'utilisateur jusqu'au reversement transparent des abonnements dans la comptabilité de l'administrateur.*

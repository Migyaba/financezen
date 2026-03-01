# FinanceZen - Gestionnaire de Finances Personnelles

FinanceZen est une application web (SaaS) complète dédiée à la gestion des finances personnelles. Conçue pour offrir une alternative moderne et intuitive aux feuilles de calcul complexes, elle permet aux utilisateurs de reprendre le contrôle de leur budget, de suivre leurs dettes et de planifier leurs objectifs d'épargne.

L'application intègre un système d'abonnement complet géré via la passerelle de paiement FedaPay, avec des rappels automatiques et une gestion granulaire des profils utilisateurs.

---

## Fonctionnalités Principales de l'Application

### 1. Tableau de Bord Financier
- **Vue Macro-économique :** Affichage en temps réel du solde total, des revenus mensuels, des dépenses engagées, de la dette restante et des économies globales.
- **Rapports Graphiques :** Visualisation intuitive de la répartition des dépenses et de l'évolution financière grâce à l'intégration de bibliothèques graphiques.

### 2. Gestion des Budgets
- **Catégorisation :** Création et gestion de catégories budgétaires personnalisées ou prédéfinies (Logement, Alimentation, Transports, etc.).
- **Limites Mensuelles :** Définition de plafonds de dépenses pour chaque catégorie.
- **Suivi des Écarts :** Comparaison automatique entre le budget prévisionnel et les dépenses réelles saisies.

### 3. Saisie et Suivi des Transactions
- Enregistrement rapide des flux financiers (revenus et dépenses).
- Possibilité d'affecter une transaction à une catégorie budgétaire spécifique.
- Gestion des devises avec support initial pour le XOF (Franc CFA).

### 4. Éradication des Dettes
- Saisie des dettes (prêts bancaires, dettes personnelles, etc.) avec taux d'intérêt et date cible.
- Enregistrement des paiements (mensualités) liés à chaque dette.
- Jauge visuelle de la progression du remboursement.

### 5. Objectifs d'Épargne
- Définition de projets d'épargne (Fonds d'urgence, projet immobilier, vacances).
- Ciblage d'un montant et d'une date d'échéance.
- Suivi des contributions et visualisation du chemin restant à parcourir.

### 6. Mode Administrateur
- **Gestion des Utilisateurs :** Vue liste des inscrits, accès aux détails de leurs historiques d'abonnement, et possibilité d'ajuster leur rôle ou de désactiver leur compte.
- **Gestion des Abonnements :** Supervision globale des revenus (Chiffre d'Affaires, Revenu Mensuel Récurrent - MRR).
- **Validation Manuelle :** Possibilité de confirmer manuellement des paiements en attente et d'allonger la durée des abonnements exceptionnellement.

### 7. Module d'Abonnement (FedaPay)
- Période d'essai automatique de 7 jours offerte à l'inscription.
- Plans tarifaires (Mensuel, Annuel).
- Renouvellement, expiration et coupure d'accès automatisés (mécanisme de tâches en arrière-plan).

---

## Architecture et Stack Technique

Le projet repose sur une architecture robuste séparant la logique métier et l'interface utilisateur.

- **Framework Backend :** Laravel 11 (PHP 8.2+). Responsable de l'API web, du routage, de l'authentification (Laravel Breeze) et de la communication avec la base de données via l'ORM Eloquent.
- **Base de Données :** PostgreSQL. Permet des analyses temporelles avancées et des extractions robustes de statistiques.
- **Frontend :** 
  - Moteur de template : Laravel Blade.
  - Framework CSS : Tailwind CSS v3 pour un design moderne, épuré et 100% responsive.
  - Javascript : Alpine.js pour l'interactivité légère des composants sans surcharger le navigateur.
- **Passerelle de Paiement :** API FedaPay, avec système de webhook intégré pour écouter et valider les transactions entrantes côté serveur de manière asynchrone.

---

## Guide d'Installation pour le Développement Local

### Prérequis
- PHP 8.2 ou version ultérieure
- Composer (Gestionnaire de dépendances PHP)
- Node.js & NPM (Pour la compilation des assets)
- Un serveur PostgreSQL actif

### Procédure d'installation

1. **Récupération du code source**
```bash
git clone https://github.com/Migyaba/financezen.git
cd financezen
```

2. **Installation des dépendances**
```bash
composer install
npm install
```

3. **Configuration de l'Environnement (.env)**
Commencez par dupliquer le fichier d'exemple :
```bash
cp .env.example .env
```
Générez ensuite la clé d'application Laravel de sécurité :
```bash
php artisan key:generate
```
Ouvrez votre fichier `.env` nouvellement créé et configurez absolument les rubriques suivantes :
- `DB_CONNECTION=pgsql`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` : avec vos accès PostgreSQL locaux.
- `FEDAPAY_SECRET_KEY`, `FEDAPAY_PUBLIC_KEY` : avec vos clés de test FedaPay (mode Sandbox).

4. **Préparation de la Base de Données**
Lancez la création des tables et structure du projet :
```bash
php artisan migrate
```
Si vous souhaitez initialiser des catégories de budget par défaut (logement, santé, etc.) pour tester immédiatement l'outil :
```bash
php artisan db:seed
```

5. **Lancement du Serveur et Compilation**
Ouvrez un terminal pour compiler le Javascript et le CSS en temps réel :
```bash
npm run dev
```
Dans un second terminal, démarrez le serveur PHP local :
```bash
php artisan serve
```

L'application est maintenant active à l'adresse `http://localhost:8000`. Vous pouvez créer votre premier compte via la page d'inscription.

---

## Tâches Automatisées (Cron Jobs)

FinanceZen utilise le planificateur de commandes Artisan de Laravel pour effectuer la supervision des abonnements en arrière-plan (coupures, envois d'emails de relance).

Lors d'un déploiement ou en développement local, les tâches planifiées suivantes doivent être activées. 

**Liste des commandes métiers (`routes/console.php`) :**
- `php artisan financezen:cleanup-trials-subs` : À exécuter idéalement à minuit. Vérifie la table des abonnements et révoque les accès des comptes dont la limite de validité est échue.
- `php artisan financezen:send-reminders` : À exécuter quotidiennement. Analyse les dates et génère des courriels vers les utilisateurs nécessitant un appel à l'action (fin de période d'essai imminente, compte échu).

En production, ces commandes doivent être adossées au Cron system s'assurant de lancer `php artisan schedule:run` chaque minute.

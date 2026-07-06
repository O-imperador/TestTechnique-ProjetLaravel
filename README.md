# 🏡 Gestion de Réservations Immobilières - Test Technique

Une mini-application Laravel de gestion de réservations immobilières avec une logique métier stricte, un système de file d'attente, et un panneau d'administration.

Ce projet a été développé en utilisant :
- **Laravel 11** (PHP 8.3) & **MySQL**
- **Laravel Breeze** (Blade) pour l'authentification
- **Livewire 3** pour la réactivité côté client (Catalogue & Formulaires)
- **Filament v3** pour le panneau d'administration
- **TailwindCSS** pour le styling

---

## 🛠️ Installation & Lancement

Prérequis : `php >= 8.2`, `composer`, `npm`, et une base de données MySQL.

1. **Cloner le dépôt et installer les dépendances PHP :**
   ```bash
   git clone <votre-url-repo>
   cd Test_technique
   composer install
   ```

2. **Configurer l'environnement :**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Assurez-vous de renseigner vos identifiants MySQL dans le fichier `.env`.*

3. **Base de données et données de démonstration :**
   Exécutez les migrations et les Seeders pour générer un utilisateur de test et +10 biens immobiliers :
   ```bash
   php artisan migrate --seed
   ```
   *(Un compte test est créé par défaut : Email: `test@example.com` / Mot de passe : `password`)*

4. **Compiler les assets front-end :**
   ```bash
   npm install
   npm run build
   ```

5. **Lancer le serveur de développement et les Workers :**
   Ouvrez **deux** terminaux différents :
   - Terminal 1 (Serveur Web) : `php artisan serve`
   - Terminal 2 (File d'attente pour les emails) : `php artisan queue:work`

L'application est maintenant accessible sur `http://localhost:8000`.

---

## 🧪 Comment lancer les Tests

Le projet utilise **Pest PHP** pour s'assurer que la logique métier critique (calcul de prix et chevauchement de dates) est solide à 100%.

Pour lancer la suite de tests automatisée :
```bash
php artisan test
# ou
./vendor/bin/pest
```
*Note : Une intégration continue (GitHub Actions) est configurée dans `.github/workflows/tests.yml` pour lancer ces tests automatiquement à chaque push.*

---

## 🏗️ Choix Techniques & Architecture

### 1. Prévention des Double-Réservations (Anti-Chevauchement)
Au lieu de se reposer uniquement sur la validation classique des formulaires, la règle de chevauchement est vérifiée directement dans le `BookingService`.
- **Validation Front/Back :** Livewire et les Form Requests empêchent les requêtes malformées (date_fin > date_début, pas de dates passées).
- **Verrous Transactionnels (Pessimistic Locking) :** **Bonus** - Pour prévenir les "Race Conditions" (deux utilisateurs qui cliquent sur le bouton "Réserver" à la milliseconde près), l'application utilise une transaction de base de données avec `lockForUpdate()`. Cela verrouille la ligne du `Property` dans MySQL jusqu'à ce que la vérification de chevauchement soit terminée et la réservation sauvegardée.

### 2. Réactivité du Catalogue (Livewire)
Le système de recherche et de filtrage du catalogue (dates et texte) a été construit avec **Livewire**. Cela permet une expérience utilisateur fluide sans aucun rechargement de page. De même, le formulaire de réservation calcule le prix total en temps réel dès que les dates sont sélectionnées, conformément aux exigences.

### 3. File d'Attente pour E-mails (Queues)
L'envoi de l'e-mail de confirmation utilise les **Queues de Laravel**.
- `QUEUE_CONNECTION` est configuré sur `database`.
- Lorsqu'une réservation est confirmée, un Mailable Markdown est mis en file d'attente (`dispatch`). Cela permet à la page Web de charger instantanément sans attendre le serveur SMTP. Le daemon `queue:work` traite la tâche en arrière-plan.

### 4. Panneau d'Administration (Filament)
**Filament v3** a été choisi pour construire le panneau d'administration (accessible via `/admin`).
- Les administrateurs peuvent réaliser un CRUD complet sur les biens immobiliers.
- Une vue "Lecture Seule" a été créée pour les réservations, avec une action personnalisée **"Annuler"** permettant aux administrateurs d'annuler manuellement une réservation sans supprimer la ligne en base de données, conservant ainsi un historique propre.

### 5. Autorisations de l'Utilisateur (Policies)
La sécurité du tableau de bord utilisateur est gérée par une **Policy** (`BookingPolicy`). L'utilisateur ne peut voir et n'annuler que **ses propres** réservations.

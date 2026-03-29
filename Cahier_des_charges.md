# Cahier des Charges - ConcertHub

## 1. Présentation du Projet
**Nom du projet :** ConcertHub  
**Description :** ConcertHub est une plateforme web spécialisée dans la gestion, la promotion et la billetterie d'événements (concerts, festivals, etc.). Elle permet à des organisateurs de créer et gérer leurs événements, aux clients de découvrir ces événements et d'acheter des billets, et à un administrateur global de superviser l'ensemble de la plateforme.

## 2. Acteurs du Système (Rôles)
Le système prévoit trois types d'utilisateurs avec des niveaux de permissions distincts :
1. **L'Administrateur (Admin) :** Supervise la plateforme, les utilisateurs, et a une vue globale sur les statistiques financières et d'utilisation.
2. **L'Organisateur (Organizer) :** Propose et gère ses propres événements, définit les types de billets et suit ses ventes.
3. **Le Client (Client) :** Navigue sur la plateforme, consulte les événements, achète des billets et accède à son historique d'achats.

## 3. Fonctionnalités Attendues

### 3.1. Espace Visiteurs (Public)
- **Page d'accueil :** Mise en avant des événements à venir.
- **Catalogue des événements :** Affichage de tous les événements avec filtres et recherche.
- **Détails de l'événement :** Visualisation des informations d'un événement précis (date, lieu, description, affiches) et des billets disponibles.

### 3.2. Authentification et Gestion de compte
- Inscription et connexion avec vérification de l'adresse e-mail.
- Gestion du profil (mise à jour des informations personnelles, mot de passe).
- Redirection basée sur le rôle de l'utilisateur après la connexion.

### 3.3. Espace Administrateur
- **Tableau de bord :** Statistiques globales de la plateforme.
- **Gestion des utilisateurs :** Visualisation, activation/désactivation, modification, ou suppression des comptes utilisateurs (organisateurs, clients).
- **Statistiques organisateurs :** Suivi des performances par organisateur.
- **Gestion globale des événements :** Capacité de modérer, modifier ou supprimer n'importe quel événement.
- **Exportation :** Possibilité d'exporter les données relatives aux billets.

### 3.4. Espace Organisateur
- **Tableau de bord :** Synthèse des ventes, revenus et événements actifs de l'organisateur.
- **Gestion des événements (CRUD) :** Création, modification, consultation et suppression de ses propres événements.
- **Gestion des billets (Ticket Types) :** Définition de différents types de billets (VIP, Standard, etc.) avec leurs prix et capacités associés à un événement.

### 3.5. Espace Client
- **Tableau de bord :** Historique des achats, commandes récentes.
- **Processus de réservation et paiement (Checkout) :**
  - Ajout de billets au panier.
  - Saisie des informations de paiement et traitement de la transaction.
  - Page de succès et confirmation de la commande.
- **Acquisition du billet :** Génération et téléchargement des billets (souvent au format PDF) après l'achat. (Envoi par email automatisé des tickets).

## 4. Modèle de Données (Entités Principales)
L'architecture de la base de données s'articule autour des entités suivantes :
- **User :** Informations de connexion, rôle (admin, organizer, client) et profil de l'utilisateur.
- **Event :** Détails de l'événement (titre, description, dates, lieu), lié à un Organisateur.
- **TicketType :** Les catégories de billets liées à un événement (nom, prix, quantité disponible).
- **Order (Commande) :** Transaction globale effectuée par un client (date, montant total, statut).
- **OrderItem (Ligne de commande / Billet) :** Billet individuel associé à une commande et à un type de billet.
- **Payment :** Informations relatives au traitement du paiement lié à une commande.

## 5. Exigences Techniques
- **Framework Back-end :** Laravel (PHP)
- **Framework Front-end / Stylisation :** TailwindCSS, Blade (Moteur de template de Laravel).
- **Base de données :** MySQL ou équivalent relationnel supporté par l'ORM Eloquent.
- **Sécurité :** Protections standards (CSRF, XSS, requêtes préparées), hachage des mots de passe, Middleware de restriction par rôle.
- **Compatibilité :** Interface responsive (Adaptable mobile, tablette et bureau).

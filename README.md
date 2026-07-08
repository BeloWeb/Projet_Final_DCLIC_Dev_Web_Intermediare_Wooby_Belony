# LIVRES & VOUS — Bibliothèque en ligne

> **Projet Final — DCLIC Dev Web**
> Une application web dynamique de gestion de bibliothèque et de suivi de lecture conçue en PHP et MySQL.

---

## 📝 Description du Projet

**LIVRES & VOUS** est une plateforme web moderne qui permet aux utilisateurs de parcourir un catalogue d'ouvrages interactif, d'effectuer des recherches ciblées, et de gérer une liste de lecture personnalisée. Le système intègre un espace membre sécurisé, un catalogue dynamique classé par rayons, une fiche détaillée par œuvre, ainsi qu'un centre d'aide et un formulaire de contact connecté à la base de données.

---

## ✨ Fonctionnalités Principales

* **Accueil & Recherche Intégrée :** Barre de recherche dynamique par titre ou auteur et accès rapide aux différents rayons de la bibliothèque.


* **Catalogue Dynamique :** Filtrage en temps réel par catégorie (Romans, Sciences, Jeunesse, Poésie) avec gestion des cas de rayons vides.


* **Fiches Détail Avancées :** Affichage complet de l'œuvre (résumé, auteur, éditeur, exemplaires disponibles) accompagné d'une barre latérale interactive listant tous les ouvrages disponibles.


* **Espace Lecteur Sécurisé :** Système d'inscription et de connexion avec hachage sécurisé des mots de passe (`password_hash`) et gestion des sessions utilisateur.


* **Liste de Lecture Personnelle :** Possibilité pour les membres connectés d'ajouter des livres à leur collection et de suivre leur progression.


* **Formulaire de Contact Automatisé :** Système de messagerie avec validation stricte des données côté serveur et enregistrement sécurisé des messages en base de données.


* **Centre d'Aide (FAQ) :** Interface accordéon ergonomique utilisant la balise native HTML5 `<details>` pour répondre aux questions fréquentes.



---

## 🛠️ Technologies Utilisées

* **Backend :** PHP (Architecture modulaire avec inclusion de composants `head.php` et `footer.php`).


* **Base de données :** MySQL avec requêtes préparées via l'interface **PDO** pour contrer les injections SQL.


* **Frontend :** HTML5 sémantique, CSS3 (Grilles `grid`, Flexbox, et états interactifs).


* **Sécurité :** Authentification par session PHP, hachage `PASSWORD_DEFAULT`, et protection contre les failles XSS à l'aide de `htmlspecialchars()`.



---

## 🗄️ Structure de la Base de Données

Le projet s'appuie sur une base de données nommée `bibliotheque` contenant les tables clés suivantes :

### 1. Table `Lecteurs`

Stocke les informations d'authentification des utilisateurs.

* `id` (INT, Clé primaire, Auto-incrément)


* `nom` (VARCHAR)


* `prenom` (VARCHAR)


* `email` (VARCHAR, Unique)


* `mot_de_passe` (VARCHAR, Hash sécurisé)



### 2. Table `Livres`

Contient l'ensemble des ouvrages disponibles au catalogue.

* `id` (INT, Clé primaire)


* `titre` (VARCHAR)


* `auteur` (VARCHAR)


* `categorie` (VARCHAR : romans, sciences, jeunesse, poesie)


* `annee` (INT)


* `maison_edition` (VARCHAR)


* `image` (VARCHAR, lien ou nom du fichier image)


* `description` (TEXT, résumé de l'œuvre)


* `nombre_exemplaire` (INT)



### 3. Table `contacts`

Enregistre les messages envoyés depuis le support.

* `id` (INT, Clé primaire)
* `nom` (VARCHAR)


* `email` (VARCHAR)


* `sujet` (VARCHAR)


* `message` (TEXT)



---

## 📂 Architecture des Fichiers

```text
bibliotheque2/
│
├── index.php             # Page d'accueil (Recherche et rayons)
├── catalogue.php         # Liste des livres et filtres par catégorie
├── details.php           # Fiche détaillée d'un ouvrage et barre latérale
├── inscription.php       # Formulaire de création de compte lecteur
├── connexion.php         # Espace de connexion sécurisé
├── deconnexion.php       # Script de destruction de session
├── contact.php           # Formulaire de contact et support
├── aide.php              # FAQ / Centre d'aide
├── conditions.php        # Conditions générales d'utilisation
├── confidentialite.php   # Politique de protection des données
│
├── includes/
│   ├── db.php            # Instance de connexion PDO à la base de données
│   ├── head.php          # En-tête HTML commun et navigation
│   └── footer.php        # Pied de page et scripts communs
│
└── images/               # Dossier de stockage des couvertures de livres

```

---

## 🚀 Installation et Configuration

Pour faire tourner le projet localement avec **XAMPP** :

1. **Cloner ou copier** le dossier du projet dans votre répertoire de serveur local :
```bash
C:/xampp/htdocs/bibliotheque2

```


2. **Configurer la base de données :**
* Lancez MySQL depuis le panneau de contrôle XAMPP.
* Rendez-vous sur `http://localhost/phpmyadmin`.
* Créez une base de données nommée `bibliotheque`.
* Importez le fichier SQL du projet (ou créez les tables `Lecteurs`, `Livres` et `contacts` selon la structure ci-dessus).




3. **Vérifier la connexion :**
* Ajustez les identifiants de connexion (hôte, utilisateur, mot de passe) dans le fichier `includes/db.php` si nécessaire.




4. **Accéder à l'application :**
* Ouvrez votre navigateur et saisissez l'adresse : `http://localhost/bibliotheque2/`



---

## 👤 Auteur

* **Woody Belony** — *Développeur & Designer Graphique*

---

*Dernière mise à jour du projet : Juillet 2026.*

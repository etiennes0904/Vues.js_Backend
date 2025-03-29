# Headless CMS API

## Contexte

Cette API est conçue pour un CMS Headless permettant la création, la gestion et la distribution de contenus à travers une API REST sécurisée et performante. Le CMS est destiné aux éditeurs de contenu, administrateurs et développeurs front-end. Il permet une gestion centralisée des utilisateurs, contenus, commentaires et fichiers téléversés.

## Objectifs

- Fournir une API REST sécurisée et flexible pour la gestion des contenus.
- Permettre la distribution des contenus sur différents canaux (applications web, mobile).
- Garantir la traçabilité des modifications (dates de création et de mise à jour).
- Utiliser des UUID pour une identification unique des entités.

---

## Fonctionnalités

### Gestion des utilisateurs (`/api/users`)
- **GET** `/api/users` : Récupère la liste des utilisateurs.
- **POST** `/api/users` : Crée un nouvel utilisateur.
- **PUT** `/api/users/{uuid}` : Remplace les données d’un utilisateur.
- **DELETE** `/api/users/{uuid}` : Supprime un utilisateur.

### Gestion des contenus (`/api/contents`)
- **GET** `/api/contents` : Récupère la liste des contenus.
- **POST** `/api/contents` : Crée un nouveau contenu.
- **GET** `/api/contents/{slug}` : Récupère un contenu spécifique.
- **PUT** `/api/contents/{slug}` : Remplace un contenu.
- **DELETE** `/api/contents/{slug}` : Supprime un contenu.

### Gestion des commentaires (`/api/comments`)
- **GET** `/api/comments` : Récupère la liste des commentaires.
- **POST** `/api/comments` : Crée un nouveau commentaire.
- **PUT** `/api/comments/{uuid}` : Remplace un commentaire.
- **DELETE** `/api/comments/{uuid}` : Supprime un commentaire.

### Téléversement de fichiers (`/api/uploads`)
- **POST** `/api/uploads` : Téléverse un nouveau fichier.
- **GET** `/api/uploads/{uuid}` : Récupère les détails d’un fichier téléversé.

### Téléversement de fichiers CSV (`/api/csv_uploads`)
- **POST** `/api/csv_uploads` : Téléverse un fichier CSV.

### Connexion (`/api/login`)
- **POST** `/api/login` : Authentifie un utilisateur et génère un token d’accès.

---

## Données et règles métiers

### Modèles de données
#### Utilisateurs
- **Nom** : string
- **Prénom** : string
- **Email** : string (unique)
- **UUID**
- **Dates** : création, modification

#### Contenus
- **Titre** : string
- **Image de couverture** : URL
- **Balises meta** : title, description
- **Contenu** : texte enrichi
- **Slug** : auto-généré, unique
- **Tags** : liste de strings
- **Auteur** : UUID
- **Dates** : création, modification

#### Commentaires
- **Commentaire** : texte
- **Auteur** : UUID
- **Dates** : création, modification

### Règles de publication
#### Profils d’utilisateurs
1. **Administrateurs**
   - Gestion complète (création/édition/suppression) des contenus, utilisateurs et commentaires.
2. **Abonnés**
   - Lecture des contenus.
   - Gestion (création/édition/suppression) de leurs propres commentaires.
3. **Visiteurs**
   - Accès en lecture seule aux contenus.

---

## Contraintes techniques
- **Langage** : PHP 8.3
- **Framework** : Symfony 7.1 (ou supérieur)
- **Typage strict** : `declare(strict_types=1);`
- **Gestion des identifiants** : UUID

---

## Documentation de l'API

Pour plus d'informations, consultez la documentation complète [ici](https://localhost/api/docs#/).

---

## Installation

1. Clonez le dépôt :
   ```bash
   git clone git@github.com:RubenAlvedin/project.git

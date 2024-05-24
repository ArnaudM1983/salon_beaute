# Projet API de Gestion de Salons de Beauté

Ce projet est une API de gestion de salons de beauté développée avec Symfony. L'API permet la gestion des utilisateurs, l'authentification, ainsi que la gestion des informations et des chiffres d'affaires des salons de beauté. Voici une description des principaux contrôleurs et des points de terminaison disponibles.

## Utilisation

### Authentification

#### Enregistrement d'un utilisateur

- **URL** : `/register`
- **Méthode** : `POST`
- **Description** : Enregistre un nouvel utilisateur.
- **Paramètres** :
    - `email` : Email de l'utilisateur.
    - `password` : Mot de passe de l'utilisateur.
- **Exemple de requête** :
    ```json
    {
        "email": "use1r@example.com",
        "password": "password1"
    }
    ```
- **Réponse** :
    ```json
    {
        "message": "User registered successfully"
    }
    ```

#### Connexion d'un utilisateur

- **URL** : `/login`
- **Méthode** : `POST`
- **Description** : Authentifie un utilisateur et renvoie un token JWT.
- **Paramètres** :
    - `email` : Email de l'utilisateur.
    - `password` : Mot de passe de l'utilisateur.
- **Exemple de requête** :
    ```json
    {
        "email": "user1@example.com",
        "password": "password1"
    }
    ```
- **Réponse** :
    ```json
    {
        "token": "jwt-token"
    }
    ```

### Gestion du profil

#### Consultation et mise à jour du profil

- **URL** : `/profil`
- **Méthode** : `GET`, `PUT`
- **Description** : Permet de consulter et de mettre à jour les informations du salon de beauté associé à l'utilisateur authentifié.
- **Paramètres** (PUT) :
    - `nom` : Nom du salon.
    - `adresse` : Adresse du salon.
    - `date_ouverture` : Date d'ouverture du salon (format `Y-m-d`).
    - `nb_employes` : Nombre d'employés.
    - `nom_representant` : Nom du représentant.
    - `prenom_representant` : Prénom du représentant.
- **Exemple de requête (GET)** :
    ```json
    {
        "nom": "Salon de beauté 1",
        "adresse": "Adresse du salon 1",
        "date_ouverture": "2023-01-01",
        "nb_employes": 5,
        "nom_representant": "Nom",
        "prenom_representant": "Prénom"
    }
    ```
- **Réponse (PUT)** :
    ```json
    {
        "message": "Profile updated successfully"
    }
    ```

### Gestion du chiffre d'affaires

#### Consultation de l'historique

- **URL** : `/historique`
- **Méthode** : `GET`
- **Description** : Récupère l'historique des chiffres d'affaires pour l'utilisateur authentifié.
- **Exemple de réponse** :
    ```json
    [
        {
            "mois": "2023-01",
            "chiffre_affaires": "5000"
        },
        {
            "mois": "2023-02",
            "chiffre_affaires": "6000"
        }
    ]
    ```

#### Saisie d'un nouveau chiffre d'affaires

- **URL** : `/nouvelle-saisie`
- **Méthode** : `POST`
- **Description** : Enregistre un nouveau chiffre d'affaires pour le mois précédent.
- **Paramètres** :
    - `chiffre_affaires` : Chiffre d'affaires du mois précédent.
- **Exemple de requête** :
    ```json
    {
        "chiffre_affaires": "7000"
    }
    ```
- **Réponse** :
    ```json
    {
        "message": "Chiffre d'affaires enregistré avec succès"
    }
    ```

## Entités Principales

### User

- `id` : Identifiant unique.
- `email` : Email de l'utilisateur.
- `roles` : Rôles de l'utilisateur.
- `password` : Mot de passe haché.

### SalonDeBeaute

- `id` : Identifiant unique.
- `user` : Utilisateur associé.
- `nom` : Nom du salon.
- `adresse` : Adresse du salon.
- `date_ouverture` : Date d'ouverture du salon.
- `nb_employes` : Nombre d'employés.
- `nom_representant` : Nom du représentant.
- `prenom_representant` : Prénom du représentant.
- `region` : Région du salon.
- `departement` : Département du salon.

### ChiffreAffaires

- `id` : Identifiant unique.
- `user` : Utilisateur associé.
- `mois` : Mois du chiffre d'affaires.
- `chiffre_affaires` : Montant du chiffre d'affaires.

### Statistic

- `id` : Identifiant unique.
- `region` : Région.
- `departement` : Département.
- `averageCaRegion` : Chiffre d'affaires moyen de la région.
- `averageCaDepartement` : Chiffre d'affaires moyen du département.

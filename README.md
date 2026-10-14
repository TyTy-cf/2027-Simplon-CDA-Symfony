# Harmonia

## Prérequis

- Docker et Docker Compose

## Installation

```bash
# 1. Construire et démarrer les conteneurs
docker compose up -d --build

# 2. Installer les dépendances PHP
docker compose exec php composer install

# 3. Créer le fichier .env.local avec l'accès à la base
echo 'DATABASE_URL="mysql://root:root@db:3306/harmonia?serverVersion=10.6.25-MariaDB&charset=utf8mb4"' > .env.local

# 4. Créer la base et jouer les migrations
docker compose exec php php bin/console doctrine:database:create --if-not-exists
docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction

# 5. Charger les fixtures (données de test)
docker compose exec php php bin/console doctrine:fixtures:load --no-interaction
```

## Accès

| Service    | URL                   |
|------------|-----------------------|
| Site       | http://localhost:8000 |
| phpMyAdmin | http://localhost:9988 |

Base de données depuis l'hôte (client SQL) : `127.0.0.1:3506`, utilisateur `root`, mot de passe `root`.

> ⚠️ Dans `.env.local`, l'hôte est `db:3306` (nom du service Docker) et non `127.0.0.1:3506` :
> Symfony tourne dans le conteneur `php`, où `127.0.0.1` désigne le conteneur lui-même.

## Commandes utiles

```bash
# Démarrer / arrêter
docker compose up -d
docker compose down

# Voir les logs
docker compose logs -f php
docker compose logs -f db

# Ouvrir un terminal dans le conteneur PHP
docker compose exec php bash

# Console Symfony
docker compose exec php php bin/console <commande>

# Composer
docker compose exec php composer require <paquet>

# Créer une migration après modification d'une entité, puis l'appliquer
docker compose exec php php bin/console make:migration
docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction

# Recharger les fixtures (⚠️ vide la base)
docker compose exec php php bin/console doctrine:fixtures:load --no-interaction

# Vider le cache
docker compose exec php php bin/console cache:clear

# Lancer les tests
docker compose exec php php bin/phpunit
```

## Réinitialiser complètement la base

```bash
docker compose exec php php bin/console doctrine:database:drop --force
docker compose exec php php bin/console doctrine:database:create
docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction
docker compose exec php php bin/console doctrine:fixtures:load --no-interaction
```

## Charger le jeu de données SQL (`docker/harmonia.sql`)

Le dump ne contient que des `INSERT` : le schéma doit exister (migrations jouées) et les tables doivent être vides.

- Base de données locale
```bash
docker compose exec -T db mariadb -uroot -proot harmonia < docker/harmonia.sql
```

- Base de données test
```bash
docker compose exec php php bin/console doctrine:database:create --if-not-exists --env=test
docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction --env=test
docker compose exec -T db mariadb -uroot -proot harmonia_test < docker/harmonia.sql
```

# P6-Snowtrick

Projet 7 de mon parcours Développeur d'application PHP/Symfony chez OpenClassrooms.
Création d'une API via Symfony.

## Installation
__Etape 1__ : Cloner ce repo et mettre les fichiers à la racine du projet
__Etape 2__ : Configurez vos variables d'environnement tel que la connexion à la base de données ou votre serveur SMTP ou adresse mail dans le fichier .env.local qui devra être crée à la racine du projet en réalisant une copie du fichier .env.
__Etape 3__ : `composer install`
__Etape 4__ : `npm install`
__Etape 5__ : `php bin/console doctrine:database:create`
__Etape 6__ : `lancer les fixtures`
__Etape 7__ : `php bin/console doctrine:migrations:migrate`

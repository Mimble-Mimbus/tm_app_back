# USERS

Même mot de passe pour tous les utilisateurs : app1234
utilisateur admin : admin@dev.com

# INSTALLATION DU PROJET

si besoin modifier le .env.local

composer install
php bin/console make:migration
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load 
symfony server:start
npm run watch

# ENVIRONNEMENT DE TEST

si besoin ajouter une database dans le fichier .env.test

php bin/console --env=test doctrine:database:create
php bin/console --env=test doctrine:schema:create
php bin/console --env=test doctrine:fixtures:load 

Executer tous les tests : php bin/phpunit tests/
Executer une classe de test : php bin/phpunit tests/nom_fichier.php

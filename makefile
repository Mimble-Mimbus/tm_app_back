migrate:
		php bin/console doctrine:database:drop --force
		php bin/console doctrine:database:create
		php bin/console doctrine:m:m
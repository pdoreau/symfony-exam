install:
	composer install
	php bin/console doctrine:database:drop --force
	php bin/console doctrine:database:create
	php bin/console doctrine:schema:create
	php bin/console doctrine:migrations:sync-metadata-storage
	php bin/console doctrine:migrations:version --add --all -n
	php bin/console hautelook:fixtures:load -n

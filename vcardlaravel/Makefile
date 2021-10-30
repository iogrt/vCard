### loonix only

sail=./vendor/bin/sail

up:
	$(sail) up -d

down:
	$(sail) down

setup:
	composer require laravel/sail --dev
	php artisan sail:install
	composer dump-autoload
	$(sail) artisan migrate:fresh
	$(sail) artisan db:seed

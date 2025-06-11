.PHONY: up down restart build logs sh composer-install tests

up:
	docker-compose up -d

down:
	docker-compose down

restart:
	docker-compose restart

build:
	docker-compose build --no-cache

logs:
	docker-compose logs -f

sh:
	docker exec -it mtx_backend bash

composer-install:
	docker exec mtx_backend composer install

composer-update:
	docker exec mtx_backend composer update

tests:
	docker exec mtx_backend php bin/phpunit

cache-clear:
	docker exec mtx_backend php bin/console cache:clear

database-create:
	docker exec mtx_backend php bin/console doctrine:database:create

database-migrate:
	docker exec mtx_backend php bin/console doctrine:migrations:migrate --no-interaction
phpcs:
	docker exec -it mtx_backend bash -c "./vendor/bin/php-cs-fixer -v fix --diff"

phpstan: ## Run PHPStan static analysis
	docker exec -it mtx_backend bash -c "./vendor/bin/phpstan analyse src tests --memory-limit=512M"

setup:
	docker exec -it mtx_backend bash -c "./config.sh"

install: composer.lock
	composer install
	make setup

.PHONY: reset-db
reset-db-docker:
	@docker-compose up -d php-fpm || true
	docker exec php-fpm-php rm -rf var/cache/dev/*
	docker exec php-fpm-php bin/console doctrine:database:drop --force --if-exists
	docker exec php-fpm-php bin/console doctrine:database:create
	docker exec php-fpm-php bin/console doctrine:migrations:migrate --no-interaction

.PHONY: dev
dev:
	@docker-compose up -d || true
	docker exec php-fpm-php bash -c 'composer install'
	docker exec php-fpm-php bin/console doctrine:migrations:migrate --no-interaction
	docker exec php-fpm-php rm -rf var/cache/dev/*
	docker exec php-fpm-php bash -c 'yarn install --check-files && yarn run dev --watch'

.PHONY: setup
setup:
	cp docker-compose.yml.dist docker-compose.yml
	cp .env.dist .env
	@docker-compose up --build -d || true
	docker exec -it php-fpm-php bash -c 'composer install'
	docker exec -it php-fpm yarn install
	docker exec -it php-fpm ./bin/console doctrine:database:create --if-not-exists
	make reset-db-docker
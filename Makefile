
composer-install:
	@docker compose exec app /usr/local/bin/composer install --ignore-platform-reqs

up:
	@docker compose up -d --wait
	@$(MAKE) composer-install
	@docker compose exec app npm install

migrate:
	@docker compose exec app php bin/console doctrine:migrations:migrate -q

fixtures:
	@docker compose exec app php bin/console doctrine:fixtures:load -q

init:
	@docker compose build --no-cache --pull
	@$(MAKE) up
	@$(MAKE) migrate
	@$(MAKE) fixtures

re-init:
	@docker compose down --remove-orphans
	@docker compose rm -f -v
	@$(MAKE) init

down:
	@docker compose down

rebuild-db:
	@docker compose down -v
	@docker compose up -d --wait
	@$(MAKE) migrate
	@$(MAKE) fixtures

stop-all:
	@$(MAKE) down
	@docker container stop $(docker container ls -q)

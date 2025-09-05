# Levanta la arquitectura

file_selected := -f infrastructure/docker-compose.$(env).yml
environment := $(env)

up:
	@docker-compose $(file_selected) up -d

ps:
	@docker-compose $(file_selected) ps

down:
	@docker-compose $(file_selected) down

build:
	@docker-compose $(file_selected) build $(c)

restart:
	@docker-compose $(file_selected) restart $(c)

logs:
	@docker-compose $(file_selected) logs -f $(c)

logs_php:
	@docker-compose $(file_selected) exec -T php tail -f var/logs/$(environment).log

connect:
	@docker-compose $(file_selected) exec $(c) bash

connect_root:
	@docker-compose $(file_selected) exec -u root $(c) bash

copy_env_vars:
	cd infrastructure && cp .env.dist .env
	cd backend && cp .env.dist .env
	cd frontend && cp .env.dist .env

install: copy_env_vars up install_dependencies cache_clear update_database create_admin_user

install_dependencies:
	@docker-compose $(file_selected) exec -T backend composer install
	@docker-compose $(file_selected) exec -T frontend npm install

cache_clear: up
	@docker-compose $(file_selected) exec -T backend php bin/console cache:clear --env=dev
	@docker-compose $(file_selected) exec -T backend php bin/console cache:clear --env=prod
	@docker-compose $(file_selected) exec -T backend rm -rf var/cache/dev
	@docker-compose $(file_selected) exec -T backend rm -rf var/cache/prod
	@docker-compose $(file_selected) exec -T backend chown -R www-data:www-data var/
	@docker-compose $(file_selected) exec -T backend chown -R www-data:www-data public/
	@docker-compose $(file_selected) exec -T backend chmod 755 -R var/cache

diff_database: 
	@docker-compose $(file_selected) exec -T backend php bin/console doctrine:migrations:diff

update_database: up
	@docker-compose $(file_selected) exec -T backend php bin/console doctrine:migrations:migrate --all-or-nothing --no-interaction

create_admin_user:
	@docker-compose $(file_selected) exec -T backend php bin/console app:user:create-admin-user --sex=male --name=SuperAdmin --email=i92chpem@uco.es --password=12345678 --target_weight=80 --birthday=2001-07-20 --no-interaction

pull_code:
	git checkout develop
	git pull

deploy: down pull_code up install_dependencies update_database cache_clear
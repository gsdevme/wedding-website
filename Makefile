.PHONY: all
default: all;

init:
	docker-compose -f infrastructure/docker-compose.yml -f infrastructure/docker-compose.dev.yml --project-directory $(CURDIR) up

composer:
	docker-compose -f infrastructure/docker-compose.yml -f infrastructure/docker-compose.dev.yml --project-directory $(CURDIR) exec php composer install

composer-update:
	docker-compose -f infrastructure/docker-compose.yml -f infrastructure/docker-compose.dev.yml --project-directory $(CURDIR) exec php composer update

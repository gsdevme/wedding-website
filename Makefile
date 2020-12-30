.PHONY: all
default: all;

env=dev

build:
	docker-compose \
		-f infrastructure/docker-compose.yml \
		-f infrastructure/docker-compose.$(env).yml \
		--project-directory $(CURDIR) \
		build

build-prod:
	docker-compose \
		-f infrastructure/docker-compose.yml \
		-f infrastructure/docker-compose.prod.yml \
		--project-directory $(CURDIR) \
		build

start:
	docker-compose \
		-f infrastructure/docker-compose.yml \
		-f infrastructure/docker-compose.$(env).yml \
		--project-directory $(CURDIR) \
		up -d

restart:
	docker-compose \
		-f infrastructure/docker-compose.yml \
		-f infrastructure/docker-compose.$(env).yml \
		--project-directory $(CURDIR) \
		restart

shell:
	docker-compose \
		-f infrastructure/docker-compose.yml \
		-f infrastructure/docker-compose.dev.yml \
		--project-directory $(CURDIR) exec php bash

logs:
	docker-compose \
		-f infrastructure/docker-compose.yml \
		-f infrastructure/docker-compose.$(env).yml \
		--project-directory $(CURDIR) \
		logs -f php

server-dump:
	docker-compose \
		-f infrastructure/docker-compose.yml \
		-f infrastructure/docker-compose.$(env).yml \
		--project-directory $(CURDIR) exec php bin/console server:dump

sync-vendor-local:
	docker cp wedding_phpfpm:/srv/app/vendor .

stop:
	docker-compose \
		-f infrastructure/docker-compose.yml \
		-f infrastructure/docker-compose.$(env).yml \
		--project-directory $(CURDIR) \
		down --remove-orphans --volumes


push: build-prod
	docker push gsdevme/wedding-website:prod

assets:
	docker-compose -f infrastructure/docker-compose.assets.yml --project-directory $(CURDIR) build && docker push gsdevme/wedding-website-assets:prod

# composer:
# 	docker-compose -f infrastructure/infrastructure/docker-compose.yml -f infrastructure/infrastructure/docker-compose.dev.yml --project-directory $(CURDIR) exec php composer install
#
# composer-update:
# 	docker-compose -f infrastructure/infrastructure/docker-compose.yml -f infrastructure/infrastructure/docker-compose.dev.yml --project-directory $(CURDIR) exec php composer update

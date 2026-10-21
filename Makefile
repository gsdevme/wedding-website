.PHONY: build start stop logs test push assets-extract

COMPOSE = docker compose -f infrastructure/docker-compose.yml
ASSETS_IMAGE = docker.io/gsdevme/wedding-website-assets:prod

build:
	$(COMPOSE) build

start: build
	$(COMPOSE) up -d

stop:
	$(COMPOSE) down --remove-orphans

logs:
	$(COMPOSE) logs -f

test:
	./infrastructure/smoke-test.sh http://localhost:8080

push: build
	docker push ghcr.io/gsdevme/wedding-website:prod

# Re-extracts the original CSS/JS/images from the last published assets
# image. Only needed if site/assets is ever lost; the result is committed.
assets-extract:
	docker pull $(ASSETS_IMAGE)
	id=$$(docker create $(ASSETS_IMAGE)) && \
		docker cp "$$id":/usr/share/nginx/html/assets site/assets-extracted && \
		docker rm "$$id"
	@echo "Extracted to site/assets-extracted; merge what you need into site/assets"

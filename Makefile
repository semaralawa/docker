# Makefile
.PHONY: help build up down rebuild shell composer artisan \
  migrate migrate-fresh logs test db-shell restart set-app

COMPOSE_FILE := docker-compose.dev.yml
PHP_VER ?= 7.4

help:
	@echo "Make targets:"
	@echo "  make build           Build images"
	@echo "  make up              Build (if needed) and start containers"
	@echo "  make down            Stop and remove containers"
	@echo "  make rebuild         Rebuild images and restart"
	@echo "  make shell           Open bash shell in app container"
	@echo "  make composer args=  Run composer inside app (args optional)"
	@echo "  make artisan args=   Run php artisan (args optional)"
	@echo "  make migrate         Run artisan migrate --force"
	@echo "  make migrate-fresh   Run artisan migrate:fresh --seed --force"
	@echo "  make logs service=   Tail logs for a service (default all)"
	@echo "  make db-shell        Open mysql client to DB container"
	@echo "  make test            Run phpunit (inside app)"
	@echo "  make set-app APP=    Replace APPNAME with provided app name in $(COMPOSE_FILE)"
	@echo
	@echo "Override PHP version: make up PHP_VER=8.1"

build:
	@echo "Building with PHP_VERSION=$(PHP_VER)"
	PHP_VERSION=$(PHP_VER) \
	docker compose -f $(COMPOSE_FILE) build --pull

up:
	PHP_VERSION=$(PHP_VER) \
	docker compose -f $(COMPOSE_FILE) up -d

down:
	docker compose -f $(COMPOSE_FILE) down

rebuild: down build up

shell:
	docker compose -f $(COMPOSE_FILE) exec app bash

composer:
ifndef args
	ARGS=install
else
	ARGS=$(args)
endif
	docker compose -f $(COMPOSE_FILE) run --rm app composer $(ARGS)

artisan:
ifndef args
	ARGS=help
else
	ARGS=$(args)
endif
	docker compose -f $(COMPOSE_FILE) exec app php artisan $(ARGS)

migrate:
	docker compose -f $(COMPOSE_FILE) exec app php artisan migrate --force

migrate-fresh:
	docker compose -f $(COMPOSE_FILE) exec app php artisan migrate:fresh --seed --force

logs:
ifndef service
	service=all
endif
	@if [ "$(service)" = "all" ]; then \
	  docker compose -f $(COMPOSE_FILE) logs -f --tail=200; \
	else \
	  docker compose -f $(COMPOSE_FILE) logs -f --tail=200 $(service); \
	fi
db-shell:
	@echo "Connecting to MySQL container..."
	docker compose -f $(COMPOSE_FILE) exec db mysql -u$${DB_USERNAME:-laravel} \
	  -p$${DB_PASSWORD:-secret} $${DB_DATABASE:-laravel}

test:
	docker compose -f $(COMPOSE_FILE) exec app ./vendor/bin/phpunit --colors=always

restart:
	docker compose -f $(COMPOSE_FILE) restart

# replace placeholder APPNAME with provided app name in docker-compose file
set-app:
ifndef APP
	$(error APP is required. Usage: make set-app APP=myapp)
endif
	@echo "Setting app name to '$(APP)' in $(COMPOSE_FILE)"
	@grep -q 'APPNAME' $(COMPOSE_FILE) || { echo "No 'APPNAME' found in $(COMPOSE_FILE). Nothing to change."; exit 0; }
	sed -i 's/APPNAME/$(APP)/g' $(COMPOSE_FILE)
	@echo "Done. Updated $(COMPOSE_FILE)."
.PHONY: up
up:
	docker compose up --detach

.PHONY: down
down:
	docker compose down --remove-orphans

.PHONY: rebuild
rebuild: down
	docker compose up --build --detach

.PHONY: install-deps
install-deps: up
	docker compose exec php composer install

.PHONY: test
test:
	docker compose exec php vendor/bin/phpunit

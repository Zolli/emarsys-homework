.PHONY: up
up:
	docker compose up -d

.PHONY: down
down:
	docker compose down --remove-orphans

.PHONY: install-deps
install-deps: up
	docker compose exec php composer install

.PHONY: test
test:
	docker compose exec php vendor/bin/phpunit

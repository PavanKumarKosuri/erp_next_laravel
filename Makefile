.PHONY: help build up down restart logs shell migrate seed fresh install test
.DEFAULT_GOAL := help

help: ## Show this help message
	@echo 'Usage: make [target]'
	@echo ''
	@echo 'Targets:'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  %-15s %s\n", $$1, $$2}' $(MAKEFILE_LIST)

build: ## Build Docker containers
	docker-compose build --no-cache

up: ## Start the application
	docker-compose up -d

down: ## Stop the application
	docker-compose down

restart: ## Restart the application
	make down
	make up

logs: ## View application logs
	docker-compose logs -f

shell: ## Access application shell
	docker-compose exec app bash

migrate: ## Run database migrations
	docker-compose exec app php artisan migrate

migrate-fresh: ## Fresh migrate with seed
	docker-compose exec app php artisan migrate:fresh --seed

seed: ## Run database seeders
	docker-compose exec app php artisan db:seed

install: ## Install dependencies
	docker-compose exec app composer install
	docker-compose exec app npm install

test: ## Run tests
	docker-compose exec app php artisan test

cache-clear: ## Clear all caches
	docker-compose exec app php artisan cache:clear
	docker-compose exec app php artisan config:clear
	docker-compose exec app php artisan route:clear
	docker-compose exec app php artisan view:clear

permissions: ## Fix file permissions
	docker-compose exec app chown -R www-data:www-data /var/www/html/storage
	docker-compose exec app chown -R www-data:www-data /var/www/html/bootstrap/cache

setup: ## Initial setup for development
	cp .env.example .env
	make build
	make up
	make install
	docker-compose exec app php artisan key:generate
	make migrate
	make permissions
	@echo "Setup complete! Visit http://localhost:8080"

create-admin: ## Create admin user for Filament
	docker-compose exec app php artisan make:filament-user

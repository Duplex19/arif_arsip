# 🐳 Docker Management
.PHONY: help build up down restart shell artisan migrate seed test logs ps

help: ## Show this help
	@echo "🚀 E-Arsip DKPP Way Kanan - Docker Commands"
	@echo ""
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | \
		awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-20s\033[0m %s\n", $$1, $$2}'

build: ## Build Docker images
	docker compose build --no-cache

up: ## Start all services (detached)
	docker compose up -d

down: ## Stop all services
	docker compose down

restart: ## Restart all services
	docker compose restart

rebuild: down build up ## Rebuild from scratch

shell: ## SSH into app container
	docker compose exec app sh

artisan: ## Run artisan command (e.g., make artisan cmd="route:list")
	docker compose exec app php artisan $(cmd)

migrate: ## Run database migrations
	docker compose exec app php artisan migrate --force

seed: ## Run database seeders
	docker compose exec app php artisan db:seed --force

migrate-fresh: ## Fresh migrate + seed
	docker compose exec app php artisan migrate:fresh --force --seed

test: ## Run tests
	docker compose exec app php artisan test --compact

logs: ## Follow container logs
	docker compose logs -f

ps: ## Show running containers
	docker compose ps

optimize: ## Cache Laravel for production
	docker compose exec app php artisan optimize
	docker compose exec app php artisan view:cache
	docker compose exec app php artisan config:cache
	docker compose exec app php artisan route:cache

storage-link: ## Create storage symlink
	docker compose exec app php artisan storage:link --force

queue: ## Run queue worker
	docker compose exec app php artisan queue:work --tries=3 --timeout=90

npm-build: ## Build frontend assets inside container
	docker compose exec app npm run build

cache-clear: ## Clear all caches
	docker compose exec app php artisan optimize:clear

octane-status: ## Check Octane status
	docker compose exec app php artisan octane:status

octane-reload: ## Reload Octane workers
	docker compose exec app php artisan octane:reload

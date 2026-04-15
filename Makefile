COMPOSE ?= docker compose
APP_SERVICE ?= app
DB_SERVICE ?= db
NODE ?= npm

APP_EXEC = $(COMPOSE) exec -T $(APP_SERVICE)
DB_EXEC = $(COMPOSE) exec -T $(DB_SERVICE)

.DEFAULT_GOAL := help

.PHONY: help env build up setup start stop down restart logs shell composer-install app-key migrate seed fresh storage-link cache-clear optimize-clear test test-filter quality format assets ci route-list observability-up observability-down db-shell

help: ## Lista os comandos disponiveis.
	@awk 'BEGIN {FS = ":.*##"; printf "\nComandos disponiveis:\n"} /^[a-zA-Z0-9_-]+:.*##/ {printf "  make %-18s %s\n", $$1, $$2}' $(MAKEFILE_LIST)
	@printf "\nFluxo inicial recomendado: make setup\n"

env: ## Cria o .env a partir do .env.example quando ele ainda nao existe.
	@if [ ! -f .env ]; then \
		cp .env.example .env; \
		echo ".env criado a partir do .env.example"; \
	else \
		echo ".env ja existe"; \
	fi

build: ## Builda as imagens Docker do projeto.
	$(COMPOSE) build

up: setup ## Sobe o projeto completo, migrado e seedado.

setup: env build start composer-install app-key migrate seed storage-link cache-clear ## Prepara tudo e deixa a aplicacao pronta para uso.
	@echo "Aplicacao pronta em http://localhost:8989"

start: ## Sobe os containers em background.
	$(COMPOSE) up -d

stop: ## Para os containers sem remover volumes.
	$(COMPOSE) stop

down: ## Remove containers e rede, preservando volumes nomeados.
	$(COMPOSE) down

restart: down start ## Reinicia os containers.

logs: ## Acompanha logs dos containers.
	$(COMPOSE) logs -f

shell: ## Abre um shell dentro do container da aplicacao.
	$(COMPOSE) exec $(APP_SERVICE) sh

db-shell: ## Abre o cliente MySQL no container do banco.
	$(DB_EXEC) mysql -ularavel -psecret laravel

composer-install: ## Instala dependencias PHP dentro do container.
	$(APP_EXEC) composer install --no-interaction --prefer-dist --optimize-autoloader

app-key: ## Gera APP_KEY quando necessario.
	@if ! grep -q '^APP_KEY=base64:' .env; then \
		$(APP_EXEC) php artisan key:generate --ansi --force; \
	else \
		echo "APP_KEY ja configurada"; \
	fi

migrate: ## Executa migrations.
	$(APP_EXEC) php artisan migrate --force

seed: ## Executa seeders.
	$(APP_EXEC) php artisan db:seed --force

fresh: ## Recria o banco do zero e roda seeders.
	$(APP_EXEC) php artisan migrate:fresh --seed --force

storage-link: ## Cria o link simbolico de storage.
	$(APP_EXEC) php artisan storage:link || true

cache-clear: ## Limpa caches do Laravel.
	$(APP_EXEC) php artisan optimize:clear

optimize-clear: cache-clear ## Alias para limpar caches.

test: ## Roda toda a suite de testes.
	$(APP_EXEC) php artisan test

test-filter: ## Roda testes filtrando por FILTER="nome_do_teste".
	$(APP_EXEC) php artisan test --filter="$(FILTER)"

quality: ## Valida Composer e estilo PHP com Pint.
	$(APP_EXEC) composer validate --strict --no-check-publish
	$(APP_EXEC) ./vendor/bin/pint --test app config database routes tests

format: ## Corrige estilo PHP com Pint.
	$(APP_EXEC) ./vendor/bin/pint app config database routes tests

assets: ## Instala dependencias JS e builda assets do Vite usando Node local.
	@if [ -f package-lock.json ]; then \
		$(NODE) ci; \
	else \
		$(NODE) install --no-package-lock; \
	fi
	$(NODE) run build

ci: quality test assets ## Roda qualidade, testes e build de assets.

route-list: ## Lista rotas da aplicacao.
	$(APP_EXEC) php artisan route:list

observability-up: ## Sobe stack de observabilidade.
	$(COMPOSE) --profile observability up -d

observability-down: ## Para stack de observabilidade.
	$(COMPOSE) --profile observability down

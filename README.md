# Laravel Multitenancy Single Database

Sistema SaaS multi-tenant para gestao veterinaria, usando Laravel 10, MySQL, Redis, Docker e arquitetura single database com isolamento por `tenant_id`.

O projeto esta sendo estruturado para atender quatro areas:

- Plataforma SaaS: painel usado pelo dono do sistema para administrar clinicas, planos, assinaturas e suporte.
- Suporte: painel usado pela equipe interna para ajudar clinicas sem acessar a administracao principal.
- Painel da clinica: painel usado pela clinica que aluga o sistema para gerenciar clientes, pets, agenda, prontuarios, estoque e financeiro.
- Portal do cliente final: area planejada para tutores/responsaveis acompanharem pets, agendamentos e documentos.

## Requisitos

- Docker
- Docker Compose
- Make
- Node.js e npm para build dos assets front-end

O PHP e o Composer rodam dentro do container `app`.
O frontend React separado fica em `frontend/`.

## Como Rodar O Projeto

### Setup completo

```bash
make setup
```

Esse comando:

- cria `.env` a partir de `.env.example`, caso ainda nao exista;
- builda as imagens Docker;
- sobe os containers;
- instala dependencias PHP com Composer;
- gera `APP_KEY`, se ainda nao existir;
- roda migrations;
- roda seeders;
- cria `storage:link`;
- limpa caches do Laravel.

Depois acesse:

```txt
Backend Laravel: http://localhost:8989
Frontend React:  http://localhost:5173
```

### Comandos principais

```bash
make help        # lista todos os comandos disponiveis
make up          # roda o setup completo
make start       # apenas sobe os containers
make stop        # para os containers
make down        # remove containers e rede
make restart     # reinicia os containers
make logs        # acompanha logs
make shell       # abre shell no container app
make db-shell    # abre shell MySQL
make frontend-dev # sobe o servico frontend
```

### Banco, migrations e seeds

```bash
make migrate     # roda migrations
make seed        # roda seeders
make fresh       # recria o banco com migrate:fresh --seed
```

### Testes e qualidade

```bash
make test        # roda php artisan test
make quality     # composer validate + audit + Pint + PHPStan/Larastan + PHP-CS-Fixer
make format      # corrige estilo com Pint
make stan        # roda analise estatica com PHPStan/Larastan
make cs-fixer    # verifica regras do PHP-CS-Fixer sem alterar arquivos
make cs-check    # verifica regras do PHP-CS-Fixer sem alterar arquivos
make cs-fix      # corrige estilo com PHP-CS-Fixer
make security-audit # verifica vulnerabilidades conhecidas do Composer
make ci          # quality + test + assets
```

Para filtrar testes:

```bash
make test-filter FILTER=AccessSeparationTest
```

### Assets front-end

```bash
make assets
```

Esse comando instala dependencias JS em `frontend/` e roda:

```bash
npm run build
```

Comandos especificos do frontend:

```bash
make frontend-install
make frontend-dev
make frontend-lint
make frontend-build
```

## Servicos Docker

Servicos principais:

- Aplicacao: `http://localhost:8989`
- Frontend React: `http://localhost:5173`
- MySQL: porta `3306`
- RabbitMQ Management: `http://localhost:15672` (`laravel` / `secret`)
- Redis: usado por filas/cache conforme configuracao

Servicos de observabilidade, quando habilitados:

- Prometheus: `http://localhost:9090`
- Grafana: `http://localhost:3000` (`admin` / `admin`)
- Loki: `http://localhost:3100`
- Alloy: `http://localhost:12345`
- cAdvisor: `http://localhost:8080`

Para subir observabilidade:

```bash
make observability-up
```

Para parar:

```bash
make observability-down
```

## Primeiro Superusuario

O painel da plataforma usa usuarios da tabela `users` com `tenant_id = null`.

Para criar o primeiro superusuario, defina no `.env`:

```env
SUPER_ADMIN_NAME="Seu Nome"
SUPER_ADMIN_EMAIL="voce@example.com"
SUPER_ADMIN_PASSWORD="senha-segura"

SUPPORT_USER_NAME="Suporte"
SUPPORT_USER_EMAIL="suporte@example.com"
SUPPORT_USER_PASSWORD="senha-segura"
```

Depois rode:

```bash
make seed
```

Ou, em um banco novo:

```bash
make fresh
```

Esse usuario acessa:

```txt
/platform/dashboard
```

O usuario de suporte acessa:

```txt
/support/dashboard
```

## Areas Do Sistema

### Plataforma SaaS

Area usada pelo dono do sistema.

```txt
/platform
/platform/dashboard
```

Responsabilidades:

- administrar clinicas/tenants;
- administrar planos;
- administrar assinaturas;
- consultar auditoria;
- gerenciar usuarios globais;
- dar suporte aos clientes SaaS.

Middleware:

```txt
auth
platform
```

Regra:

```txt
users.tenant_id = null
```

### Painel Da Clinica

Area usada pela clinica que aluga o sistema.

```txt
/app
/app/dashboard
```

Responsabilidades:

- clientes/tutores;
- pets/pacientes;
- agenda;
- prontuarios;
- vacinas;
- internacoes;
- produtos;
- estoque;
- financeiro operacional;
- usuarios da clinica.

Middleware:

```txt
auth
tenant
```

Regra:

```txt
users.tenant_id preenchido
```

### Painel De Suporte

Area usada pela equipe interna de suporte.

```txt
/support
/support/dashboard
```

Responsabilidades:

- consultar contexto de clinicas;
- entender problemas reportados por clientes;
- apoiar atendimento e investigacao;
- acessar indicadores operacionais basicos.

Restricoes:

- nao acessa `/platform`;
- nao administra planos;
- nao administra assinaturas;
- nao cria ou remove tenants;
- nao gerencia superusuarios.

Middleware:

```txt
auth
support
```

Regra:

```txt
users.tenant_id = null
role = support
```

### Portal Do Cliente Final

Area planejada para tutores/responsaveis.

```txt
/portal
```

Estado atual:

- possui pagina base;
- possui API de status;
- ainda nao possui autenticacao propria.

Regra futura:

- criar uma tabela propria, como `client_users` ou `portal_users`;
- vincular esse usuario a `tenant_id` e `client_id`;
- permitir acesso apenas aos proprios pets, documentos e agendamentos.

## APIs

### API Da Plataforma

```txt
/api/platform/v1
```

Exemplos:

```txt
/api/platform/v1/tenants
/api/platform/v1/subscription-plans
/api/platform/v1/tenant-subscriptions
/api/platform/v1/audit-logs
/api/platform/v1/users
/api/platform/v1/roles
/api/platform/v1/permissions
```

Middlewares:

```txt
auth:sanctum
platform
```

### API Da Clinica

```txt
/api/clinic/v1
```

Exemplos:

```txt
/api/clinic/v1/clients
/api/clinic/v1/pets
/api/clinic/v1/appointments
/api/clinic/v1/medical-records
/api/clinic/v1/products
/api/clinic/v1/financial-transactions
```

Middlewares:

```txt
auth:sanctum
tenant
```

### API Do Portal

```txt
/api/portal/v1/status
```

Essa API ainda e apenas um marcador da area do portal.

## Frontend React

O frontend separado fica em:

```txt
frontend/
```

Tecnologias:

```txt
React
TypeScript
Vite
```

Arquivo de configuracao local:

```txt
frontend/.env.example
```

Variavel principal:

```env
VITE_API_BASE_URL=http://localhost:8989
```

O cliente HTTP fica em:

```txt
frontend/src/services/api.ts
```

Ele usa Laravel Sanctum com cookies:

```txt
GET /sanctum/csrf-cookie
POST /api/auth/login
GET /api/auth/me
POST /api/auth/logout
```

Contrato detalhado entre frontend e backend:

```txt
docs/frontend-api-contract.md
```

## Modelo De Acesso

O sistema usa a tabela `users` para dois tipos de usuarios administrativos:

```txt
tenant_id = null      -> usuario da plataforma
tenant_id preenchido  -> usuario da clinica
```

Clientes da clinica nao sao usuarios administrativos:

```txt
Client = tutor/responsavel
Pet    = paciente veterinario
```

Resumo:

```txt
Platform User
  administra o SaaS
  acessa /platform

Support User
  ajuda clientes sem acessar a administracao principal
  acessa /support

Tenant
  representa a clinica que aluga o sistema

Tenant User
  administra a clinica
  acessa /app

Client
  tutor/responsavel cadastrado pela clinica

Pet
  paciente vinculado ao Client
```

## Multi-Tenancy

O projeto usa single database com coluna `tenant_id`.

Models operacionais da clinica usam:

```php
App\Tenant\Traits\TenantTrait
```

Esse trait registra:

- `TenantScope`: aplica filtro automatico por `tenant_id`;
- `TenantObserver`: preenche `tenant_id` ao criar registros.

Arquivos principais:

```txt
app/Tenant/Traits/TenantTrait.php
app/Scope/Tenant/TenantScope.php
app/TenantObserver/TenantObserver.php
app/Tenant/TenantManager.php
```

Na pratica:

- usuario de clinica ve apenas dados do proprio tenant;
- novos registros operacionais recebem `tenant_id` automaticamente;
- usuario da plataforma nao recebe esse filtro, pois administra dados globais.

## Estrutura Tecnica

### Models

Os models Eloquent ficam em:

```txt
app/Models
```

Principais grupos:

- Plataforma: `Tenant`, `SubscriptionPlan`, `TenantSubscription`, `AuditLog`, `Integration`
- Usuarios e permissoes: `User`, `Role`, `Permission`
- Clinica: `Branch`, `Client`, `Pet`, `Document`, `CommunicationMessage`
- Clinico: `Appointment`, `MedicalRecord`, `MedicalRecordEntry`, `Vaccine`, `PetVaccine`, `Hospitalization`
- Backoffice: `Service`, `PriceTable`, `PriceTableItem`, `Product`, `Supplier`, `Brand`, `InventoryLocation`, `InventoryMovement`, `FinancialAccount`, `FinancialTransaction`

### Modulos

A pasta `app/Modules` organiza dominios da aplicacao:

```txt
app/Modules/Platform
app/Modules/Clinic
app/Modules/Clinical
app/Modules/Backoffice
app/Modules/Shared
```

Direcao de organizacao:

- `Platform`: regras do SaaS
- `Clinic`: operacao da clinica
- `Clinical`: fluxo clinico veterinario
- `Backoffice`: estoque, financeiro e cadastros operacionais
- `Shared`: contratos, DTOs, helpers e traits compartilhados

### Camadas Service E Repository

CRUD generico criado para o esqueleto:

```txt
app/Repositories/Contracts/CrudRepositoryInterface.php
app/Repositories/Eloquent/CrudRepository.php
app/Services/CrudService.php
app/Http/Controllers/Api/V1/CrudController.php
```

Responsabilidades:

- Controller: recebe HTTP e retorna resposta
- Service: orquestra regra de aplicacao
- Repository: conversa com Eloquent

Controllers API basicos ficam em:

```txt
app/Http/Controllers/Api/V1
```

### Controllers Web

Areas web:

```txt
app/Http/Controllers/Platform
app/Http/Controllers/Support
app/Http/Controllers/Clinic
app/Http/Controllers/Portal
```

Views:

```txt
resources/views/platform
resources/views/support
resources/views/clinic
resources/views/portal
```

### Rotas

Rotas web:

```txt
routes/web.php
routes/platform.php
routes/support.php
routes/clinic.php
routes/portal.php
```

Rotas API:

```txt
routes/api.php
```

Para listar rotas:

```bash
make route-list
```

## Testes

Pasta de testes:

```txt
tests/Feature
tests/Feature/Api
tests/Unit
tests/Unit/Architecture
tests/Unit/Repositories
tests/Unit/Services
```

Testes importantes:

- separacao de acesso entre plataforma e clinica;
- rotas API da plataforma, clinica e portal;
- isolamento multi-tenant;
- camada CRUD service/repository;
- autenticacao;
- posts de exemplo por tenant.

Rodar tudo:

```bash
make test
```

## Qualidade E CI/CD

Pipeline GitHub Actions:

```txt
.github/workflows/ci-cd.yml
```

Jobs:

- `build`: instala dependencias, prepara app e builda assets;
- `tests`: sobe MySQL e roda `php artisan test`;
- `code-quality`: valida Composer, roda audit, Pint, PHPStan/Larastan, PHP-CS-Fixer e lint do frontend.

Localmente:

```bash
make quality
make ci
```

Ferramentas PHP de qualidade:

- Pint: formatador principal do Laravel;
- PHPStan com Larastan: analise estatica do codigo Laravel;
- PHP-CS-Fixer: verificacao complementar de padroes de estilo;
- Composer audit: checagem de vulnerabilidades conhecidas.

Observacao sobre versao: o container atual usa PHP 8.1, entao o projeto usa Larastan `^2.0`. O Larastan `^3.0` exige PHP `^8.2`.

## Documentacao Complementar

- [`docs/access-separation.md`](docs/access-separation.md): separacao entre plataforma, clinica e portal.
- [`docs/user-onboarding-and-areas.md`](docs/user-onboarding-and-areas.md): como cadastrar e usar superadmin, suporte, clinica e cliente final.
- [`docs/frontend-api-contract.md`](docs/frontend-api-contract.md): como o frontend autentica e chama APIs por tipo de usuario.
- [`docs/architecture-veterinary-saas.md`](docs/architecture-veterinary-saas.md): arquitetura modular do SaaS veterinario.

## Fluxo Recomendado Para Desenvolvimento

1. Subir ambiente:

```bash
make setup
```

2. Criar ou atualizar banco:

```bash
make migrate
```

3. Rodar testes:

```bash
make test
```

4. Verificar qualidade:

```bash
make quality
```

5. Buildar assets quando mexer no front:

```bash
make assets
```

## Observacoes

- O banco local usa MySQL via Docker.
- A estrategia multi-tenant atual e single database com `tenant_id`.
- O portal do cliente final ainda esta como esqueleto tecnico.
- O RabbitMQ esta disponivel no Docker, mas os jobs Laravel seguem usando Redis por padrao.
- O projeto usa Pint como formatador principal, PHP-CS-Fixer como checagem complementar e PHPStan/Larastan para analise estatica.

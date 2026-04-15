# Separacao De Acessos E Paineis

Este documento define a separacao entre a administracao da plataforma SaaS, o painel administrativo da clinica e o portal do cliente final.

## Objetivo

O sistema possui tres publicos diferentes:

- Plataforma: administradores do SaaS, ou seja, a empresa dona do produto.
- Clinica: clientes que alugam o sistema e usam o painel para operar a clinica.
- Cliente final: tutores/responsaveis pelos pets atendidos pela clinica.

Cada publico deve ter rotas, permissoes e limites de dados proprios.

## Nomenclatura Oficial

### Platform User

Usuario administrativo da plataforma.

Regras:

- Fica na tabela `users`.
- Possui `tenant_id = null`.
- Acessa o painel `/platform`.
- Administra tenants, planos, assinaturas, usuarios, suporte e auditoria.

Exemplos de papeis futuros:

- `super-admin`
- `platform-admin`
- `support`
- `billing-admin`

### Tenant

Clinica, pet shop ou empresa que aluga o sistema.

Regras:

- Fica na tabela `tenants`.
- E o eixo principal do isolamento multi-tenant.
- Possui usuarios administrativos, clientes/tutores, pets, agenda, financeiro e estoque.

### Tenant User

Usuario administrativo da clinica.

Regras:

- Fica na tabela `users`.
- Possui `tenant_id` preenchido.
- Acessa o painel `/app`.
- Enxerga apenas dados do proprio tenant por meio de `TenantTrait`, `TenantScope` e `TenantObserver`.

Exemplos de papeis futuros:

- `clinic-owner`
- `clinic-admin`
- `veterinarian`
- `receptionist`
- `finance`
- `inventory`

### Client

Cliente/tutor da clinica.

Regras:

- Fica na tabela `clients`.
- Possui `tenant_id`.
- Representa a pessoa responsavel pelo animal.
- Nao deve ser confundido com o cliente SaaS da plataforma. Cliente SaaS e `Tenant`; cliente da clinica e `Client`.

### Pet

Paciente veterinario.

Regras:

- Fica na tabela `pets`.
- Pertence a um `Client`.
- Tambem possui `tenant_id` para isolamento e consultas eficientes.

### Portal User

Usuario futuro do portal do cliente final.

Regras planejadas:

- Nao deve usar a mesma autenticacao dos usuarios administrativos da clinica.
- Deve ter uma tabela propria no futuro, por exemplo `client_users` ou `portal_users`.
- Deve se vincular a `tenant_id` e `client_id`.
- Deve acessar apenas dados do proprio tutor.

## Areas Web

### `/platform`

Painel da empresa dona da plataforma.

Rotas atuais:

```txt
/platform/dashboard
```

Arquivo de rotas:

```txt
routes/platform.php
```

Middleware:

```txt
auth
platform
```

Controller inicial:

```txt
App\Http\Controllers\Platform\DashboardController
```

### `/app`

Painel administrativo da clinica.

Rotas atuais:

```txt
/app/dashboard
/app/posts
```

Arquivo de rotas:

```txt
routes/clinic.php
```

Middleware:

```txt
auth
tenant
```

Controller inicial:

```txt
App\Http\Controllers\Clinic\DashboardController
```

### `/portal`

Base do portal do tutor/cliente final.

Rotas atuais:

```txt
/portal
```

Arquivo de rotas:

```txt
routes/portal.php
```

Controller inicial:

```txt
App\Http\Controllers\Portal\HomeController
```

Observacao:

O portal ainda nao possui login proprio. O middleware `client.portal` ja existe como marcador tecnico e retorna `501 Not Implemented` ate a autenticacao propria ser criada.

### `/dashboard`

Rota de compatibilidade.

Ela redireciona o usuario autenticado para a area correta:

```txt
Platform User -> /platform/dashboard
Tenant User   -> /app/dashboard
```

## Areas API

### `/api/platform/v1`

API administrativa da plataforma.

Rotas principais:

```txt
/api/platform/v1/tenants
/api/platform/v1/subscription-plans
/api/platform/v1/tenant-subscriptions
/api/platform/v1/audit-logs
/api/platform/v1/users
/api/platform/v1/roles
/api/platform/v1/permissions
/api/platform/v1/integrations
```

Middlewares:

```txt
auth:sanctum
platform
```

### `/api/clinic/v1`

API operacional da clinica.

Rotas principais:

```txt
/api/clinic/v1/clients
/api/clinic/v1/pets
/api/clinic/v1/appointments
/api/clinic/v1/medical-records
/api/clinic/v1/products
/api/clinic/v1/financial-transactions
/api/clinic/v1/services
/api/clinic/v1/branches
```

Middlewares:

```txt
auth:sanctum
tenant
```

### `/api/portal/v1`

API planejada para o portal do tutor.

Rota atual:

```txt
/api/portal/v1/status
```

Essa area ainda nao manipula dados sensiveis porque falta implementar a autenticacao propria do cliente final.

## Middlewares

### `platform`

Classe:

```txt
App\Http\Middleware\EnsurePlatformUser
```

Permite acesso apenas quando:

```php
$user->tenant_id === null
```

### `tenant`

Classe:

```txt
App\Http\Middleware\EnsureTenantUser
```

Permite acesso apenas quando:

```php
$user->tenant_id !== null
```

### `client.portal`

Classe:

```txt
App\Http\Middleware\EnsureClientPortalAccess
```

Estado atual:

```txt
501 Not Implemented
```

Uso futuro:

- Validar usuario do portal.
- Garantir vinculo com `tenant_id`.
- Garantir vinculo com `client_id`.

## Decisao Sobre `users.tenant_id`

`users.tenant_id` agora e nullable.

Motivo:

- Usuario da plataforma nao pertence a nenhuma clinica.
- Usuario da clinica sempre pertence a um tenant.

Regra:

```txt
tenant_id = null     -> plataforma
tenant_id preenchido -> clinica
```

## Isolamento Multi-Tenant

Models operacionais da clinica usam:

```txt
App\Tenant\Traits\TenantTrait
```

Esse trait aplica:

- `TenantScope`: filtra consultas pelo `tenant_id` do usuario autenticado.
- `TenantObserver`: preenche `tenant_id` automaticamente ao criar registros.

Com isso, um `Tenant User` cria e consulta apenas dados da propria clinica.

Um `Platform User` nao recebe filtro automatico, pois precisa administrar dados globais da plataforma.

## Fluxo De Login

Depois do login ou cadastro:

```txt
Platform User -> route('platform.dashboard')
Tenant User   -> route('clinic.dashboard')
```

Essa regra fica em:

```txt
App\Models\User::homeRoute()
```

## Criacao Do Primeiro Superusuario

Existe um seeder opcional para criar o primeiro usuario da plataforma:

```txt
Database\Seeders\PlatformAdminSeeder
```

Defina no `.env`:

```txt
SUPER_ADMIN_NAME="Seu Nome"
SUPER_ADMIN_EMAIL="voce@example.com"
SUPER_ADMIN_PASSWORD="senha-segura"
```

Depois rode:

```bash
php artisan db:seed
```

O usuario sera criado com:

```txt
tenant_id = null
```

Por isso ele acessa:

```txt
/platform/dashboard
```

## Proximo Passo Recomendado

Para concluir o portal do cliente final, criar:

```txt
client_users
```

Campos sugeridos:

```txt
id
tenant_id
client_id
name
email
password
email_verified_at
remember_token
created_at
updated_at
```

Depois:

- Criar guard `client`.
- Criar login separado em `/portal/login`.
- Criar middleware real para `client.portal`.
- Criar rotas como `/portal/pets`, `/portal/appointments` e `/portal/documents`.
- Garantir que todo acesso consulte apenas registros do `client_id` autenticado.

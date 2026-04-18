# Contrato Frontend E API

Este documento descreve como o frontend React deve autenticar usuarios, identificar a area correta e enviar requisicoes para a API Laravel.

## Estrutura Atual

O projeto esta separado em:

```txt
/
  app/                 Backend Laravel
  routes/              Rotas web/API do Laravel
  database/            Migrations e seeders
  tests/               Testes do backend
  frontend/            SPA React + TypeScript
```

Nesta fase, o Laravel continua na raiz do repositorio e o React fica em `frontend/`.

## URLs Locais

```txt
Frontend React: http://localhost:5173
Backend API:    http://localhost:8989
```

Variaveis relevantes:

```env
APP_URL=http://localhost:8989
FRONTEND_URL=http://localhost:5173
SANCTUM_STATEFUL_DOMAINS=localhost:5173,localhost:8989,127.0.0.1:5173,127.0.0.1:8989
SESSION_DOMAIN=localhost
```

No frontend:

```env
VITE_API_BASE_URL=http://localhost:8989
```

## Autenticacao SPA Com Sanctum

O frontend usa cookies de sessao via Laravel Sanctum.

Fluxo obrigatorio:

1. Buscar cookie CSRF:

```http
GET /sanctum/csrf-cookie
```

2. Enviar login ou cadastro com:

```txt
credentials: include
X-XSRF-TOKEN: valor do cookie XSRF-TOKEN
Accept: application/json
Content-Type: application/json
```

3. Usar os endpoints autenticados sempre com:

```txt
credentials: include
```

No frontend, isso esta centralizado em:

```txt
frontend/src/services/api.ts
```

## Login

Endpoint:

```http
POST /api/auth/login
```

Payload:

```json
{
  "email": "admin@example.com",
  "password": "password",
  "remember": true
}
```

Resposta:

```json
{
  "data": {
    "id": 1,
    "name": "Super Admin",
    "email": "admin@example.com",
    "tenant_id": null,
    "roles": ["super-admin"],
    "area": "platform",
    "home_path": "/platform/dashboard"
  }
}
```

O campo `area` define qual area o frontend deve liberar.

Valores possiveis:

```txt
platform
support
clinic
```

## Cadastro De Clinica

Endpoint:

```http
POST /api/auth/register
```

Esse endpoint cria um novo `Tenant`, provisiona os modulos padrao do segmento e cria o primeiro usuario administrativo do tenant.

Payload:

```json
{
  "tenant_name": "Clinica Central",
  "segment_slug": "veterinary",
  "name": "Dra. Ana",
  "email": "ana@example.com",
  "password": "password",
  "password_confirmation": "password"
}
```

`segment_slug` define o segmento inicial do tenant. Segmentos disponiveis podem ser carregados em:

```http
GET /api/segments
```

Depois do cadastro, o backend habilita os modulos `core` e os modulos padrao do segmento.

Resposta:

```json
{
  "data": {
    "id": 10,
    "name": "Dra. Ana",
    "email": "ana@example.com",
    "tenant_id": 1,
    "roles": [],
    "area": "clinic",
    "home_path": "/app/dashboard"
  }
}
```

## Usuario Atual

Endpoint:

```http
GET /api/auth/me
```

Uso:

- restaurar sessao ao abrir o frontend;
- descobrir area do usuario;
- mostrar dados do usuario logado;
- decidir quais menus ficam disponiveis.

Resposta:

```json
{
  "data": {
    "id": 1,
    "name": "Suporte",
    "email": "suporte@example.com",
    "tenant_id": null,
    "roles": ["support"],
    "area": "support",
    "home_path": "/support/dashboard"
  }
}
```

## Logout

Endpoint:

```http
POST /api/auth/logout
```

Resposta:

```txt
204 No Content
```

## Como O Front Decide A Area

O backend sempre devolve:

```txt
area
home_path
roles
tenant_id
```

O frontend deve usar `area` como decisao principal.

```ts
if (user.area === 'platform') {
  // liberar telas da plataforma
}

if (user.area === 'support') {
  // liberar telas de suporte
}

if (user.area === 'clinic') {
  // liberar telas da clinica
}
```

Nao confie apenas em menus do frontend para seguranca.

O Laravel tambem valida cada area com middleware.

## Requisicoes Por Tipo De Usuario

### Superadmin E Platform Admin

Identificacao:

```txt
tenant_id = null
role = super-admin ou platform-admin
area = platform
```

Frontend libera:

```txt
/platform
```

API permitida:

```txt
/api/platform/v1/*
```

Exemplos:

```http
GET /api/platform/v1/tenants
GET /api/platform/v1/subscription-plans
GET /api/platform/v1/users
GET /api/platform/v1/audit-logs
```

Backend recebe com:

```txt
auth:sanctum
platform
```

Middleware:

```txt
App\Http\Middleware\EnsurePlatformUser
```

### Suporte

Identificacao:

```txt
tenant_id = null
role = support
area = support
```

Frontend libera:

```txt
/support
```

API atual:

```txt
Ainda nao existe namespace /api/support/v1.
```

Nesta fase, o suporte possui painel web base:

```txt
/support/dashboard
```

Quando a API de suporte for criada, ela deve usar:

```txt
/api/support/v1/*
```

Middleware recomendado:

```txt
auth:sanctum
support
```

O suporte nao deve chamar:

```txt
/api/platform/v1/*
```

Se chamar, o backend deve responder:

```txt
403 Forbidden
```

### Dono/Admin Da Clinica

Identificacao:

```txt
tenant_id = id da clinica
area = clinic
```

Frontend libera:

```txt
/app
```

API permitida:

```txt
/api/clinic/v1/*
```

Exemplos:

```http
GET /api/clinic/v1/clients
POST /api/clinic/v1/pets
GET /api/clinic/v1/appointments
GET /api/clinic/v1/medical-records
```

Backend recebe com:

```txt
auth:sanctum
tenant
```

Middleware:

```txt
App\Http\Middleware\EnsureTenantUser
```

### Cliente Final / Tutor

Estado atual:

```txt
Ainda nao possui login proprio.
```

Frontend pode mostrar area planejada:

```txt
/portal
```

API atual:

```http
GET /api/portal/v1/status
```

Quando o portal for implementado, o tutor deve usar autenticacao propria, separada da tabela `users`.

Tabela sugerida:

```txt
client_users
```

Middleware futuro:

```txt
client.portal
```

## Como O Backend Recebe E Protege

### API De Plataforma

Rotas:

```txt
routes/api.php
prefix: /api/platform/v1
```

Middlewares:

```txt
auth:sanctum
platform
```

Regra:

```php
$user->isPlatformAdmin()
```

### API Da Clinica

Rotas:

```txt
routes/api.php
prefix: /api/clinic/v1
```

Middlewares:

```txt
auth:sanctum
tenant
```

Regra:

```php
$user->isTenantUser()
```

### API Do Portal

Rotas:

```txt
routes/api.php
prefix: /api/portal/v1
```

Estado atual:

```txt
Somente status publico planejado.
```

## Regras De Seguranca

- O frontend pode esconder menus, mas a seguranca real fica no backend.
- Nunca envie `tenant_id` confiando no frontend quando o registro pertence a clinica logada.
- Para dados operacionais, o backend usa `TenantTrait` para preencher e filtrar `tenant_id`.
- Superadmin e suporte sao `users` com `tenant_id = null`.
- Cliente final/tutor nao deve usar `users` no futuro; deve ter autenticacao propria.

## Arquivos Importantes

Frontend:

```txt
frontend/src/services/api.ts
frontend/src/types/auth.ts
frontend/src/main.tsx
frontend/.env.example
```

Backend:

```txt
app/Http/Controllers/Api/AuthController.php
routes/api.php
config/cors.php
config/sanctum.php
app/Http/Middleware/EnsurePlatformUser.php
app/Http/Middleware/EnsureSupportUser.php
app/Http/Middleware/EnsureTenantUser.php
```

## Checklist Para Novas Telas

Ao criar uma nova tela no frontend:

1. Descobrir `user.area` via `/api/auth/me`.
2. Escolher namespace correto:

```txt
platform -> /api/platform/v1
clinic   -> /api/clinic/v1
support  -> /api/support/v1 futuro
portal   -> /api/portal/v1 futuro
```

3. Enviar `credentials: include`.
4. Nao enviar `tenant_id` manualmente para registros da clinica.
5. Tratar `401` como sessao expirada.
6. Tratar `403` como usuario sem permissao para aquela area.

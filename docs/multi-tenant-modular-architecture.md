# Arquitetura SaaS Multi-Tenant Modular

Este documento define a arquitetura para manter o sistema como uma plataforma SaaS multi-tenant flexivel, capaz de atender segmentos diferentes sem criar uma aplicacao separada por nicho.

## Objetivo

O produto deve ter um nucleo comum e modulos especificos ativaveis por tenant.

Exemplos:

```txt
Tenant veterinario
- core.agenda
- core.finance
- core.documents
- veterinary.pets
- veterinary.vaccines
- veterinary.medical-records

Tenant psicologia
- core.agenda
- core.finance
- core.documents
- psychology.patients
- psychology.sessions
- psychology.clinical-records
```

O frontend e o backend nao devem depender de condicionais fixas como `if segment == veterinary`.

O comportamento correto e:

```txt
tenant possui modulo?
usuario possui permissao?
modulo possui rota/menu/config?
```

## Conceitos

### Tenant

Representa o cliente SaaS, independente do nicho.

Tabela principal:

```txt
tenants
- id
- segment_id
- name
- slug
- uuid
- status
- metadata
```

### Segment

Representa a area de atuacao do tenant.

```txt
segments
- id
- name
- slug
- description
- active
```

Segmentos iniciais:

```txt
veterinary
psychology
```

### Module

Representa uma funcionalidade ativavel.

```txt
modules
- id
- key
- name
- description
- scope
- category
- is_core
- active
- navigation_label
- navigation_path
- api_prefix
- icon
- config_schema
```

Chaves seguem o padrao:

```txt
core.agenda
core.finance
veterinary.pets
psychology.sessions
```

### Module Segment

Relaciona segmentos com modulos sugeridos por padrao.

```txt
module_segment
- module_id
- segment_id
- enabled_by_default
- default_config
```

### Tenant Modules

Controla os modulos habilitados por tenant.

```txt
tenant_modules
- tenant_id
- module_id
- enabled
- config
- enabled_at
```

Esse e o principal ponto de ativacao e desativacao de funcionalidades.

### Tenant Settings E Branding

Configuracoes funcionais ficam em:

```txt
tenant_settings
- tenant_id
- key
- value
```

Personalizacao visual fica em:

```txt
tenant_brandings
- tenant_id
- logo_path
- primary_color
- secondary_color
- accent_color
- custom_domain
- extra
```

## Provisionamento

O cadastro publico chama:

```http
POST /api/auth/register
```

Payload:

```json
{
  "tenant_name": "Clinica Central",
  "segment_slug": "veterinary",
  "name": "Dra. Ana",
  "email": "ana@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

O backend cria:

```txt
Tenant
User inicial do tenant
TenantBranding padrao
TenantSettings padrao
TenantModules padrao do segmento
```

Servico responsavel:

```txt
App\Services\TenantProvisioningService
```

## Bootstrap Da SPA

O frontend deve iniciar a area logada chamando:

```http
GET /api/app/bootstrap
```

Esse endpoint retorna:

```txt
user
tenant
modules
navigation
permissions
branding
```

Exemplo resumido:

```json
{
  "data": {
    "tenant": {
      "segment": {
        "slug": "psychology",
        "name": "Psicologia"
      }
    },
    "modules": [
      {
        "key": "psychology.sessions",
        "api_prefix": "/api/clinic/v1/appointments",
        "navigation_label": "Sessoes"
      }
    ],
    "navigation": [
      {
        "label": "Sessoes",
        "path": "/app/sessions",
        "module": "psychology.sessions",
        "api_prefix": "/api/clinic/v1/appointments"
      }
    ]
  }
}
```

O frontend deve renderizar menus, rotas e telas a partir desse contrato.

## Protecao De Rotas Por Modulo

Rotas operacionais podem usar middleware:

```php
Route::apiResource('pets', PetController::class)
    ->middleware('module:veterinary.pets');
```

Tambem e possivel aceitar qualquer um de varios modulos:

```php
Route::apiResource('medical-records', MedicalRecordController::class)
    ->middleware('module:veterinary.medical-records,psychology.clinical-records');
```

Middleware:

```txt
App\Http\Middleware\EnsureTenantModuleEnabled
```

## Como Adicionar Um Novo Nicho

Exemplo: odontologia.

1. Criar segmento no seeder ou painel da plataforma:

```txt
slug: dentistry
name: Odontologia
```

2. Criar modulos:

```txt
dentistry.patients
dentistry.dental-records
dentistry.procedures
```

3. Vincular modulos ao segmento em `module_segment`.

4. Criar migrations/models/controllers se houver dados especificos.

5. Proteger rotas especificas com middleware `module:*`.

6. Expor `navigation_label`, `navigation_path` e `api_prefix` no modulo.

7. O frontend passa a receber os novos modulos em `/api/app/bootstrap`.

## Regra De Ouro

Evite:

```php
if ($tenant->segment->slug === 'veterinary') {
    // comportamento fixo
}
```

Prefira:

```php
if ($tenant->hasModule('veterinary.pets')) {
    // comportamento por capacidade habilitada
}
```

No frontend, prefira:

```ts
bootstrap.navigation.map(renderMenuItem)
```

em vez de montar menu fixo por nicho.

## Estado Atual

Implementado:

- segmentos `veterinary` e `psychology`;
- catalogo de modulos;
- modulos habilitados por tenant;
- provisionamento automatico no cadastro;
- endpoint `/api/segments`;
- endpoint `/api/app/bootstrap`;
- middleware `module:*`;
- frontend usando o bootstrap para listar rotas da API habilitadas.

Ainda recomendado para evolucao:

- criar controllers e tabelas especificas de psicologia quando o dominio exigir campos proprios;
- criar tela administrativa para ativar/desativar `tenant_modules`;
- criar editor de branding;
- expandir RBAC para permissoes por modulo;
- criar testes automatizados para provisionamento e bloqueio por modulo.

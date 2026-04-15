# Cadastro E Uso Por Tipo De Usuario

Este documento explica como cada tipo de usuario deve ser criado, por onde acessa o sistema e quais areas pode utilizar.

## Resumo Dos Tipos De Usuario

```txt
Superadmin
  Dono da plataforma SaaS
  Criado via seed/env
  Acessa /platform e /support

Suporte
  Equipe interna de atendimento
  Criado via seed/env
  Acessa /support
  Nao acessa /platform

Dono/Admin da clinica
  Cliente SaaS que aluga o sistema
  Criado pelo cadastro /register
  Acessa /app

Usuario da clinica
  Funcionario da clinica
  Deve ser criado futuramente pelo admin da clinica
  Acessa /app

Cliente final/Tutor
  Cliente da clinica, responsavel pelo pet
  Hoje e cadastro interno em clients
  Futuramente acessara /portal
```

## 1. Superadmin

### Quem e

O superadmin e o dono principal da plataforma. Ele administra o SaaS como produto.

### Como cadastrar

O superadmin nao deve ser criado pelo cadastro publico `/register`.

Ele deve ser criado via variaveis de ambiente e seeder.

No `.env`, configure:

```env
SUPER_ADMIN_NAME="Seu Nome"
SUPER_ADMIN_EMAIL="voce@example.com"
SUPER_ADMIN_PASSWORD="senha-segura"
```

Depois rode:

```bash
make seed
```

Em um banco novo, tambem pode rodar:

```bash
make fresh
```

### Como o sistema identifica

Na tabela `users`:

```txt
tenant_id = null
```

Na tabela `roles`:

```txt
slug = super-admin
scope = platform
tenant_id = null
```

O seeder vincula esse papel ao usuario.

### Como acessar

Login normal:

```txt
/login
```

Depois do login, o sistema redireciona para:

```txt
/platform/dashboard
```

### O que pode fazer

Pode acessar:

```txt
/platform
/platform/dashboard
/support
/support/dashboard
/api/platform/v1/*
```

Responsabilidades:

- administrar clinicas/tenants;
- administrar planos;
- administrar assinaturas;
- administrar usuarios globais;
- acessar auditoria;
- apoiar suporte quando necessario.

### O que nao representa

Superadmin nao e clinica.

Por isso:

```txt
tenant_id = null
```

Ele tambem nao representa cliente final/tutor.

## 2. Platform Admin

### Quem e

Platform admin e um administrador da plataforma com permissao parecida com superadmin, mas pensado para uso futuro quando houver mais funcionarios internos.

### Como cadastrar

Hoje o papel `platform-admin` e criado pelo seeder, mas ainda nao existe uma tela propria para criar esse usuario.

Fluxo recomendado para o futuro:

- superadmin acessa `/platform`;
- cria um usuario administrativo;
- atribui role `platform-admin`.

### Como o sistema identifica

Na tabela `users`:

```txt
tenant_id = null
```

Na tabela `roles`:

```txt
slug = platform-admin
scope = platform
tenant_id = null
```

### Como acessar

```txt
/login
```

Depois:

```txt
/platform/dashboard
```

## 3. Suporte

### Quem e

Suporte e uma conta interna para ajudar clinicas a entender problemas, consultar contexto operacional e orientar uso do sistema.

Ela tem menos acesso que a administracao principal.

### Como cadastrar

O suporte tambem nao deve ser criado pelo cadastro publico `/register`.

Configure no `.env`:

```env
SUPPORT_USER_NAME="Suporte"
SUPPORT_USER_EMAIL="suporte@example.com"
SUPPORT_USER_PASSWORD="senha-segura"
```

Depois rode:

```bash
make seed
```

### Como o sistema identifica

Na tabela `users`:

```txt
tenant_id = null
```

Na tabela `roles`:

```txt
slug = support
scope = platform
tenant_id = null
```

### Como acessar

Login normal:

```txt
/login
```

Depois do login, o sistema redireciona para:

```txt
/support/dashboard
```

### O que pode fazer

Pode acessar:

```txt
/support
/support/dashboard
```

Objetivo:

- consultar informacoes de contexto;
- entender problemas reportados por clinicas;
- apoiar atendimento;
- investigar dados operacionais basicos.

### O que nao pode fazer

Nao pode acessar:

```txt
/platform
/platform/dashboard
```

Nao deve:

- criar ou remover tenants;
- alterar planos;
- alterar assinaturas;
- gerenciar superadmins;
- mudar configuracoes sensiveis da plataforma.

## 4. Dono/Admin Da Clinica

### Quem e

E o cliente SaaS que aluga o sistema.

No sistema, a clinica e um `Tenant`.

O primeiro usuario criado para essa clinica e um `Tenant User`.

### Como cadastrar

Hoje o cadastro publico cria uma nova clinica e o primeiro usuario administrativo dela.

Acesse:

```txt
/register
```

Informe:

```txt
tenant_name = nome da clinica
name        = nome do primeiro usuario
email       = email do primeiro usuario
password    = senha
```

O sistema cria:

```txt
Tenant
User com tenant_id preenchido
```

### Como o sistema identifica

Na tabela `users`:

```txt
tenant_id = id da clinica
```

Na tabela `tenants`:

```txt
id
name
uuid
```

### Como acessar

Login:

```txt
/login
```

Depois do login:

```txt
/app/dashboard
```

### O que pode fazer

Pode acessar:

```txt
/app
/app/dashboard
/api/clinic/v1/*
```

Responsabilidades:

- gerenciar dados da clinica;
- cadastrar clientes/tutores;
- cadastrar pets/pacientes;
- gerenciar agenda;
- gerenciar prontuarios;
- gerenciar vacinas;
- gerenciar estoque;
- gerenciar financeiro operacional;
- futuramente, gerenciar usuarios funcionarios da clinica.

### Isolamento de dados

Esse usuario so enxerga dados do proprio `tenant_id`.

Models operacionais usam `TenantTrait`, que aplica:

```txt
TenantScope
TenantObserver
```

Ou seja:

- consultas sao filtradas pelo tenant;
- novos registros recebem `tenant_id` automaticamente.

## 5. Usuario Funcionario Da Clinica

### Quem e

Funcionario da clinica, como:

- veterinario;
- recepcionista;
- financeiro;
- estoque;
- gerente de unidade.

### Como cadastrar

Ainda nao existe tela especifica para isso.

Fluxo recomendado para o futuro:

- dono/admin da clinica acessa `/app`;
- abre area de usuarios;
- cria funcionario;
- atribui papel dentro da clinica.

Exemplos de roles futuras:

```txt
clinic-owner
clinic-admin
veterinarian
receptionist
finance
inventory
```

### Como o sistema deve identificar

Na tabela `users`:

```txt
tenant_id = id da clinica
branch_id = id da unidade, quando aplicavel
```

Na tabela `roles`:

```txt
tenant_id = id da clinica
scope = clinic
```

### Como acessar

```txt
/login
/app/dashboard
```

## 6. Cliente Final / Tutor

### Quem e

Cliente final e o tutor/responsavel pelo pet.

No sistema atual:

```txt
Client = tutor/responsavel
Pet    = paciente
```

### Como cadastrar hoje

Hoje o tutor e cadastrado internamente pela clinica.

Fluxo atual:

- usuario da clinica acessa `/app`;
- cadastra `Client`;
- cadastra `Pet` vinculado ao `Client`.

Tambem existe API da clinica:

```txt
/api/clinic/v1/clients
/api/clinic/v1/pets
```

### Como acessar hoje

Hoje o cliente final ainda nao possui login proprio.

Existe apenas uma area base planejada:

```txt
/portal
/api/portal/v1/status
```

### Como deve funcionar no futuro

Criar uma autenticacao separada para o portal.

Tabela sugerida:

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

Fluxo futuro:

- clinica cadastra tutor;
- tutor recebe convite;
- tutor cria senha;
- tutor acessa `/portal/login`;
- tutor visualiza apenas dados do proprio `client_id`.

Area futura:

```txt
/portal/pets
/portal/appointments
/portal/documents
/portal/payments
```

## Fluxo De Redirecionamento Depois Do Login

O metodo responsavel e:

```txt
App\Models\User::homeRoute()
```

Regras atuais:

```txt
super-admin     -> /platform/dashboard
platform-admin  -> /platform/dashboard
support         -> /support/dashboard
tenant user     -> /app/dashboard
```

## Matriz De Acesso

| Tipo de usuario | tenant_id | role | Area web | Pode acessar platform? | Pode acessar support? | Pode acessar app? |
| --- | --- | --- | --- | --- | --- | --- |
| Superadmin | null | super-admin | `/platform` | Sim | Sim | Nao |
| Platform admin | null | platform-admin | `/platform` | Sim | Sim | Nao |
| Suporte | null | support | `/support` | Nao | Sim | Nao |
| Dono/Admin da clinica | preenchido | clinic-owner/clinic-admin futuro | `/app` | Nao | Nao | Sim |
| Funcionario da clinica | preenchido | roles clinic futuras | `/app` | Nao | Nao | Sim |
| Cliente final/Tutor | nao usa `users` no futuro | portal futuro | `/portal` | Nao | Nao | Nao |

## Comandos Uteis

Criar superadmin e suporte via seed:

```bash
make seed
```

Recriar banco e rodar seeders:

```bash
make fresh
```

Rodar testes de acesso:

```bash
make test-filter FILTER=AccessSeparationTest
```

Listar rotas:

```bash
make route-list
```

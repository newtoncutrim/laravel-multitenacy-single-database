# Arquitetura ERP/CRM Veterinario SaaS

Este documento descreve a espinha dorsal incremental para evoluir a aplicacao Laravel multi-tenant atual para um ERP/CRM veterinario SaaS.

## 1. Diagnostico Atual

Base existente:

- `Tenant`: clinica, pet shop ou profissional assinante.
- `User`: usuario vinculado a um tenant.
- `Client`: tutor/cliente da clinica.
- `Pet`: animal do cliente.
- `Appointment`: agenda/consulta.
- `MedicalRecord`: prontuario base.
- Isolamento por `tenant_id` usando `TenantTrait`, `TenantScope` e `TenantObserver`.

Pontos fortes:

- Modelo multi-tenant simples e adequado para single database.
- `tenant_id` ja e o eixo de isolamento.
- Models principais ja existem e podem ser expandidos sem reescrever o projeto.
- `MedicalRecord` ja pode ser mantido como nucleo clinico.

Riscos atuais:

- Ainda ha pouco controle de autorizacao por perfil.
- Ainda nao ha separacao clara entre billing SaaS e financeiro da clinica.
- Ainda nao ha modulos formais para manter o crescimento organizado.
- Ainda faltam objetos importantes para operacao real: unidades, estoque, servicos, vacinas, documentos, auditoria e integracoes.

Decisao arquitetural:

- Preservar `app/Models` como fonte dos models Eloquent.
- Criar `app/Modules` para organizar casos de uso, controllers, requests, resources e services/actions.
- Evoluir por modulos, sem mover tudo de uma vez.
- Manter `tenant_id` em todos os dados operacionais da clinica.
- Separar dados da plataforma SaaS dos dados financeiros da clinica.
- Separar os paineis em `/platform`, `/app` e `/portal`, conforme detalhado em `docs/access-separation.md`.

## 2. Arquitetura Modular

### Platform

Responsavel pela plataforma SaaS.

- Tenants
- Planos
- Assinaturas SaaS
- Billing SaaS
- RBAC base
- Auditoria
- Integracoes globais

Nao confundir com o financeiro operacional da clinica.

### Clinic

Responsavel pela operacao diaria da clinica.

- Unidades/filiais
- Usuarios da clinica
- Clientes/tutores
- Pets
- Documentos
- Comunicacao

### Clinical

Responsavel pelo core clinico.

- Agenda
- Prontuario
- Entradas extensivas de prontuario
- Vacinacao
- Internacao/hotel

`MedicalRecord` continua sendo o nucleo clinico. Extensoes entram via tabelas complementares, como `medical_record_entries`.

### Backoffice

Responsavel pela retaguarda operacional da clinica.

- Servicos
- Tabelas de preco
- Produtos
- Estoque
- Fornecedores
- Marcas
- Financeiro operacional

### Shared

Responsavel por objetos compartilhados.

- DTOs
- Contracts
- Helpers
- Traits
- Value objects

## 3. Arvore De Diretorios

```txt
app/
  Models/
    Tenant.php
    User.php
    Branch.php
    Client.php
    Pet.php
    Appointment.php
    MedicalRecord.php
    MedicalRecordEntry.php
    Role.php
    Permission.php
    Service.php
    PriceTable.php
    PriceTableItem.php
    Brand.php
    Supplier.php
    Product.php
    InventoryLocation.php
    InventoryMovement.php
    FinancialAccount.php
    FinancialTransaction.php
    Vaccine.php
    PetVaccine.php
    Hospitalization.php
    Document.php
    CommunicationMessage.php
    Integration.php
    SubscriptionPlan.php
    TenantSubscription.php
    AuditLog.php

  Modules/
    Platform/
    Clinic/
      Actions/
      Http/
        Controllers/
        Requests/
        Resources/
    Clinical/
    Backoffice/
    Shared/

  Policies/
    ClientPolicy.php

database/
  migrations/
```

Regra de evolucao:

- Eloquent model fica em `app/Models`.
- Entrada HTTP fica no modulo.
- Regra de aplicacao fica em `Actions` ou `Services` dentro do modulo.
- Validacao fica em `Requests`.
- Resposta JSON fica em `Resources`.
- Autorizacao fica em `Policies`.

## 4. Entidades E Relacionamentos

### SaaS / Plataforma

```txt
Tenant
  hasMany User
  hasMany Branch
  hasOne TenantSubscription

SubscriptionPlan
  hasMany TenantSubscription

TenantSubscription
  belongsTo Tenant
  belongsTo SubscriptionPlan

AuditLog
  belongsTo Tenant nullable
  belongsTo User nullable
```

### RBAC

```txt
Role
  tenant_id nullable
  belongsToMany Permission
  belongsToMany User

Permission
  belongsToMany Role

User
  belongsToMany Role
```

`roles.tenant_id = null` permite perfis globais da plataforma.
`roles.tenant_id = X` permite perfis customizados por clinica.

### Operacao Da Clinica

```txt
Branch
  belongsTo Tenant
  hasMany User
  hasMany Client
  hasMany Appointment

Client
  belongsTo Tenant
  belongsTo Branch nullable
  hasMany Pet
  hasMany Appointment
  hasMany MedicalRecord

Pet
  belongsTo Tenant
  belongsTo Client
  hasMany Appointment
  hasMany MedicalRecord
```

### Core Clinico

```txt
Appointment
  belongsTo Tenant
  belongsTo Branch nullable
  belongsTo Client
  belongsTo Pet
  belongsTo User nullable

MedicalRecord
  belongsTo Tenant
  belongsTo Client
  belongsTo Pet
  belongsTo User nullable
  belongsTo Appointment nullable
  hasMany MedicalRecordEntry

MedicalRecordEntry
  belongsTo Tenant
  belongsTo MedicalRecord

Vaccine
  belongsTo Tenant

PetVaccine
  belongsTo Tenant
  belongsTo Pet
  belongsTo Vaccine
  belongsTo User nullable

Hospitalization
  belongsTo Tenant
  belongsTo Branch nullable
  belongsTo Client
  belongsTo Pet
```

### Backoffice

```txt
Service
  belongsTo Tenant
  belongsTo Branch nullable

PriceTable
  belongsTo Tenant
  hasMany PriceTableItem

PriceTableItem
  belongsTo Tenant
  belongsTo PriceTable
  belongsTo Service nullable
  belongsTo Product nullable

Product
  belongsTo Tenant
  belongsTo Brand nullable
  belongsTo Supplier nullable

InventoryLocation
  belongsTo Tenant
  belongsTo Branch nullable

InventoryMovement
  belongsTo Tenant
  belongsTo InventoryLocation
  belongsTo Product
  belongsTo User nullable

FinancialAccount
  belongsTo Tenant
  belongsTo Branch nullable

FinancialTransaction
  belongsTo Tenant
  belongsTo Branch nullable
  belongsTo FinancialAccount nullable
  belongsTo Client nullable
  belongsTo Supplier nullable
```

## 5. Migrations Iniciais Sugeridas

Migrations adicionadas como base:

- `create_branches_table`
- `create_rbac_tables`
- `create_services_and_price_tables`
- `create_inventory_tables`
- `create_financial_tables`
- `create_clinical_extension_tables`
- `create_documents_and_communication_tables`
- `create_platform_billing_tables`
- `create_audit_logs_table`

Principios:

- Toda tabela operacional da clinica tem `tenant_id`.
- Tabelas SaaS da plataforma podem nao usar `TenantTrait`.
- Billing SaaS usa `subscription_plans` e `tenant_subscriptions`.
- Financeiro da clinica usa `financial_accounts` e `financial_transactions`.
- Prontuario extensivel usa `medical_record_entries`, sem transformar `medical_records` em uma tabela gigante.

## 6. Endpoints Iniciais

Implementado como exemplo funcional:

```txt
GET    /api/v1/clients
POST   /api/v1/clients
GET    /api/v1/clients/{client}
PUT    /api/v1/clients/{client}
PATCH  /api/v1/clients/{client}
DELETE /api/v1/clients/{client}
```

Proximos endpoints por modulo:

```txt
Clinic
  /api/v1/branches
  /api/v1/users
  /api/v1/clients
  /api/v1/pets
  /api/v1/documents
  /api/v1/communication-messages

Clinical
  /api/v1/appointments
  /api/v1/medical-records
  /api/v1/medical-records/{medical_record}/entries
  /api/v1/vaccines
  /api/v1/pet-vaccines
  /api/v1/hospitalizations

Backoffice
  /api/v1/services
  /api/v1/price-tables
  /api/v1/products
  /api/v1/brands
  /api/v1/suppliers
  /api/v1/inventory-locations
  /api/v1/inventory-movements
  /api/v1/financial-accounts
  /api/v1/financial-transactions

Platform
  /api/v1/platform/tenants
  /api/v1/platform/subscription-plans
  /api/v1/platform/tenant-subscriptions
  /api/v1/platform/audit-logs
  /api/v1/platform/integrations
```

## 7. Exemplos De Implementacao

### Model

```php
class Client extends Model
{
    use HasFactory;
    use TenantTrait;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'name',
        'document',
        'email',
        'phone',
        'address',
        'notes',
    ];
}
```

### Request

```php
class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Client::class) ?? false;
    }
}
```

### Action

```php
class CreateClientAction
{
    public function execute(array $data): Client
    {
        return Client::create($data);
    }
}
```

### Controller

```php
class ClientController extends Controller
{
    public function store(StoreClientRequest $request, CreateClientAction $action): ClientResource
    {
        return ClientResource::make(
            $action->execute($request->validated())
        );
    }
}
```

### Policy

```php
public function update(User $user, Client $client): bool
{
    return $client->tenant_id === $user->tenant_id;
}
```

### Resource

```php
class ClientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
        ];
    }
}
```

## 8. Roadmap Incremental

### Fase 1 - Fundacao

- Consolidar `Tenant`, `User`, `Client`, `Pet`, `Appointment`, `MedicalRecord`.
- Adicionar `Branch`.
- Adicionar RBAC base.
- Criar padrao de modulo com Clients.
- Garantir testes de isolamento por tenant.

### Fase 2 - Operacao Da Clinica

- CRUD de unidades.
- CRUD de clientes.
- CRUD de pets.
- Busca rapida por tutor, telefone, pet e documento.
- Anexos/documentos simples.

### Fase 3 - Core Clinico

- Agenda completa.
- Prontuario com entradas extensivas.
- Vacinas e lembretes.
- Internacao/hotel.
- Modelos de anamnese e procedimentos.

### Fase 4 - Backoffice

- Servicos.
- Tabelas de preco.
- Produtos, marcas e fornecedores.
- Locais de estoque.
- Movimentacoes de estoque.

### Fase 5 - Financeiro Da Clinica

- Contas financeiras.
- Contas a pagar/receber.
- Recebimentos por atendimento.
- Relatorios por unidade, periodo e categoria.

### Fase 6 - Plataforma SaaS

- Planos.
- Assinaturas.
- Bloqueio por status de assinatura.
- Limites por plano.
- Auditoria administrativa.

### Fase 7 - Comunicacao E Integracoes

- WhatsApp/e-mail/SMS.
- Lembretes de vacina e consulta.
- Webhooks.
- Integracoes fiscais, pagamentos e gateways.

### Fase 8 - Hardening

- Policies por modulo.
- Permissoes granulares.
- Auditoria automatica.
- Observabilidade.
- Jobs para tarefas demoradas.
- Testes de regressao por tenant.

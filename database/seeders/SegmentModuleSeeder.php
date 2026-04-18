<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Segment;
use App\Models\Tenant;
use App\Services\TenantProvisioningService;
use Illuminate\Database\Seeder;

class SegmentModuleSeeder extends Seeder
{
    public function run(): void
    {
        $segments = [
            'veterinary' => [
                'name' => 'Veterinaria',
                'description' => 'Clinicas veterinarias, pet shops, hospitais veterinarios e profissionais do cuidado animal.',
            ],
            'psychology' => [
                'name' => 'Psicologia',
                'description' => 'Psicologos, terapeutas, consultorios e clinicas de saude mental.',
            ],
        ];

        foreach ($segments as $slug => $data) {
            Segment::query()->updateOrCreate(
                ['slug' => $slug],
                [...$data, 'active' => true]
            );
        }

        $modules = [
            [
                'key' => 'platform.tenants',
                'name' => 'Tenants',
                'description' => 'Gestao de clientes SaaS, segmentos e configuracoes de tenants.',
                'scope' => 'platform',
                'category' => 'platform',
                'navigation_label' => 'Tenants',
                'navigation_path' => '/platform/tenants',
                'api_prefix' => '/api/platform/v1/tenants',
                'icon' => 'building',
            ],
            [
                'key' => 'platform.billing',
                'name' => 'Planos e Assinaturas',
                'description' => 'Planos SaaS, assinaturas e billing da plataforma.',
                'scope' => 'platform',
                'category' => 'platform',
                'navigation_label' => 'Assinaturas',
                'navigation_path' => '/platform/subscriptions',
                'api_prefix' => '/api/platform/v1/tenant-subscriptions',
                'icon' => 'credit-card',
            ],
            [
                'key' => 'platform.access',
                'name' => 'Acesso da Plataforma',
                'description' => 'Usuarios, perfis e permissoes globais da plataforma.',
                'scope' => 'platform',
                'category' => 'platform',
                'navigation_label' => 'Acessos',
                'navigation_path' => '/platform/users',
                'api_prefix' => '/api/platform/v1/users',
                'icon' => 'shield',
            ],
            [
                'key' => 'core.users',
                'name' => 'Usuarios e Permissoes',
                'description' => 'Gestao de usuarios, perfis, permissoes e acessos.',
                'category' => 'core',
                'is_core' => true,
                'navigation_label' => 'Usuarios',
                'navigation_path' => '/app/users',
                'api_prefix' => null,
                'icon' => 'users',
            ],
            [
                'key' => 'core.clients',
                'name' => 'Clientes',
                'description' => 'Cadastro comum de clientes, tutores, pacientes ou responsaveis conforme o segmento.',
                'category' => 'core',
                'is_core' => true,
                'navigation_label' => 'Clientes',
                'navigation_path' => '/app/clients',
                'api_prefix' => '/api/clinic/v1/clients',
                'icon' => 'contacts',
            ],
            [
                'key' => 'core.agenda',
                'name' => 'Agenda',
                'description' => 'Agenda de atendimentos, compromissos, sessoes e consultas.',
                'category' => 'core',
                'is_core' => true,
                'navigation_label' => 'Agenda',
                'navigation_path' => '/app/appointments',
                'api_prefix' => '/api/clinic/v1/appointments',
                'icon' => 'calendar',
            ],
            [
                'key' => 'core.finance',
                'name' => 'Financeiro',
                'description' => 'Contas, receitas, despesas e transacoes financeiras.',
                'category' => 'core',
                'is_core' => true,
                'navigation_label' => 'Financeiro',
                'navigation_path' => '/app/finance',
                'api_prefix' => '/api/clinic/v1/financial-transactions',
                'icon' => 'wallet',
            ],
            [
                'key' => 'core.documents',
                'name' => 'Documentos',
                'description' => 'Documentos, arquivos, contratos, laudos e modelos.',
                'category' => 'core',
                'is_core' => true,
                'navigation_label' => 'Documentos',
                'navigation_path' => '/app/documents',
                'api_prefix' => '/api/clinic/v1/documents',
                'icon' => 'file-text',
            ],
            [
                'key' => 'core.reports',
                'name' => 'Relatorios',
                'description' => 'Indicadores e relatorios operacionais.',
                'category' => 'core',
                'is_core' => true,
                'navigation_label' => 'Relatorios',
                'navigation_path' => '/app/reports',
                'api_prefix' => null,
                'icon' => 'bar-chart',
            ],
            [
                'key' => 'veterinary.pets',
                'name' => 'Pets',
                'description' => 'Cadastro de animais e pacientes veterinarios.',
                'category' => 'veterinary',
                'navigation_label' => 'Pets',
                'navigation_path' => '/app/pets',
                'api_prefix' => '/api/clinic/v1/pets',
                'icon' => 'paw-print',
            ],
            [
                'key' => 'veterinary.vaccines',
                'name' => 'Vacinas',
                'description' => 'Catalogo de vacinas, carteira vacinal e doses aplicadas.',
                'category' => 'veterinary',
                'navigation_label' => 'Vacinas',
                'navigation_path' => '/app/vaccines',
                'api_prefix' => '/api/clinic/v1/vaccines',
                'icon' => 'syringe',
            ],
            [
                'key' => 'veterinary.medical-records',
                'name' => 'Prontuario Veterinario',
                'description' => 'Prontuarios, fichas clinicas e evolucao veterinaria.',
                'category' => 'veterinary',
                'navigation_label' => 'Prontuarios',
                'navigation_path' => '/app/medical-records',
                'api_prefix' => '/api/clinic/v1/medical-records',
                'icon' => 'stethoscope',
            ],
            [
                'key' => 'veterinary.hospitalization',
                'name' => 'Internacao e Hospedagem',
                'description' => 'Controle de internacoes, hospedagens e acompanhamento diario.',
                'category' => 'veterinary',
                'navigation_label' => 'Internacao',
                'navigation_path' => '/app/hospitalizations',
                'api_prefix' => '/api/clinic/v1/hospitalizations',
                'icon' => 'hotel',
            ],
            [
                'key' => 'psychology.patients',
                'name' => 'Pacientes',
                'description' => 'Cadastro e acompanhamento de pacientes da psicologia.',
                'category' => 'psychology',
                'navigation_label' => 'Pacientes',
                'navigation_path' => '/app/patients',
                'api_prefix' => '/api/clinic/v1/clients',
                'icon' => 'user-round',
            ],
            [
                'key' => 'psychology.sessions',
                'name' => 'Sessoes',
                'description' => 'Controle de sessoes, agenda terapeutica e comparecimento.',
                'category' => 'psychology',
                'navigation_label' => 'Sessoes',
                'navigation_path' => '/app/sessions',
                'api_prefix' => '/api/clinic/v1/appointments',
                'icon' => 'calendar-clock',
            ],
            [
                'key' => 'psychology.clinical-records',
                'name' => 'Prontuario Clinico',
                'description' => 'Evolucao terapeutica, registros clinicos e anotacoes.',
                'category' => 'psychology',
                'navigation_label' => 'Prontuario Clinico',
                'navigation_path' => '/app/clinical-records',
                'api_prefix' => '/api/clinic/v1/medical-records',
                'icon' => 'notebook-tabs',
            ],
        ];

        foreach ($modules as $module) {
            Module::query()->updateOrCreate(
                ['key' => $module['key']],
                [
                    'name' => $module['name'],
                    'description' => $module['description'],
                    'scope' => $module['scope'] ?? 'tenant',
                    'category' => $module['category'],
                    'is_core' => $module['is_core'] ?? false,
                    'active' => true,
                    'navigation_label' => $module['navigation_label'],
                    'navigation_path' => $module['navigation_path'],
                    'api_prefix' => $module['api_prefix'],
                    'icon' => $module['icon'],
                ]
            );
        }

        $veterinary = Segment::query()->where('slug', 'veterinary')->firstOrFail();
        $psychology = Segment::query()->where('slug', 'psychology')->firstOrFail();

        $this->syncSegmentModules($veterinary, [
            'veterinary.pets',
            'veterinary.vaccines',
            'veterinary.medical-records',
            'veterinary.hospitalization',
        ]);

        $this->syncSegmentModules($psychology, [
            'psychology.patients',
            'psychology.sessions',
            'psychology.clinical-records',
        ]);

        $provisioner = app(TenantProvisioningService::class);

        Tenant::query()
            ->whereNull('segment_id')
            ->get()
            ->each(fn (Tenant $tenant) => $provisioner->provision($tenant, $veterinary));

        Tenant::query()
            ->whereNotNull('segment_id')
            ->get()
            ->each(fn (Tenant $tenant) => $provisioner->provision($tenant, $tenant->segment));
    }

    private function syncSegmentModules(Segment $segment, array $moduleKeys): void
    {
        $moduleIds = Module::query()
            ->whereIn('key', $moduleKeys)
            ->pluck('id')
            ->all();

        $segment->modules()->syncWithoutDetaching(
            collect($moduleIds)
                ->mapWithKeys(fn ($id) => [$id => ['enabled_by_default' => true]])
                ->all()
        );
    }
}

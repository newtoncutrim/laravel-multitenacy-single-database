import { endpointsByArea } from './api';
import type { UserArea } from '../types/auth';

export type ApiResourceArea = Extract<UserArea, 'platform' | 'clinic'>;

export type ApiResourceDefinition = {
  area: ApiResourceArea;
  slug: string;
  label: string;
  path: string;
  description: string;
};

const clinicResources = [
  ['appointments', 'Agenda', 'Agendamentos, horarios e atendimentos marcados.'],
  ['branches', 'Unidades', 'Filiais e unidades da clinica.'],
  ['brands', 'Marcas', 'Marcas vinculadas a produtos e estoque.'],
  ['clients', 'Clientes', 'Tutores e responsaveis cadastrados.'],
  ['communication-messages', 'Comunicacao', 'Mensagens e historico de contato.'],
  ['documents', 'Documentos', 'Documentos, atestados, contratos e modelos.'],
  ['financial-accounts', 'Contas financeiras', 'Contas usadas no financeiro.'],
  ['financial-transactions', 'Transacoes financeiras', 'Receitas, despesas e movimentos.'],
  ['hospitalizations', 'Internacao', 'Internacoes, hospedagens e acompanhamento.'],
  ['inventory-locations', 'Locais de estoque', 'Locais fisicos de armazenagem.'],
  ['inventory-movements', 'Movimentos de estoque', 'Entradas, saidas e ajustes.'],
  ['medical-record-entries', 'Entradas de prontuario', 'Registros individuais do prontuario.'],
  ['medical-records', 'Prontuarios', 'Historico clinico dos pets.'],
  ['pet-vaccines', 'Vacinas dos pets', 'Carteira e doses aplicadas.'],
  ['pets', 'Pets', 'Animais vinculados aos tutores.'],
  ['posts', 'Posts', 'Conteudo publico da clinica.'],
  ['price-table-items', 'Itens de tabela', 'Itens e valores de tabelas de preco.'],
  ['price-tables', 'Tabelas de preco', 'Tabelas comerciais da clinica.'],
  ['products', 'Produtos', 'Produtos vendidos ou consumidos.'],
  ['services', 'Servicos', 'Procedimentos e servicos oferecidos.'],
  ['suppliers', 'Fornecedores', 'Fornecedores de produtos e servicos.'],
  ['vaccines', 'Vacinas', 'Catalogo de vacinas.'],
] as const;

const platformResources = [
  ['audit-logs', 'Auditoria', 'Eventos e rastros administrativos.'],
  ['integrations', 'Integracoes', 'Conectores externos e webhooks.'],
  ['permissions', 'Permissoes', 'Permissoes do sistema.'],
  ['roles', 'Perfis', 'Perfis de acesso.'],
  ['subscription-plans', 'Planos SaaS', 'Planos comerciais da plataforma.'],
  ['tenant-subscriptions', 'Assinaturas', 'Assinaturas das clinicas.'],
  ['tenants', 'Clinicas', 'Tenants cadastrados na plataforma.'],
  ['users', 'Usuarios', 'Usuarios da plataforma e clinicas.'],
] as const;

export const apiResources: ApiResourceDefinition[] = [
  ...clinicResources.map(([slug, label, description]) => ({
    area: 'clinic' as const,
    slug,
    label,
    description,
    path: `${endpointsByArea.clinic}/${slug}`,
  })),
  ...platformResources.map(([slug, label, description]) => ({
    area: 'platform' as const,
    slug,
    label,
    description,
    path: `${endpointsByArea.platform}/${slug}`,
  })),
];

export function resourcesForArea(area: UserArea): ApiResourceDefinition[] {
  if (area === 'platform') {
    return apiResources.filter((resource) => resource.area === 'platform');
  }

  if (area === 'clinic') {
    return apiResources.filter((resource) => resource.area === 'clinic');
  }

  return [];
}

import { useEffect, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { Building2, Database, Headphones, Lock, LogOut, PawPrint, RefreshCcw, ShieldCheck } from 'lucide-react';
import { appBootstrap, endpointsByArea, listApiResource, logout } from '../services/api';
import type { PaginatedResponse } from '../services/api';
import type { AuthUser } from '../types/auth';

type ApiResourceDefinition = {
  key: string;
  label: string;
  path: string;
  description: string;
  category: string;
};

function Dashboard() {
  const [user, setUser] = useState<AuthUser | null>(null);
  const [resources, setResources] = useState<ApiResourceDefinition[]>([]);
  const [message, setMessage] = useState('Carregando sessao...');
  const [loading, setLoading] = useState(false);
  const [selectedResource, setSelectedResource] = useState<ApiResourceDefinition | null>(null);
  const [resourceData, setResourceData] = useState<PaginatedResponse | null>(null);
  const [resourceLoading, setResourceLoading] = useState(false);
  const [resourceMessage, setResourceMessage] = useState('');
  const navigate = useNavigate();

  useEffect(() => {
    appBootstrap()
      .then((bootstrap) => {
        setUser(bootstrap.user);
        setMessage(`Acesso liberado para ${bootstrap.user.area}.`);

        const apiResources = bootstrap.navigation
          .filter((item) => item.api_prefix)
          .map((item) => ({
            key: item.module,
            label: item.label,
            path: item.api_prefix as string,
            description: bootstrap.modules.find((module) => module.key === item.module)?.description ?? 'Modulo habilitado para este tenant.',
            category: item.category,
          }));

        setResources(apiResources);
        setSelectedResource(apiResources[0] ?? null);
      })
      .catch(() => {
        setMessage('Entre para acessar sua area.');
        navigate('/login');
    });
  }, [navigate]);

  useEffect(() => {
    if (!selectedResource) {
      setResourceData(null);
      return;
    }

    void loadResource(selectedResource);
  }, [selectedResource]);

  async function loadResource(resource: ApiResourceDefinition) {
    setResourceLoading(true);
    setResourceMessage(`Carregando ${resource.label.toLowerCase()}...`);

    try {
      const response = await listApiResource(resource.path, 10);

      setResourceData(response);
      setResourceMessage(`${response.total} registro(s) retornado(s) por ${resource.path}.`);
    } catch (error) {
      setResourceData(null);
      setResourceMessage(error instanceof Error ? error.message : 'Nao foi possivel carregar este recurso.');
    } finally {
      setResourceLoading(false);
    }
  }

  async function handleLogout() {
    setLoading(true);

    try {
      await logout();
      navigate('/');
    } catch (error) {
      setMessage(error instanceof Error ? error.message : 'Nao foi possivel sair.');
    } finally {
      setLoading(false);
    }
  }

  return (
    <main className="min-h-screen bg-background px-4 py-8 text-foreground">
      <div className="mx-auto w-full max-w-6xl">
        <header className="mb-8 flex flex-col gap-4 border-b border-border pb-6 sm:flex-row sm:items-center sm:justify-between">
          <Link to="/" className="flex items-center gap-2.5">
            <div className="flex h-9 w-9 items-center justify-center rounded-lg bg-primary">
              <span className="text-lg font-bold text-primary-foreground">V</span>
            </div>
            <span className="text-xl font-bold tracking-tight">
              vet<span className="text-primary">Flow</span>
            </span>
          </Link>

          <button
            className="inline-flex items-center justify-center gap-2 rounded-lg bg-foreground px-4 py-2 text-sm font-semibold text-background transition-opacity hover:opacity-90 disabled:cursor-wait disabled:opacity-60"
            disabled={loading}
            onClick={handleLogout}
            type="button"
          >
            <LogOut className="h-4 w-4" />
            Sair
          </button>
        </header>

        <section className="mb-6 grid gap-5 lg:grid-cols-[minmax(0,1fr)_360px]">
          <div>
            <p className="mb-3 text-xs font-semibold uppercase tracking-widest text-primary">SaaS veterinario</p>
            <h1 className="mb-4 text-3xl font-extrabold leading-tight tracking-tight sm:text-4xl">
              Painel conectado a API Laravel
            </h1>
            <p className="max-w-2xl text-muted-foreground">
              A sessao continua usando Sanctum com cookies. Cada usuario recebe uma area no login e o frontend libera a rota correta.
            </p>
          </div>

          <div className="rounded-lg border border-border bg-card p-5 shadow-sm">
            <p className="mb-2 text-sm font-bold">Status</p>
            <p className="text-sm leading-relaxed text-muted-foreground">{message}</p>
          </div>
        </section>

        <section className="mb-6 grid gap-5 lg:grid-cols-[minmax(0,1fr)_360px]">
          <div className="rounded-lg border border-border bg-card p-6 shadow-sm">
            <h2 className="mb-4 text-xl font-bold">Usuario autenticado</h2>
            {user ? (
              <div className="grid gap-3 text-sm">
                <p>
                  <strong className="block text-base text-foreground">{user.name}</strong>
                  <span className="text-muted-foreground">{user.email}</span>
                </p>
                <p>Area: {user.area}</p>
                <p>Tenant: {user.tenant_id ?? 'plataforma'}</p>
                <p>Home: {user.home_path}</p>
                <p>Perfis: {user.roles.length ? user.roles.join(', ') : 'sem perfil informado'}</p>
              </div>
            ) : (
              <p className="text-sm text-muted-foreground">Nenhuma sessao carregada.</p>
            )}
          </div>

          <div className="rounded-lg border border-border bg-card p-6 shadow-sm">
            <h2 className="mb-4 text-xl font-bold">Atalhos</h2>
            <div className="grid gap-2 text-sm">
              <Link className="rounded-lg border border-border px-3 py-2 transition-colors hover:bg-muted" to="/platform">
                Plataforma
              </Link>
              <Link className="rounded-lg border border-border px-3 py-2 transition-colors hover:bg-muted" to="/support">
                Suporte
              </Link>
              <Link className="rounded-lg border border-border px-3 py-2 transition-colors hover:bg-muted" to="/app">
                Clinica
              </Link>
              <Link className="rounded-lg border border-border px-3 py-2 transition-colors hover:bg-muted" to="/portal">
                Portal
              </Link>
            </div>
          </div>
        </section>

        <section className="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
          <Area icon={ShieldCheck} title="Plataforma" route="/platform" endpoint={endpointsByArea.platform} role="super-admin" />
          <Area icon={Headphones} title="Suporte" route="/support" endpoint={endpointsByArea.support} role="support" />
          <Area icon={Building2} title="Clinica" route="/app" endpoint={endpointsByArea.clinic} role="tenant user" />
          <Area icon={PawPrint} title="Portal" route="/portal" endpoint={endpointsByArea.portal} role="cliente final futuro" />
        </section>

        <section className="mt-6 grid gap-5 lg:grid-cols-[320px_minmax(0,1fr)]">
          <div className="rounded-lg border border-border bg-card p-5 shadow-sm">
            <div className="mb-4 flex items-center gap-2">
              <Database className="h-5 w-5 text-primary" />
              <h2 className="text-lg font-bold">Rotas da API</h2>
            </div>

            {resources.length ? (
              <div className="grid gap-2">
                {resources.map((resource) => (
                  <button
                    key={resource.path}
                    className={`rounded-lg border px-3 py-2 text-left transition-colors ${
                      selectedResource?.path === resource.path
                        ? 'border-primary bg-primary text-primary-foreground'
                        : 'border-border bg-background hover:bg-muted'
                    }`}
                    onClick={() => setSelectedResource(resource)}
                    type="button"
                  >
                    <span className="block text-sm font-bold">{resource.label}</span>
                    <span className={`block text-xs ${selectedResource?.path === resource.path ? 'text-primary-foreground/80' : 'text-muted-foreground'}`}>
                      {resource.path}
                    </span>
                  </button>
                ))}
              </div>
            ) : (
              <p className="text-sm leading-relaxed text-muted-foreground">
                Nenhum modulo com rota API foi habilitado para este usuario/tenant.
              </p>
            )}
          </div>

          <div className="rounded-lg border border-border bg-card p-5 shadow-sm">
            <div className="mb-4 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
              <div>
                <p className="text-xs font-semibold uppercase tracking-widest text-primary">Consumo em tempo real</p>
                <h2 className="mt-1 text-xl font-bold">{selectedResource?.label ?? 'Nenhum recurso selecionado'}</h2>
                {selectedResource && <p className="mt-1 text-sm text-muted-foreground">{selectedResource.description}</p>}
              </div>

              {selectedResource && (
                <button
                  className="inline-flex items-center justify-center gap-2 rounded-lg border border-border bg-background px-3 py-2 text-sm font-semibold transition-colors hover:bg-muted disabled:cursor-wait disabled:opacity-60"
                  disabled={resourceLoading}
                  onClick={() => loadResource(selectedResource)}
                  type="button"
                >
                  <RefreshCcw className="h-4 w-4" />
                  Atualizar
                </button>
              )}
            </div>

            {resourceMessage && <p className="mb-4 rounded-lg bg-muted px-3 py-2 text-sm text-muted-foreground">{resourceMessage}</p>}

            {resourceData && selectedResource ? (
              <ResourceTable data={resourceData.data} />
            ) : (
              <div className="rounded-lg border border-dashed border-border p-6 text-sm text-muted-foreground">
                Selecione um recurso para buscar dados direto na API Laravel.
              </div>
            )}
          </div>
        </section>
      </div>
    </main>
  );
}

type AreaProps = {
  icon: typeof Lock;
  title: string;
  route: string;
  endpoint: string;
  role: string;
};

function Area({ icon: Icon, title, route, endpoint, role }: AreaProps) {
  return (
    <article className="rounded-lg border border-border bg-card p-5 shadow-sm">
      <div className="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
        <Icon className="h-5 w-5 text-primary" />
      </div>
      <h2 className="mb-4 text-lg font-bold text-success">{title}</h2>
      <dl className="grid gap-3 text-sm">
        <div>
          <dt className="font-bold uppercase text-muted-foreground">Rota</dt>
          <dd className="mt-1 break-words">{route}</dd>
        </div>
        <div>
          <dt className="font-bold uppercase text-muted-foreground">API</dt>
          <dd className="mt-1 break-words">{endpoint}</dd>
        </div>
        <div>
          <dt className="font-bold uppercase text-muted-foreground">Acesso</dt>
          <dd className="mt-1 break-words">{role}</dd>
        </div>
      </dl>
    </article>
  );
}

function ResourceTable({ data }: { data: Record<string, unknown>[] }) {
  if (!data.length) {
    return (
      <div className="rounded-lg border border-border bg-background p-6 text-sm text-muted-foreground">
        A API respondeu corretamente, mas ainda nao existem registros para este recurso.
      </div>
    );
  }

  const columns = Array.from(
    data.reduce((keys, row) => {
      Object.keys(row)
        .slice(0, 8)
        .forEach((key) => keys.add(key));

      return keys;
    }, new Set<string>()),
  ).slice(0, 8);

  return (
    <div className="overflow-x-auto rounded-lg border border-border">
      <table className="min-w-full divide-y divide-border text-sm">
        <thead className="bg-muted">
          <tr>
            {columns.map((column) => (
              <th key={column} className="px-3 py-2 text-left font-bold text-foreground">
                {column}
              </th>
            ))}
          </tr>
        </thead>
        <tbody className="divide-y divide-border bg-background">
          {data.map((row, index) => (
            <tr key={String(row.id ?? index)}>
              {columns.map((column) => (
                <td key={column} className="max-w-[220px] px-3 py-2 align-top text-muted-foreground">
                  <CellValue value={row[column]} />
                </td>
              ))}
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}

function CellValue({ value }: { value: unknown }) {
  if (value === null || value === undefined || value === '') {
    return <span className="text-muted-foreground/60">-</span>;
  }

  if (typeof value === 'string' || typeof value === 'number' || typeof value === 'boolean') {
    return <span className="break-words">{String(value)}</span>;
  }

  return <span className="break-words">{JSON.stringify(value)}</span>;
}

export default Dashboard;

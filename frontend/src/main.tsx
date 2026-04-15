import React, { FormEvent, useEffect, useState } from 'react';
import { createRoot } from 'react-dom/client';
import { currentUser, endpointsByArea, login, logout, registerTenant } from './services/api';
import type { AuthUser } from './types/auth';
import './styles/app.css';

function App() {
  const [user, setUser] = useState<AuthUser | null>(null);
  const [mode, setMode] = useState<'login' | 'register'>('login');
  const [message, setMessage] = useState('Escolha uma conta para entrar ou cadastre uma clinica.');
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    currentUser()
      .then(setUser)
      .catch(() => undefined);
  }, []);

  async function handleLogin(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setLoading(true);
    setMessage('Autenticando...');

    const data = new FormData(event.currentTarget);

    try {
      const authUser = await login({
        email: String(data.get('email')),
        password: String(data.get('password')),
        remember: data.get('remember') === 'on',
      });

      setUser(authUser);
      setMessage(`Acesso liberado para ${authUser.area}.`);
    } catch (error) {
      setMessage(error instanceof Error ? error.message : 'Nao foi possivel entrar.');
    } finally {
      setLoading(false);
    }
  }

  async function handleRegister(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setLoading(true);
    setMessage('Criando clinica...');

    const data = new FormData(event.currentTarget);

    try {
      const authUser = await registerTenant({
        tenant_name: String(data.get('tenant_name')),
        name: String(data.get('name')),
        email: String(data.get('email')),
        password: String(data.get('password')),
        password_confirmation: String(data.get('password_confirmation')),
      });

      setUser(authUser);
      setMessage('Clinica criada. O primeiro usuario entrou no painel da clinica.');
    } catch (error) {
      setMessage(error instanceof Error ? error.message : 'Nao foi possivel cadastrar.');
    } finally {
      setLoading(false);
    }
  }

  async function handleLogout() {
    setLoading(true);

    try {
      await logout();
      setUser(null);
      setMessage('Sessao encerrada.');
    } catch (error) {
      setMessage(error instanceof Error ? error.message : 'Nao foi possivel sair.');
    } finally {
      setLoading(false);
    }
  }

  return (
    <main className="app-shell">
      <section className="intro-band">
        <div>
          <p className="eyebrow">SaaS veterinario</p>
          <h1>Front separado da API Laravel</h1>
          <p>
            A aplicacao React consome os endpoints do Laravel usando Sanctum com cookies. Cada usuario recebe
            uma area no login e o front usa essa area para liberar a experiencia correta.
          </p>
        </div>
        <div className="status-panel">
          <strong>Status</strong>
          <span>{message}</span>
        </div>
      </section>

      <section className="content-grid">
        <div className="surface auth-surface">
          <div className="tabs" aria-label="Autenticacao">
            <button className={mode === 'login' ? 'active' : ''} onClick={() => setMode('login')} type="button">
              Entrar
            </button>
            <button className={mode === 'register' ? 'active' : ''} onClick={() => setMode('register')} type="button">
              Cadastrar clinica
            </button>
          </div>

          {mode === 'login' ? (
            <form onSubmit={handleLogin}>
              <label>
                E-mail
                <input name="email" type="email" placeholder="admin@example.com" required />
              </label>

              <label>
                Senha
                <input name="password" type="password" required />
              </label>

              <label className="inline-field">
                <input name="remember" type="checkbox" />
                Manter conectado
              </label>

              <button disabled={loading} type="submit">
                Entrar
              </button>
            </form>
          ) : (
            <form onSubmit={handleRegister}>
              <label>
                Clinica
                <input name="tenant_name" type="text" placeholder="Clinica Central" required />
              </label>

              <label>
                Nome
                <input name="name" type="text" placeholder="Dra. Ana" required />
              </label>

              <label>
                E-mail
                <input name="email" type="email" required />
              </label>

              <label>
                Senha
                <input name="password" type="password" minLength={8} required />
              </label>

              <label>
                Confirmar senha
                <input name="password_confirmation" type="password" minLength={8} required />
              </label>

              <button disabled={loading} type="submit">
                Criar clinica
              </button>
            </form>
          )}
        </div>

        <div className="surface">
          <h2>Usuario autenticado</h2>
          {user ? (
            <div className="user-summary">
              <p>
                <strong>{user.name}</strong>
                <span>{user.email}</span>
              </p>
              <p>Area: {user.area}</p>
              <p>Tenant: {user.tenant_id ?? 'plataforma'}</p>
              <p>Home: {user.home_path}</p>
              <button className="secondary" disabled={loading} onClick={handleLogout} type="button">
                Sair
              </button>
            </div>
          ) : (
            <p className="muted">Nenhuma sessao carregada.</p>
          )}
        </div>
      </section>

      <section className="area-grid">
        <Area title="Plataforma" route="/platform" endpoint={endpointsByArea.platform} role="super-admin" />
        <Area title="Suporte" route="/support" endpoint={endpointsByArea.support} role="support" />
        <Area title="Clinica" route="/app" endpoint={endpointsByArea.clinic} role="tenant user" />
        <Area title="Portal" route="/portal" endpoint={endpointsByArea.portal} role="cliente final futuro" />
      </section>
    </main>
  );
}

type AreaProps = {
  title: string;
  route: string;
  endpoint: string;
  role: string;
};

function Area({ title, route, endpoint, role }: AreaProps) {
  return (
    <article className="area-item">
      <h2>{title}</h2>
      <dl>
        <div>
          <dt>Rota</dt>
          <dd>{route}</dd>
        </div>
        <div>
          <dt>API</dt>
          <dd>{endpoint}</dd>
        </div>
        <div>
          <dt>Acesso</dt>
          <dd>{role}</dd>
        </div>
      </dl>
    </article>
  );
}

createRoot(document.getElementById('root')!).render(
  <React.StrictMode>
    <App />
  </React.StrictMode>,
);

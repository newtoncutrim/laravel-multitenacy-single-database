import { FormEvent, useEffect, useMemo, useState } from 'react';
import { ArrowLeft, Eye, EyeOff, Mail, MessageCircle } from 'lucide-react';
import { Link, useNavigate, useSearchParams } from 'react-router-dom';
import { currentUser, listSegments, login, registerTenant } from '../services/api';
import type { SegmentOption } from '../services/api';

type AuthMode = 'login' | 'register';

function Login() {
  const [searchParams] = useSearchParams();
  const initialMode = useMemo<AuthMode>(() => (searchParams.get('mode') === 'register' ? 'register' : 'login'), [searchParams]);
  const [mode, setMode] = useState<AuthMode>(initialMode);
  const [showPassword, setShowPassword] = useState(false);
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState('');
  const [segments, setSegments] = useState<SegmentOption[]>([]);
  const navigate = useNavigate();

  useEffect(() => {
    setMode(initialMode);
  }, [initialMode]);

  useEffect(() => {
    currentUser()
      .then((user) => navigate(user.home_path || '/dashboard'))
      .catch(() => undefined);
  }, [navigate]);

  useEffect(() => {
    listSegments()
      .then(setSegments)
      .catch(() => setSegments([]));
  }, []);

  async function handleLogin(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setLoading(true);
    setMessage('');

    const data = new FormData(event.currentTarget);

    try {
      const authUser = await login({
        email: String(data.get('email')),
        password: String(data.get('password')),
        remember: data.get('remember') === 'on',
      });

      navigate(authUser.home_path || '/dashboard');
    } catch (error) {
      setMessage(error instanceof Error ? error.message : 'Nao foi possivel entrar.');
    } finally {
      setLoading(false);
    }
  }

  async function handleRegister(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setLoading(true);
    setMessage('');

    const data = new FormData(event.currentTarget);

    try {
      const authUser = await registerTenant({
        tenant_name: String(data.get('tenant_name')),
        segment_slug: String(data.get('segment_slug') || 'veterinary'),
        name: String(data.get('name')),
        email: String(data.get('email')),
        password: String(data.get('password')),
        password_confirmation: String(data.get('password_confirmation')),
      });

      navigate(authUser.home_path || '/dashboard');
    } catch (error) {
      setMessage(error instanceof Error ? error.message : 'Nao foi possivel cadastrar.');
    } finally {
      setLoading(false);
    }
  }

  return (
    <div className="min-h-screen bg-background px-4 py-8">
      <div className="mx-auto flex w-full max-w-md flex-col">
        <Link to="/" className="mb-8 inline-flex items-center gap-1.5 text-sm text-muted-foreground transition-colors hover:text-foreground">
          <ArrowLeft className="h-4 w-4" /> Voltar
        </Link>

        <div className="mb-8 text-center">
          <div className="mb-2 flex items-center justify-center gap-2">
            <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-primary">
              <span className="text-xl font-bold text-primary-foreground">V</span>
            </div>
            <span className="text-3xl font-bold tracking-tight text-foreground">
              vet<span className="text-primary">Flow</span>
            </span>
          </div>
          <p className="text-sm text-muted-foreground">Acesse sua clinica ou comece um novo teste.</p>
        </div>

        <div className="mb-6 grid grid-cols-2 gap-2 rounded-lg bg-muted p-1">
          <button
            className={`rounded-lg px-4 py-2 text-sm font-semibold transition-colors ${mode === 'login' ? 'bg-card text-foreground shadow-sm' : 'text-muted-foreground'}`}
            onClick={() => setMode('login')}
            type="button"
          >
            Entrar
          </button>
          <button
            className={`rounded-lg px-4 py-2 text-sm font-semibold transition-colors ${mode === 'register' ? 'bg-card text-foreground shadow-sm' : 'text-muted-foreground'}`}
            onClick={() => setMode('register')}
            type="button"
          >
            Cadastrar clinica
          </button>
        </div>

        {message && <div className="mb-5 rounded-lg bg-destructive/10 p-3 text-center text-sm text-destructive">{message}</div>}

        {mode === 'login' ? (
          <form onSubmit={handleLogin} className="space-y-5">
            <div>
              <label className="mb-1.5 block text-sm font-semibold text-foreground">E-mail</label>
              <input
                name="email"
                type="email"
                placeholder="admin@example.com"
                required
                className="w-full rounded-lg border border-border bg-card px-4 py-3 text-foreground outline-none transition-shadow placeholder:text-muted-foreground focus:ring-2 focus:ring-ring"
              />
            </div>

            <div>
              <label className="mb-1.5 block text-sm font-semibold text-foreground">Senha</label>
              <div className="relative">
                <input
                  name="password"
                  type={showPassword ? 'text' : 'password'}
                  placeholder="Digite sua senha"
                  required
                  className="w-full rounded-lg border border-border bg-card px-4 py-3 pr-24 text-foreground outline-none transition-shadow placeholder:text-muted-foreground focus:ring-2 focus:ring-ring"
                />
                <button
                  type="button"
                  onClick={() => setShowPassword(!showPassword)}
                  className="absolute right-3 top-1/2 flex -translate-y-1/2 items-center gap-1 text-sm font-medium text-primary transition-opacity hover:opacity-80"
                >
                  {showPassword ? <EyeOff className="h-4 w-4" /> : <Eye className="h-4 w-4" />}
                  {showPassword ? 'Ocultar' : 'Mostrar'}
                </button>
              </div>
            </div>

            <label className="flex items-center gap-2 text-sm text-muted-foreground">
              <input name="remember" type="checkbox" className="h-4 w-4 rounded border-border" />
              Manter conectado
            </label>

            <button
              type="submit"
              disabled={loading}
              className="w-full rounded-lg bg-primary py-3.5 text-base font-semibold text-primary-foreground shadow-lg shadow-primary/20 transition-opacity hover:opacity-90 disabled:cursor-wait disabled:opacity-60"
            >
              {loading ? 'Entrando...' : 'Acessar'}
            </button>
          </form>
        ) : (
          <form onSubmit={handleRegister} className="space-y-5">
            <div>
              <label className="mb-1.5 block text-sm font-semibold text-foreground">Empresa ou consultorio</label>
              <input
                name="tenant_name"
                type="text"
                placeholder="Clinica Central"
                required
                className="w-full rounded-lg border border-border bg-card px-4 py-3 text-foreground outline-none transition-shadow placeholder:text-muted-foreground focus:ring-2 focus:ring-ring"
              />
            </div>
            <div>
              <label className="mb-1.5 block text-sm font-semibold text-foreground">Segmento de atuacao</label>
              <select
                name="segment_slug"
                defaultValue="veterinary"
                required
                className="w-full rounded-lg border border-border bg-card px-4 py-3 text-foreground outline-none transition-shadow focus:ring-2 focus:ring-ring"
              >
                {segments.length ? (
                  segments.map((segment) => (
                    <option key={segment.slug} value={segment.slug}>
                      {segment.name}
                    </option>
                  ))
                ) : (
                  <>
                    <option value="veterinary">Veterinaria</option>
                    <option value="psychology">Psicologia</option>
                  </>
                )}
              </select>
              <p className="mt-1 text-xs text-muted-foreground">
                O segmento define os modulos iniciais. Eles podem ser habilitados ou removidos por tenant depois.
              </p>
            </div>
            <div>
              <label className="mb-1.5 block text-sm font-semibold text-foreground">Nome</label>
              <input
                name="name"
                type="text"
                placeholder="Dra. Ana"
                required
                className="w-full rounded-lg border border-border bg-card px-4 py-3 text-foreground outline-none transition-shadow placeholder:text-muted-foreground focus:ring-2 focus:ring-ring"
              />
            </div>
            <div>
              <label className="mb-1.5 block text-sm font-semibold text-foreground">E-mail</label>
              <input
                name="email"
                type="email"
                required
                className="w-full rounded-lg border border-border bg-card px-4 py-3 text-foreground outline-none transition-shadow placeholder:text-muted-foreground focus:ring-2 focus:ring-ring"
              />
            </div>
            <div className="grid gap-5 sm:grid-cols-2">
              <div>
                <label className="mb-1.5 block text-sm font-semibold text-foreground">Senha</label>
                <input
                  name="password"
                  type="password"
                  minLength={8}
                  required
                  className="w-full rounded-lg border border-border bg-card px-4 py-3 text-foreground outline-none transition-shadow placeholder:text-muted-foreground focus:ring-2 focus:ring-ring"
                />
              </div>
              <div>
                <label className="mb-1.5 block text-sm font-semibold text-foreground">Confirmar senha</label>
                <input
                  name="password_confirmation"
                  type="password"
                  minLength={8}
                  required
                  className="w-full rounded-lg border border-border bg-card px-4 py-3 text-foreground outline-none transition-shadow placeholder:text-muted-foreground focus:ring-2 focus:ring-ring"
                />
              </div>
            </div>

            <button
              type="submit"
              disabled={loading}
              className="w-full rounded-lg bg-primary py-3.5 text-base font-semibold text-primary-foreground shadow-lg shadow-primary/20 transition-opacity hover:opacity-90 disabled:cursor-wait disabled:opacity-60"
            >
              {loading ? 'Criando clinica...' : 'Criar clinica'}
            </button>
          </form>
        )}

        <div className="mt-8 text-center">
          <p className="mb-3 text-sm font-semibold text-foreground">Precisa de ajuda?</p>
          <div className="flex justify-center gap-3">
            <a href="https://wa.me/5511999999999" className="inline-flex items-center gap-2 rounded-full bg-accent px-5 py-2.5 text-sm font-medium text-accent-foreground transition-opacity hover:opacity-90">
              <MessageCircle className="h-4 w-4" /> WhatsApp
            </a>
            <a href="mailto:contato@vetflow.com.br" className="inline-flex items-center gap-2 rounded-full bg-primary px-5 py-2.5 text-sm font-medium text-primary-foreground transition-opacity hover:opacity-90">
              <Mail className="h-4 w-4" /> E-mail
            </a>
          </div>
        </div>
      </div>
    </div>
  );
}

export default Login;

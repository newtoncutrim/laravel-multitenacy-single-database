import type { ApiEnvelope, AuthUser, LoginPayload, RegisterTenantPayload } from '../types/auth';

const apiBaseUrl = import.meta.env.VITE_API_BASE_URL ?? 'http://localhost:8989';

type RequestOptions = RequestInit & {
  json?: unknown;
};

async function csrfCookie(): Promise<void> {
  await fetch(`${apiBaseUrl}/sanctum/csrf-cookie`, {
    credentials: 'include',
  });
}

function csrfToken(): string | null {
  const token = document.cookie
    .split('; ')
    .find((row) => row.startsWith('XSRF-TOKEN='))
    ?.split('=')[1];

  return token ? decodeURIComponent(token) : null;
}

async function request<T>(path: string, options: RequestOptions = {}): Promise<T> {
  const headers = new Headers(options.headers);

  if (options.json !== undefined) {
    headers.set('Content-Type', 'application/json');
    headers.set('Accept', 'application/json');
  }

  const token = csrfToken();

  if (token) {
    headers.set('X-XSRF-TOKEN', token);
  }

  const response = await fetch(`${apiBaseUrl}${path}`, {
    ...options,
    headers,
    credentials: 'include',
    body: options.json !== undefined ? JSON.stringify(options.json) : options.body,
  });

  if (!response.ok) {
    const errorBody = await response.json().catch(() => null);
    const message = errorBody?.message ?? `Request failed with status ${response.status}`;
    throw new Error(message);
  }

  if (response.status === 204) {
    return undefined as T;
  }

  return response.json() as Promise<T>;
}

export async function login(payload: LoginPayload): Promise<AuthUser> {
  await csrfCookie();

  const response = await request<ApiEnvelope<AuthUser>>('/api/auth/login', {
    method: 'POST',
    json: payload,
  });

  return response.data;
}

export async function registerTenant(payload: RegisterTenantPayload): Promise<AuthUser> {
  await csrfCookie();

  const response = await request<ApiEnvelope<AuthUser>>('/api/auth/register', {
    method: 'POST',
    json: payload,
  });

  return response.data;
}

export async function currentUser(): Promise<AuthUser> {
  const response = await request<ApiEnvelope<AuthUser>>('/api/auth/me');

  return response.data;
}

export async function logout(): Promise<void> {
  await request<void>('/api/auth/logout', {
    method: 'POST',
  });
}

export const endpointsByArea = {
  platform: '/api/platform/v1',
  support: '/support',
  clinic: '/api/clinic/v1',
  portal: '/api/portal/v1',
} as const;

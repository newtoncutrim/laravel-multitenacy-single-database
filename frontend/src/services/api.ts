import type { ApiEnvelope, AuthUser, LoginPayload, RegisterTenantPayload } from '../types/auth';

const apiBaseUrl = import.meta.env.VITE_API_BASE_URL ?? '';

type RequestOptions = RequestInit & {
  json?: unknown;
};

export type PaginatedResponse<T = Record<string, unknown>> = {
  current_page: number;
  data: T[];
  first_page_url?: string;
  from: number | null;
  last_page: number;
  last_page_url?: string;
  links?: Array<{
    url: string | null;
    label: string;
    active: boolean;
  }>;
  next_page_url: string | null;
  path?: string;
  per_page: number;
  prev_page_url: string | null;
  to: number | null;
  total: number;
};

export type SegmentOption = {
  id: number;
  name: string;
  slug: string;
  description: string | null;
};

export type AppModule = {
  key: string;
  name: string;
  description: string | null;
  scope: string;
  category: string;
  is_core: boolean;
  api_prefix: string | null;
  navigation_label: string | null;
  navigation_path: string | null;
  icon: string | null;
  config: Record<string, unknown> | null;
};

export type AppNavigationItem = {
  label: string;
  path: string;
  module: string;
  icon: string | null;
  api_prefix: string | null;
  category: string;
};

export type AppBootstrap = {
  user: AuthUser & {
    permissions: string[];
  };
  tenant: AuthUser['tenant'] & {
    slug?: string | null;
    status?: string;
    branding?: {
      logo_path: string | null;
      primary_color: string;
      secondary_color: string;
      accent_color: string;
      custom_domain: string | null;
    } | null;
  };
  modules: AppModule[];
  navigation: AppNavigationItem[];
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

export async function request<T>(path: string, options: RequestOptions = {}): Promise<T> {
  const headers = new Headers(options.headers);

  headers.set('Accept', 'application/json');

  if (options.json !== undefined) {
    headers.set('Content-Type', 'application/json');
  }

  if (options.method && options.method !== 'GET') {
    await csrfCookie();
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
  const response = await request<ApiEnvelope<AuthUser>>('/api/auth/login', {
    method: 'POST',
    json: payload,
  });

  return response.data;
}

export async function registerTenant(payload: RegisterTenantPayload): Promise<AuthUser> {
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

export async function appBootstrap(): Promise<AppBootstrap> {
  const response = await request<ApiEnvelope<AppBootstrap>>('/api/app/bootstrap');

  return response.data;
}

export async function listSegments(): Promise<SegmentOption[]> {
  const response = await request<ApiEnvelope<SegmentOption[]>>('/api/segments');

  return response.data;
}

export async function logout(): Promise<void> {
  await request<void>('/api/auth/logout', {
    method: 'POST',
  });
}

export async function listApiResource<T = Record<string, unknown>>(path: string, perPage = 10): Promise<PaginatedResponse<T>> {
  const separator = path.includes('?') ? '&' : '?';

  return request<PaginatedResponse<T>>(`${path}${separator}per_page=${perPage}`);
}

export const endpointsByArea = {
  platform: '/api/platform/v1',
  support: '/api/support/v1',
  clinic: '/api/clinic/v1',
  portal: '/api/portal/v1',
} as const;

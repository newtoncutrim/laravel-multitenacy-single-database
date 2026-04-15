export type UserArea = 'platform' | 'support' | 'clinic';

export type AuthUser = {
  id: number;
  name: string;
  email: string;
  tenant_id: number | null;
  roles: string[];
  area: UserArea;
  home_path: string;
};

export type ApiEnvelope<T> = {
  data: T;
};

export type LoginPayload = {
  email: string;
  password: string;
  remember?: boolean;
};

export type RegisterTenantPayload = {
  tenant_name: string;
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
};

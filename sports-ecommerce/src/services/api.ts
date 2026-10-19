import {
  ApiCategory,
  ApiBrand,
  ApiProductDetail,
  ApiCart,
  ApiUser,
  ApiOrder,
  ApiResponse,
  ApiPaginatedProducts,
  ApiProductFilterParams,
} from '@/types/api';

const API_BASE = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8088/api/v1';

async function fetchJson<T>(endpoint: string, options: RequestInit = {}): Promise<T> {
  const url = `${API_BASE}${endpoint}`;
  const headers = new Headers(options.headers || {});
  headers.set('Accept', 'application/json');

  if (options.body && !(options.body instanceof FormData)) {
    headers.set('Content-Type', 'application/json');
  }

  const response = await fetch(url, {
    ...options,
    headers,
  });

  const data = await response.json().catch(() => null);

  if (!response.ok) {
    const errorMsg = data?.message || data?.errors ? JSON.stringify(data.errors || data.message) : `HTTP ${response.status}`;
    throw new Error(errorMsg);
  }

  return data as T;
}

// 1. Catalog APIs
export async function getCategories(): Promise<ApiCategory[]> {
  const res = await fetchJson<ApiResponse<ApiCategory[]>>('/categories');
  return res.data || [];
}

export async function getBrands(): Promise<ApiBrand[]> {
  const res = await fetchJson<ApiResponse<ApiBrand[]>>('/brands');
  return res.data || [];
}

export async function getProducts(params?: ApiProductFilterParams): Promise<ApiPaginatedProducts> {
  const query = new URLSearchParams();
  if (params?.keyword) query.set('keyword', params.keyword);
  if (params?.category_id) query.set('category_id', String(params.category_id));
  if (params?.brand_id) query.set('brand_id', String(params.brand_id));
  if (params?.min_price !== undefined) query.set('min_price', String(params.min_price));
  if (params?.max_price !== undefined) query.set('max_price', String(params.max_price));
  if (params?.is_featured !== undefined) query.set('is_featured', params.is_featured ? '1' : '0');
  if (params?.sort_by) query.set('sort_by', params.sort_by);
  if (params?.limit) query.set('limit', String(params.limit));
  if (params?.page) query.set('page', String(params.page));

  const qs = query.toString() ? `?${query.toString()}` : '';
  const res = await fetchJson<{ data: ApiPaginatedProducts }>(`/products${qs}`);
  return res.data;
}

export async function getProductDetail(id: number | string): Promise<ApiProductDetail> {
  const res = await fetchJson<ApiResponse<ApiProductDetail>>(`/products/${id}`);
  return res.data;
}

// 2. Auth APIs
export async function register(payload: {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
}): Promise<{ user: ApiUser }> {
  const res = await fetchJson<ApiResponse<ApiUser>>('/auth/register', {
    method: 'POST',
    body: JSON.stringify(payload),
  });
  return { user: res.data };
}

export async function login(payload: {
  email: string;
  password: string;
}): Promise<{ access_token: string; user: ApiUser }> {
  const res = await fetchJson<{ access_token: string; data: ApiUser }>('/auth/login', {
    method: 'POST',
    body: JSON.stringify(payload),
  });
  return { access_token: res.access_token, user: res.data };
}

export async function logout(token: string): Promise<void> {
  await fetchJson('/auth/logout', {
    method: 'POST',
    headers: { Authorization: `Bearer ${token}` },
  }).catch(() => null);
}

export async function getProfile(token: string): Promise<ApiUser> {
  const res = await fetchJson<ApiResponse<ApiUser>>('/auth/me', {
    headers: { Authorization: `Bearer ${token}` },
  });
  return res.data;
}

export async function updateProfile(
  token: string,
  payload: { name?: string; phone?: string; address?: string }
): Promise<ApiUser> {
  const res = await fetchJson<ApiResponse<ApiUser>>('/auth/me', {
    method: 'POST',
    headers: { Authorization: `Bearer ${token}` },
    body: JSON.stringify(payload),
  });
  return res.data;
}

// 3. Cart APIs
export async function getCart(token?: string): Promise<ApiCart> {
  const headers: Record<string, string> = {};
  if (token) headers['Authorization'] = `Bearer ${token}`;

  const res = await fetchJson<ApiResponse<ApiCart>>('/cart', { headers });
  return res.data;
}

export async function addToCart(variantId: number, quantity: number, token?: string): Promise<{ total_items: number }> {
  const headers: Record<string, string> = {};
  if (token) headers['Authorization'] = `Bearer ${token}`;

  const res = await fetchJson<ApiResponse<{ total_items: number }>>('/cart/items', {
    method: 'POST',
    headers,
    body: JSON.stringify({ product_variant_id: variantId, quantity }),
  });
  return res.data;
}

export async function updateCartItem(itemId: number, quantity: number, token?: string): Promise<void> {
  const headers: Record<string, string> = {};
  if (token) headers['Authorization'] = `Bearer ${token}`;

  await fetchJson<ApiResponse<null>>(`/cart/items/${itemId}`, {
    method: 'PUT',
    headers,
    body: JSON.stringify({ quantity }),
  });
}

export async function removeCartItem(itemId: number, token?: string): Promise<void> {
  const headers: Record<string, string> = {};
  if (token) headers['Authorization'] = `Bearer ${token}`;

  await fetchJson(`/cart/items/${itemId}`, {
    method: 'DELETE',
    headers,
  });
}

export async function clearCart(token?: string): Promise<void> {
  const headers: Record<string, string> = {};
  if (token) headers['Authorization'] = `Bearer ${token}`;

  await fetchJson('/cart/clear', {
    method: 'DELETE',
    headers,
  });
}

export async function updateCartSelection(itemIds: number[], selected: boolean, token?: string): Promise<ApiCart> {
  const headers: Record<string, string> = {};
  if (token) headers['Authorization'] = `Bearer ${token}`;

  const res = await fetchJson<ApiResponse<ApiCart>>('/cart/select', {
    method: 'PUT',
    headers,
    body: JSON.stringify({ item_ids: itemIds, selected }),
  });
  return res.data;
}

// 4. Order APIs
export async function createOrder(
  token: string,
  payload: {
    receiver_name: string;
    receiver_phone: string;
    shipping_address: string;
    payment_method: string;
    note?: string;
  }
): Promise<ApiOrder> {
  const res = await fetchJson<ApiResponse<ApiOrder>>('/orders', {
    method: 'POST',
    headers: { Authorization: `Bearer ${token}` },
    body: JSON.stringify(payload),
  });
  return res.data;
}

export async function getMyOrders(token: string, limit: number = 20): Promise<ApiOrder[]> {
  const res = await fetchJson<{ data: { data: ApiOrder[] } }>(`/orders?limit=${limit}`, {
    headers: { Authorization: `Bearer ${token}` },
  });
  return res.data.data;
}

export async function getOrderDetail(code: string, token: string): Promise<ApiOrder> {
  const res = await fetchJson<ApiResponse<ApiOrder>>(`/orders/${code}`, {
    headers: { Authorization: `Bearer ${token}` },
  });
  return res.data;
}

export async function cancelOrder(code: string, token: string): Promise<ApiOrder> {
  const res = await fetchJson<ApiResponse<ApiOrder>>(`/orders/${code}/cancel`, {
    method: 'PUT',
    headers: { Authorization: `Bearer ${token}` },
  });
  return res.data;
}

export async function createVnpayUrl(orderCode: string, token: string): Promise<string> {
  const res = await fetchJson<ApiResponse<{ url: string }>>('/orders/payment/vnpay/create-url', {
    method: 'POST',
    headers: { Authorization: `Bearer ${token}` },
    body: JSON.stringify({ order_code: orderCode }),
  });
  return res.data.url;
}

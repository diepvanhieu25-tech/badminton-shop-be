export interface ApiResponse<T> {
  status: boolean | string;
  message?: string;
  data: T;
}

export interface ApiCategory {
  id: number;
  name: string;
  image_url: string | null;
  parent_id: number | null;
  children?: ApiCategory[];
}

export interface ApiBrand {
  id: number;
  name: string;
  logo: string | null;
  description?: string | null;
}

export interface ApiProduct {
  id: number;
  name: string;
  sku: string | null;
  thumbnail: string | null;
  price: number;
  original_price: number | null;
  discount_rate: number;
  is_featured: boolean;
  category_name: string;
  brand_name: string;
}

export interface ApiProductVariant {
  id: number;
  sku: string;
  attributes: { code: string; name: string; value: string }[] | Record<string, string>;
  price: number;
  original_price?: number | null;
  stock_qty: number;
  image?: string | null;
  is_in_stock: boolean;
}

export interface ApiProductDetail {
  id: number;
  name: string;
  sku: string | null;
  description: string | null;
  thumbnail: string | null;
  price: number;
  original_price: number | null;
  category: { id: number; name: string };
  brand: { id: number; name: string };
  images: { id: number; image_url: string }[];
  variants: ApiProductVariant[];
  related_products: ApiProduct[];
}

export interface ApiPaginationMeta {
  current_page: number;
  from: number | null;
  last_page: number;
  per_page: number;
  to: number | null;
  total: number;
}

export interface ApiPaginatedProducts {
  data: ApiProduct[];
  links?: Record<string, unknown>;
  meta?: ApiPaginationMeta;
}

export interface ApiProductFilterParams {
  keyword?: string;
  category_id?: number;
  brand_id?: number;
  min_price?: number;
  max_price?: number;
  is_featured?: boolean;
  sort_by?: 'newest' | 'price_asc' | 'price_desc' | 'name_asc';
  limit?: number;
  page?: number;
}

export interface ApiCartItem {
  id: number;
  quantity: number;
  product_variant_id: number;
  sku: string;
  name: string;
  attributes?: Record<string, string> | { code: string; name: string; value: string }[];
  image_url: string | null;
  price: number;
  subtotal: number;
  in_stock: boolean;
  max_qty: number;
  is_selected: boolean;
}

export interface ApiCart {
  id: number | null;
  status?: string;
  total_items: number;
  total_qty: number;
  total_price: number;
  items: ApiCartItem[];
}

export interface ApiUser {
  id: number;
  name: string;
  email: string;
  phone: string | null;
  address?: string | null;
  role: 'admin' | 'customer';
  status: 'active' | 'inactive';
  avatar_url: string | null;
  created_at: string;
}

export interface ApiOrderItem {
  id: number;
  product_name: string;
  variant_name: string;
  quantity: number;
  unit_price: number;
  total_price: number;
  image_url: string | null;
}

export interface ApiOrder {
  id: number;
  code: string;
  receiver_info: {
    name: string;
    phone: string;
    address: string;
  };
  financial: {
    subtotal: number;
    shipping_fee: number;
    total: number;
  };
  status: {
    key: string;
    label: string;
    color: string;
  };
  payment: {
    method: string;
    status: string;
  };
  note?: string | null;
  created_at: string;
  items?: ApiOrderItem[];
}

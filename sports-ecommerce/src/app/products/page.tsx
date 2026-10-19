"use client";

import React, { useEffect, useState, useCallback, Suspense } from 'react';
import { useSearchParams, useRouter } from 'next/navigation';
import Header from '@/components/Header';
import Footer from '@/components/Footer';
import Breadcrumb from '@/components/Breadcrumb';
import ProductCard from '@/components/ProductCard';
import * as api from '@/services/api';
import { ApiCategory, ApiBrand, ApiProduct, ApiPaginationMeta } from '@/types/api';
import { useCart } from '@/context/CartContext';

function ProductsCatalogContent() {
  const router = useRouter();
  const searchParams = useSearchParams();
  const { openMiniCart } = useCart();

  // URL Query State
  const categoryIdParam = searchParams.get('category_id') ? Number(searchParams.get('category_id')) : undefined;
  const brandIdParam = searchParams.get('brand_id') ? Number(searchParams.get('brand_id')) : undefined;
  const keywordParam = searchParams.get('keyword') || '';
  const minPriceParam = searchParams.get('min_price') ? Number(searchParams.get('min_price')) : undefined;
  const maxPriceParam = searchParams.get('max_price') ? Number(searchParams.get('max_price')) : undefined;
  const sortByParam = (searchParams.get('sort_by') as 'newest' | 'price_asc' | 'price_desc' | 'name_asc') || 'newest';
  const isFeaturedParam = searchParams.get('is_featured') === '1';
  const pageParam = searchParams.get('page') ? Number(searchParams.get('page')) : 1;

  // Data States
  const [categories, setCategories] = useState<ApiCategory[]>([]);
  const [brands, setBrands] = useState<ApiBrand[]>([]);
  const [products, setProducts] = useState<ApiProduct[]>([]);
  const [paginationMeta, setPaginationMeta] = useState<ApiPaginationMeta | null>(null);

  // UI States
  const [isLoading, setIsLoading] = useState<boolean>(true);
  const [error, setError] = useState<string | null>(null);

  // Load Categories & Brands once
  useEffect(() => {
    Promise.all([api.getCategories(), api.getBrands()])
      .then(([cats, bnds]) => {
        setCategories(cats || []);
        setBrands(bnds || []);
      })
      .catch(() => null);
  }, []);

  // Fetch Products based on current params
  const fetchProducts = useCallback(async () => {
    setIsLoading(true);
    setError(null);
    try {
      const res = await api.getProducts({
        category_id: categoryIdParam,
        brand_id: brandIdParam,
        keyword: keywordParam || undefined,
        min_price: minPriceParam,
        max_price: maxPriceParam,
        is_featured: isFeaturedParam ? true : undefined,
        sort_by: sortByParam,
        page: pageParam,
        limit: 12,
      });
      setProducts(res.data || []);
      setPaginationMeta(res.meta || null);
    } catch (err: unknown) {
      setError(err instanceof Error ? err.message : 'Không thể tải danh sách sản phẩm');
    } finally {
      setIsLoading(false);
    }
  }, [categoryIdParam, brandIdParam, keywordParam, minPriceParam, maxPriceParam, isFeaturedParam, sortByParam, pageParam]);

  useEffect(() => {
    fetchProducts();
  }, [fetchProducts]);

  // Update query params helper
  const updateFilter = (updates: Record<string, string | number | undefined | null>) => {
    const params = new URLSearchParams(searchParams.toString());
    Object.entries(updates).forEach(([k, v]) => {
      if (v === undefined || v === null || v === '') {
        params.delete(k);
      } else {
        params.set(k, String(v));
      }
    });
    // Reset to page 1 on filter change if page wasn't explicitly changed
    if (!updates.page) {
      params.delete('page');
    }
    router.push(`/products?${params.toString()}`);
  };

  const clearAllFilters = () => {
    router.push('/products');
  };

  const formatVnd = (amount: number) => {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
  };


  const selectedCategoryName = categories.find(c => c.id === categoryIdParam)?.name;
  const selectedBrandName = brands.find(b => b.id === brandIdParam)?.name;

  const hasActiveFilters = Boolean(
    categoryIdParam || brandIdParam || keywordParam || minPriceParam || maxPriceParam || isFeaturedParam
  );

  return (
    <div className="flex flex-col min-h-screen bg-[#f8fafc] text-slate-900">
      <Header />

      <main className="flex-grow max-w-[1400px] w-full mx-auto px-4 lg:px-8 py-6">
        {/* Breadcrumb */}
        <Breadcrumb
          items={[
            { label: 'Sản phẩm', href: '/products' },
            ...(selectedCategoryName ? [{ label: selectedCategoryName }] : []),
            ...(keywordParam ? [{ label: `Tìm kiếm: "${keywordParam}"` }] : []),
          ]}
        />

        {/* Page Banner / Title */}
        <div className="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-2xs">
          <div>
            <h1 className="text-2xl sm:text-3xl font-black uppercase text-slate-900 tracking-tight">
              {selectedCategoryName || (isFeaturedParam ? 'Khuyến Mãi & Deal Sốc' : 'Tất Cả Sản Phẩm Cầu Lông')}
            </h1>
            <p className="text-xs text-slate-500 mt-1 font-medium">
              Vợt, giày và phụ kiện cầu lông chính hãng từ Yonex, Lining, Victor bảo hành 12 tháng.
            </p>
          </div>

          {/* Quick Active Search Term indicator */}
          {keywordParam && (
            <div className="inline-flex items-center gap-2 bg-[#13634B]/10 text-[#13634B] px-3.5 py-1.5 rounded-xl text-xs font-bold">
              <span>Từ khóa: &quot;{keywordParam}&quot;</span>
              <button onClick={() => updateFilter({ keyword: undefined })} className="hover:text-rose-600 cursor-pointer">
                ✕
              </button>
            </div>
          )}
        </div>

        {/* Main Grid: Sidebar + Product Grid */}
        <div className="grid grid-cols-1 lg:grid-cols-4 gap-8">
          {/* Left Sidebar Filter */}
          <aside className="lg:col-span-1 space-y-6">
            <div className="bg-white rounded-3xl border border-slate-200/90 p-5 shadow-xs space-y-6 sticky top-24">
              <div className="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 className="font-bold text-xs uppercase tracking-wider text-slate-900 flex items-center gap-2">
                  <span className="material-symbols-outlined text-[#13634B] text-base">filter_list</span>
                  Bộ lọc sản phẩm
                </h3>
                {hasActiveFilters && (
                  <button
                    onClick={clearAllFilters}
                    className="text-[11px] text-[#13634B] font-bold hover:underline cursor-pointer"
                  >
                    Xóa tất cả
                  </button>
                )}
              </div>

              {/* 1. Category Filter */}
              <div>
                <h4 className="text-xs font-bold uppercase text-slate-700 mb-3 flex items-center justify-between">
                  <span>Danh mục</span>
                  <span className="text-[10px] text-slate-400 font-mono">{categories.length}</span>
                </h4>
                <div className="space-y-1.5">
                  <button
                    onClick={() => updateFilter({ category_id: undefined })}
                    className={`w-full text-left px-3.5 py-2.5 rounded-xl text-xs font-bold transition flex items-center justify-between cursor-pointer ${
                      categoryIdParam === undefined
                        ? 'bg-[#13634B] text-white shadow-xs'
                        : 'text-slate-700 hover:bg-slate-50 hover:text-[#13634B]'
                    }`}
                  >
                    <span className="flex items-center gap-2">
                      <span className={`w-1.5 h-1.5 rounded-full ${categoryIdParam === undefined ? 'bg-emerald-300' : 'bg-slate-300'}`}></span>
                      Tất cả danh mục
                    </span>
                    {categoryIdParam === undefined && (
                      <span className="material-symbols-outlined text-sm">check</span>
                    )}
                  </button>
                  {categories.map((cat) => {
                    const isSelected = categoryIdParam === cat.id;
                    return (
                      <button
                        key={cat.id}
                        onClick={() => updateFilter({ category_id: cat.id })}
                        className={`w-full text-left px-3.5 py-2.5 rounded-xl text-xs font-bold transition flex items-center justify-between cursor-pointer ${
                          isSelected
                            ? 'bg-[#13634B] text-white shadow-xs'
                            : 'text-slate-700 hover:bg-slate-50 hover:text-[#13634B]'
                        }`}
                      >
                        <span className="flex items-center gap-2">
                          <span className={`w-1.5 h-1.5 rounded-full ${isSelected ? 'bg-emerald-300' : 'bg-slate-300'}`}></span>
                          {cat.name}
                        </span>
                        {isSelected && (
                          <span className="material-symbols-outlined text-sm">check</span>
                        )}
                      </button>
                    );
                  })}
                </div>
              </div>

              {/* 2. Brand Filter */}
              <div className="pt-4 border-t border-slate-100">
                <h4 className="text-xs font-bold uppercase text-slate-700 mb-3">Thương hiệu</h4>
                <div className="space-y-2">
                  {brands.map((brand) => {
                    const isSelected = brandIdParam === brand.id;
                    return (
                      <label
                        key={brand.id}
                        onClick={() => updateFilter({ brand_id: isSelected ? undefined : brand.id })}
                        className="flex items-center gap-2.5 text-xs font-semibold text-slate-700 cursor-pointer hover:text-[#13634B]"
                      >
                        <input
                          type="checkbox"
                          checked={isSelected}
                          onChange={() => {}}
                          className="w-4 h-4 rounded text-[#13634B] focus:ring-[#13634B] border-slate-300"
                        />
                        <span>{brand.name}</span>
                      </label>
                    );
                  })}
                </div>
              </div>

              {/* 3. Price Filter Ranges */}
              <div className="pt-4 border-t border-slate-100">
                <h4 className="text-xs font-bold uppercase text-slate-700 mb-3">Khoảng giá</h4>
                <div className="space-y-2">
                  {[
                    { label: 'Tất cả mức giá', min: undefined, max: undefined },
                    { label: 'Dưới 1.000.000 ₫', min: 0, max: 1000000 },
                    { label: '1.000.000 ₫ - 3.000.000 ₫', min: 1000000, max: 3000000 },
                    { label: 'Trên 3.000.000 ₫', min: 3000000, max: undefined },
                  ].map((range, idx) => {
                    const isSelected = minPriceParam === range.min && maxPriceParam === range.max;
                    return (
                      <button
                        key={idx}
                        onClick={() => updateFilter({ min_price: range.min, max_price: range.max })}
                        className={`w-full text-left px-3 py-1.5 rounded-lg text-xs font-medium transition cursor-pointer ${
                          isSelected
                            ? 'bg-[#13634B]/10 text-[#13634B] font-bold'
                            : 'text-slate-600 hover:text-[#13634B]'
                        }`}
                      >
                        {range.label}
                      </button>
                    );
                  })}
                </div>
              </div>

              {/* 4. Special Tag Filter */}
              <div className="pt-4 border-t border-slate-100">
                <label
                  onClick={() => updateFilter({ is_featured: isFeaturedParam ? undefined : 1 })}
                  className="flex items-center gap-2.5 text-xs font-bold text-slate-800 cursor-pointer hover:text-[#13634B]"
                >
                  <input
                    type="checkbox"
                    checked={isFeaturedParam}
                    onChange={() => {}}
                    className="w-4 h-4 rounded text-[#13634B] focus:ring-[#13634B] border-slate-300"
                  />
                  <span>Chỉ hiện Hot Deal & Khuyến mãi</span>
                </label>
              </div>
            </div>
          </aside>

          {/* Right Product Grid Column */}
          <div className="lg:col-span-3 space-y-6">
            {/* Top Toolbar: Result count & Sort */}
            <div className="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs flex flex-wrap items-center justify-between gap-4">
              <div className="text-xs font-semibold text-slate-500">
                Tìm thấy <strong className="text-slate-900 font-black">{paginationMeta?.total ?? products.length}</strong> sản phẩm
              </div>

              <div className="flex items-center gap-2">
                <span className="text-xs text-slate-400 font-semibold">Sắp xếp:</span>
                <select
                  value={sortByParam}
                  onChange={(e) => updateFilter({ sort_by: e.target.value })}
                  className="px-3 py-1.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold text-slate-800 focus:outline-none focus:border-[#13634B]"
                >
                  <option value="newest">Mới nhất</option>
                  <option value="price_asc">Giá: Thấp đến Cao</option>
                  <option value="price_desc">Giá: Cao đến Thấp</option>
                  <option value="name_asc">Tên: A - Z</option>
                </select>
              </div>
            </div>

            {/* Active Filter Tags */}
            {hasActiveFilters && (
              <div className="flex flex-wrap items-center gap-2">
                <span className="text-[11px] font-bold text-slate-400 uppercase">Đang lọc:</span>
                {selectedCategoryName && (
                  <span className="inline-flex items-center gap-1.5 px-3 py-1 bg-white border border-slate-200 text-slate-700 rounded-full text-xs font-bold">
                    Danh mục: {selectedCategoryName}
                    <button onClick={() => updateFilter({ category_id: undefined })} className="hover:text-rose-600">✕</button>
                  </span>
                )}
                {selectedBrandName && (
                  <span className="inline-flex items-center gap-1.5 px-3 py-1 bg-white border border-slate-200 text-slate-700 rounded-full text-xs font-bold">
                    Hãng: {selectedBrandName}
                    <button onClick={() => updateFilter({ brand_id: undefined })} className="hover:text-rose-600">✕</button>
                  </span>
                )}
                {minPriceParam !== undefined && (
                  <span className="inline-flex items-center gap-1.5 px-3 py-1 bg-white border border-slate-200 text-slate-700 rounded-full text-xs font-bold">
                    Giá: {formatVnd(minPriceParam)} {maxPriceParam ? `- ${formatVnd(maxPriceParam)}` : '+'}
                    <button onClick={() => updateFilter({ min_price: undefined, max_price: undefined })} className="hover:text-rose-600">✕</button>
                  </span>
                )}
                {isFeaturedParam && (
                  <span className="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 border border-amber-200 text-amber-800 rounded-full text-xs font-bold">
                    Hot Deal
                    <button onClick={() => updateFilter({ is_featured: undefined })} className="hover:text-rose-600">✕</button>
                  </span>
                )}
              </div>
            )}

            {/* Loading Skeletons */}
            {isLoading && (
              <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                {[1, 2, 3, 4, 5, 6].map((n) => (
                  <div key={n} className="bg-white rounded-3xl border border-slate-200 p-4 space-y-4 animate-pulse">
                    <div className="w-full aspect-[4/5] bg-slate-100 rounded-2xl"></div>
                    <div className="h-4 bg-slate-100 rounded w-1/3"></div>
                    <div className="h-5 bg-slate-100 rounded w-3/4"></div>
                    <div className="h-6 bg-slate-100 rounded w-1/2"></div>
                  </div>
                ))}
              </div>
            )}

            {/* Error Banner */}
            {!isLoading && error && (
              <div className="bg-rose-50 border border-rose-200 rounded-2xl p-8 text-center space-y-4">
                <span className="material-symbols-outlined text-4xl text-rose-500">wifi_off</span>
                <p className="text-sm font-bold text-rose-700">{error}</p>
                <button
                  onClick={fetchProducts}
                  className="px-6 py-2 rounded-xl bg-[#13634B] text-white text-xs font-bold hover:bg-[#0e4a38] transition cursor-pointer"
                >
                  Thử lại
                </button>
              </div>
            )}

            {/* Empty State */}
            {!isLoading && !error && products.length === 0 && (
              <div className="bg-white rounded-3xl border border-slate-200/90 p-16 text-center space-y-4">
                <span className="material-symbols-outlined text-6xl text-slate-300">inventory_2</span>
                <h3 className="text-base font-bold text-slate-800">
                  Không tìm thấy sản phẩm nào phù hợp
                </h3>
                <p className="text-xs text-slate-400 max-w-sm mx-auto">
                  Hãy thử thay đổi điều kiện lọc, tìm kiếm theo từ khóa khác hoặc xóa toàn bộ bộ lọc.
                </p>
                <button
                  onClick={clearAllFilters}
                  className="px-6 py-2.5 rounded-xl bg-[#13634B] text-white font-bold text-xs uppercase tracking-wider hover:bg-[#0e4a38] transition cursor-pointer"
                >
                  Xóa bộ lọc và xem tất cả
                </button>
              </div>
            )}

            {/* Success: Product Grid with Optimized ProductCard */}
            {!isLoading && !error && products.length > 0 && (
              <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                {products.map((product) => (
                  <ProductCard
                    key={product.id}
                    product={product}
                    onOpenMiniCart={openMiniCart}
                  />
                ))}
              </div>
            )}

            {/* Pagination Controls */}
            {paginationMeta && paginationMeta.last_page > 1 && (
              <div className="flex items-center justify-center gap-2 pt-6">
                <button
                  onClick={() => updateFilter({ page: pageParam - 1 })}
                  disabled={pageParam <= 1}
                  className="px-4 py-2 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-700 hover:border-[#13634B] transition disabled:opacity-40 cursor-pointer"
                >
                  Trang trước
                </button>
                {Array.from({ length: paginationMeta.last_page }, (_, i) => i + 1).map((p) => (
                  <button
                    key={p}
                    onClick={() => updateFilter({ page: p })}
                    className={`w-9 h-9 rounded-xl text-xs font-bold transition cursor-pointer ${
                      p === pageParam
                        ? 'bg-[#13634B] text-white shadow-xs'
                        : 'bg-white border border-slate-200 text-slate-700 hover:border-[#13634B]'
                    }`}
                  >
                    {p}
                  </button>
                ))}
                <button
                  onClick={() => updateFilter({ page: pageParam + 1 })}
                  disabled={pageParam >= paginationMeta.last_page}
                  className="px-4 py-2 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-700 hover:border-[#13634B] transition disabled:opacity-40 cursor-pointer"
                >
                  Trang sau
                </button>
              </div>
            )}
          </div>
        </div>
      </main>

      <Footer />
    </div>
  );
}

export default function ProductsPage() {
  return (
    <Suspense fallback={
      <div className="min-h-screen flex items-center justify-center bg-[#f8fafc]">
        <div className="w-10 h-10 border-4 border-[#13634B] border-t-transparent rounded-full animate-spin"></div>
      </div>
    }>
      <ProductsCatalogContent />
    </Suspense>
  );
}

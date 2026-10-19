"use client";

import React, { useEffect, useState } from "react";
import Link from "next/link";
import Header from "@/components/Header";
import Hero from "@/components/Hero";
import Benefits from "@/components/Benefits";
import Footer from "@/components/Footer";
import ProductCard from "@/components/ProductCard";
import * as api from "@/services/api";
import { ApiCategory, ApiProduct } from "@/types/api";
import { useCart } from "@/context/CartContext";

export default function Home() {
  const { openMiniCart } = useCart();

  const [categories, setCategories] = useState<ApiCategory[]>([]);
  const [featuredProducts, setFeaturedProducts] = useState<ApiProduct[]>([]);
  const [newestProducts, setNewestProducts] = useState<ApiProduct[]>([]);
  const [selectedCatFilter, setSelectedCatFilter] = useState<number | undefined>(undefined);
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    Promise.all([
      api.getCategories(),
      api.getProducts({ is_featured: true, limit: 4 }),
      api.getProducts({ sort_by: 'newest', limit: 8 }),
    ])
      .then(([cats, featuredRes, newestRes]) => {
        setCategories(cats || []);
        setFeaturedProducts(featuredRes.data || []);
        setNewestProducts(newestRes.data || []);
      })
      .catch((e) => console.error(e))
      .finally(() => setIsLoading(false));
  }, []);

  const filteredNewest = selectedCatFilter
    ? newestProducts.filter((p) => {
        const cat = categories.find((c) => c.id === selectedCatFilter);
        return cat ? p.category_name === cat.name : true;
      })
    : newestProducts;

  return (
    <div className="flex flex-col min-h-screen bg-[#f8fafc] text-slate-900">
      <Header />

      <main className="flex-grow space-y-16 pb-16">
        <Hero />
        <Benefits />

        {/* 1. Dynamic Categories From Backend */}
        <section className="max-w-[1400px] mx-auto px-4 lg:px-8">
          <div className="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
            <div>
              <div className="flex items-center gap-2 text-[#13634B] text-xs font-bold uppercase tracking-wider mb-1">
                <span className="w-2.5 h-2.5 rounded-full bg-[#13634B]"></span>
                Danh Mục Sản Phẩm
              </div>
              <h2 className="text-2xl sm:text-3xl font-black uppercase text-slate-900 tracking-tight">
                THIẾT BỊ THI ĐẤU CHÍNH HÃNG
              </h2>
            </div>
            <Link
              href="/products"
              className="text-xs font-bold text-[#13634B] hover:underline flex items-center gap-1 w-fit"
            >
              Xem tất cả danh mục <span className="material-symbols-outlined text-sm">arrow_forward</span>
            </Link>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {categories.map((cat) => (
              <Link
                key={cat.id}
                href={`/products?category_id=${cat.id}`}
                className="group bg-white rounded-2xl border border-slate-200/80 hover:border-[#13634B] overflow-hidden shadow-xs hover:shadow-lg transition-all duration-300 flex flex-col justify-between"
              >
                {/* Category Image directly from Backend */}
                <div className="relative aspect-[16/10] overflow-hidden bg-slate-100">
                  {cat.image_url ? (
                    /* eslint-disable-next-line @next/next/no-img-element */
                    <img
                      src={cat.image_url}
                      alt={cat.name}
                      className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                    />
                  ) : (
                    <div className="w-full h-full flex items-center justify-center text-slate-300">
                      <span className="material-symbols-outlined text-5xl">sports_tennis</span>
                    </div>
                  )}
                  <div className="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                  <div className="absolute bottom-3 left-3 text-white">
                    <h3 className="font-black text-sm uppercase leading-tight drop-shadow-sm">
                      {cat.name}
                    </h3>
                  </div>
                </div>

                <div className="p-4 flex items-center justify-between text-xs font-bold text-slate-700 group-hover:text-[#13634B] transition-colors">
                  <span>Khám phá sản phẩm</span>
                  <span className="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">
                    arrow_forward
                  </span>
                </div>
              </Link>
            ))}
          </div>
        </section>

        {/* 2. Hot Deals Showcase */}
        {featuredProducts.length > 0 && (
          <section className="max-w-[1400px] mx-auto px-4 lg:px-8">
            <div className="bg-gradient-to-r from-[#0a3629] via-[#0f4635] to-[#13634B] rounded-3xl p-6 sm:p-10 text-white shadow-xl relative overflow-hidden">
              <div className="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8 pb-6 border-b border-emerald-800/80">
                <div className="space-y-1">
                  <div className="inline-flex items-center gap-2 px-3 py-1 bg-amber-400 text-slate-950 rounded-full text-xs font-black uppercase tracking-wider">
                    <span className="material-symbols-outlined text-sm animate-pulse">local_fire_department</span>
                    HOT DEALS TUẦN NÀY
                  </div>
                  <h2 className="text-2xl sm:text-3xl font-black uppercase tracking-tight text-white">
                    SIÊU PHẨM THI ĐẤU GIÁ TỐT NHẤT
                  </h2>
                </div>

                <div className="flex items-center gap-3 text-xs font-bold text-emerald-200 flex-shrink-0">
                  <span>Kết thúc sau:</span>
                  <div className="flex items-center gap-1.5 font-mono text-sm font-black text-white">
                    <span className="bg-black/40 px-2.5 py-1.5 rounded-lg border border-emerald-500/30">08h</span>
                    <span>:</span>
                    <span className="bg-black/40 px-2.5 py-1.5 rounded-lg border border-emerald-500/30">45m</span>
                    <span>:</span>
                    <span className="bg-black/40 px-2.5 py-1.5 rounded-lg border border-emerald-500/30">20s</span>
                  </div>
                </div>
              </div>

              {/* Clean ProductCard Grid */}
              <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 relative z-10">
                {featuredProducts.map((p) => (
                  <ProductCard key={p.id} product={p} onOpenMiniCart={openMiniCart} />
                ))}
              </div>
            </div>
          </section>
        )}

        {/* 3. Newest Products Catalog Showcase */}
        <section className="max-w-[1400px] mx-auto px-4 lg:px-8">
          <div className="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
            <div>
              <div className="flex items-center gap-2 text-[#13634B] text-xs font-bold uppercase tracking-wider mb-1">
                <span className="w-2.5 h-2.5 rounded-full bg-[#13634B]"></span>
                Hàng Mới Về
              </div>
              <h2 className="text-2xl sm:text-3xl font-black uppercase text-slate-900 tracking-tight">
                SẢN PHẨM MỚI NHẤT MÙA GIẢI 2024
              </h2>
            </div>

            {/* Category Filter Pills (Direct from Backend) */}
            <div className="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
              <button
                onClick={() => setSelectedCatFilter(undefined)}
                className={`px-4 py-2 rounded-xl text-xs font-bold transition cursor-pointer whitespace-nowrap ${
                  selectedCatFilter === undefined
                    ? 'bg-[#13634B] text-white shadow-xs'
                    : 'bg-white border border-slate-200 text-slate-600 hover:text-[#13634B]'
                }`}
              >
                Tất cả sản phẩm
              </button>
              {categories.map((c) => (
                <button
                  key={c.id}
                  onClick={() => setSelectedCatFilter(c.id)}
                  className={`px-4 py-2 rounded-xl text-xs font-bold transition cursor-pointer whitespace-nowrap ${
                    selectedCatFilter === c.id
                      ? 'bg-[#13634B] text-white shadow-xs'
                      : 'bg-white border border-slate-200 text-slate-600 hover:text-[#13634B]'
                  }`}
                >
                  {c.name}
                </button>
              ))}
            </div>
          </div>

          {/* Product Grid */}
          {isLoading ? (
            <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
              {[1, 2, 3, 4, 5, 6, 7, 8].map((n) => (
                <div key={n} className="bg-white rounded-2xl border border-slate-200 p-4 space-y-4 animate-pulse">
                  <div className="w-full aspect-square bg-slate-100 rounded-xl"></div>
                  <div className="h-4 bg-slate-100 rounded w-1/3"></div>
                  <div className="h-5 bg-slate-100 rounded w-3/4"></div>
                  <div className="h-6 bg-slate-100 rounded w-1/2"></div>
                </div>
              ))}
            </div>
          ) : (
            <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
              {filteredNewest.map((product) => (
                <ProductCard
                  key={product.id}
                  product={product}
                  onOpenMiniCart={openMiniCart}
                />
              ))}
            </div>
          )}

          <div className="text-center pt-10">
            <Link
              href="/products"
              className="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl bg-[#13634B] hover:bg-[#0e4a38] text-white font-black text-xs uppercase tracking-wider transition shadow-md shadow-[#13634B]/20"
            >
              <span>Xem toàn bộ sản phẩm DSPORT NEXUS</span>
              <span className="material-symbols-outlined text-sm">arrow_forward</span>
            </Link>
          </div>
        </section>

        {/* 4. Professional BWF Stringing Station Banner */}
        <section className="max-w-[1400px] mx-auto px-4 lg:px-8">
          <div className="bg-white rounded-3xl border border-slate-200/90 p-8 sm:p-12 shadow-xs grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
            <div className="md:col-span-8 space-y-4">
              <span className="text-xs font-black text-[#13634B] uppercase tracking-wider">
                DỊCH VỤ SHOWROOM DSPORT NEXUS
              </span>
              <h2 className="text-2xl sm:text-3xl font-black uppercase text-slate-900 leading-tight">
                TRẠM CĂNG VỢT ĐIỆN TỬ 4 NÚT YONEX PRECISION 9.0
              </h2>
              <p className="text-xs sm:text-sm text-slate-600 leading-relaxed max-w-2xl">
                Toàn bộ quy trình căng dây tại 58 chi nhánh DSPORT NEXUS đều được thực hiện trên hệ thống máy điện tử thế hệ mới nhất với sai số dưới 0.1kg. Kỹ thuật viên đạt chứng chỉ quốc tế, bảo vệ tối đa vành khung và đan miễn phí tiền công trọn đời sản phẩm.
              </p>
              <div className="flex flex-wrap gap-4 pt-2">
                <Link
                  href="/products?category_id=1"
                  className="px-6 py-3 rounded-xl bg-[#13634B] text-white text-xs font-bold uppercase tracking-wider hover:bg-[#0e4a38] transition"
                >
                  Chọn vợt và chọn mức kg
                </Link>
                <Link
                  href="/products?category_id=4"
                  className="px-6 py-3 rounded-xl border border-slate-200 text-slate-700 text-xs font-bold uppercase tracking-wider hover:border-[#13634B] hover:text-[#13634B] transition"
                >
                  Xem các loại cước thi đấu
                </Link>
              </div>
            </div>

            <div className="md:col-span-4 flex justify-center">
              <div className="w-52 h-52 rounded-full bg-[#13634B]/10 flex flex-col items-center justify-center text-center p-6 border-2 border-dashed border-[#13634B]/30">
                <span className="material-symbols-outlined text-4xl text-[#13634B] mb-2">verified</span>
                <span className="text-xs font-black uppercase text-slate-900">BẢO HÀNH CĂNG CƯỚC</span>
                <span className="text-[11px] text-slate-500 mt-1">Đổi cước mới 100% nếu đứt trong 24h</span>
              </div>
            </div>
          </div>
        </section>
      </main>

      <Footer />
    </div>
  );
}

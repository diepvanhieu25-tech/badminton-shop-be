"use client";

import React, { useState } from 'react';
import Link from 'next/link';
import { ApiProduct } from '@/types/api';
import { useCart } from '@/context/CartContext';
import * as api from '@/services/api';

interface ProductCardProps {
  product: ApiProduct;
  onOpenMiniCart?: () => void;
}

export default function ProductCard({ product, onOpenMiniCart }: ProductCardProps) {
  const { addItem } = useCart();
  const [isAdding, setIsAdding] = useState(false);
  const [justAdded, setJustAdded] = useState(false);

  const formatVnd = (amount: number) => {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
  };

  const handleQuickAdd = async (e: React.MouseEvent) => {
    e.preventDefault();
    e.stopPropagation();
    setIsAdding(true);
    try {
      const detail = await api.getProductDetail(product.id);
      const defaultVariant = detail.variants?.[0];
      const variantId = defaultVariant ? defaultVariant.id : product.id;

      await addItem(variantId, 1, {
        name: product.name,
        price: defaultVariant ? defaultVariant.price : product.price,
        sku: defaultVariant ? defaultVariant.sku : (product.sku || `SKU-${product.id}`),
        image_url: product.thumbnail,
      });

      setJustAdded(true);
      setTimeout(() => setJustAdded(false), 2000);

      if (onOpenMiniCart) {
        onOpenMiniCart();
      }
    } catch (err: unknown) {
      alert(err instanceof Error ? err.message : 'Lỗi khi thêm vào giỏ hàng');
    } finally {
      setIsAdding(false);
    }
  };

  const hasDiscount = product.original_price && product.original_price > product.price;

  return (
    <div className="group bg-white rounded-2xl border border-slate-200/80 hover:border-[#13634B] transition-all duration-300 flex flex-col hover:shadow-lg hover:shadow-slate-200/60 relative overflow-hidden">
      {/* 1. Clean Pristine Image Canvas */}
      <Link href={`/products/${product.id}`} className="block relative aspect-square bg-[#F8FAFC] p-6 overflow-hidden border-b border-slate-100/80">
        {product.thumbnail ? (
          /* eslint-disable-next-line @next/next/no-img-element */
          <img
            alt={product.name}
            className="w-full h-full object-contain group-hover:scale-106 transition-transform duration-500 ease-out"
            src={product.thumbnail}
          />
        ) : (
          <div className="w-full h-full flex flex-col items-center justify-center text-slate-300 group-hover:text-[#13634B] transition-colors">
            <span className="material-symbols-outlined text-6xl">sports_tennis</span>
          </div>
        )}

        {/* Minimal Badges */}
        <div className="absolute top-3 left-3 flex flex-col gap-1 z-10">
          {product.brand_name && (
            <span className="bg-slate-900/90 text-white text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider backdrop-blur-xs">
              {product.brand_name}
            </span>
          )}
        </div>

        {product.discount_rate > 0 && (
          <div className="absolute top-3 right-3 bg-amber-500 text-white text-[10px] font-black px-2 py-0.5 rounded uppercase shadow-xs">
            -{product.discount_rate}%
          </div>
        )}

        {/* Floating Quick Add Button on Hover */}
        <div className="absolute inset-x-4 bottom-4 opacity-0 group-hover:opacity-100 translate-y-2 group-hover:translate-y-0 transition-all duration-300 z-10">
          <button
            onClick={handleQuickAdd}
            disabled={isAdding}
            className={`w-full py-2.5 px-4 rounded-xl text-xs font-bold uppercase tracking-wider flex items-center justify-center gap-1.5 transition-all shadow-md cursor-pointer ${
              justAdded
                ? 'bg-emerald-600 text-white'
                : 'bg-[#13634B] hover:bg-[#0e4a38] text-white'
            }`}
          >
            <span className="material-symbols-outlined text-base">
              {justAdded ? 'check' : isAdding ? 'sync' : 'add_shopping_cart'}
            </span>
            <span>{justAdded ? 'Đã thêm vào giỏ' : isAdding ? 'Đang thêm...' : 'Thêm vào giỏ'}</span>
          </button>
        </div>
      </Link>

      {/* 2. Elegant Product Info Block */}
      <div className="p-4 sm:p-5 flex flex-col flex-1 justify-between bg-white space-y-3">
        <div>
          {/* Subtle Category Tag */}
          <div className="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1 truncate">
            {product.category_name}
          </div>

          {/* Product Title */}
          <Link href={`/products/${product.id}`} className="block">
            <h3
              className="text-xs sm:text-sm font-bold text-slate-900 line-clamp-2 leading-snug group-hover:text-[#13634B] transition-colors"
              title={product.name}
            >
              {product.name}
            </h3>
          </Link>
        </div>

        {/* Pricing Block */}
        <div className="pt-2 border-t border-slate-100 flex items-baseline justify-between">
          <div className="flex items-baseline gap-2">
            <span className="text-[#13634B] font-black text-base sm:text-lg">
              {formatVnd(product.price)}
            </span>
            {hasDiscount && (
              <span className="text-xs text-slate-400 line-through">
                {formatVnd(product.original_price!)}
              </span>
            )}
          </div>

          <Link
            href={`/products/${product.id}`}
            className="text-xs font-bold text-slate-400 group-hover:text-[#13634B] flex items-center gap-0.5 transition"
          >
            <span>Chi tiết</span>
            <span className="material-symbols-outlined text-sm">chevron_right</span>
          </Link>
        </div>
      </div>
    </div>
  );
}

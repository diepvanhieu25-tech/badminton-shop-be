"use client";

import React, { useEffect, useState } from 'react';
import { ApiProductDetail, ApiProductVariant } from '@/types/api';
import * as api from '@/services/api';
import { useCart } from '@/context/CartContext';

interface ProductDetailModalProps {
  productId: number | null;
  onClose: () => void;
  onOpenCart: () => void;
}

export default function ProductDetailModal({
  productId,
  onClose,
  onOpenCart,
}: ProductDetailModalProps) {
  const { addItem } = useCart();

  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [product, setProduct] = useState<ApiProductDetail | null>(null);
  const [selectedVariant, setSelectedVariant] = useState<ApiProductVariant | null>(null);
  const [quantity, setQuantity] = useState(1);
  const [isAdding, setIsAdding] = useState(false);
  const [addSuccess, setAddSuccess] = useState(false);

  useEffect(() => {
    if (!productId) {
      setProduct(null);
      setSelectedVariant(null);
      return;
    }

    let isMounted = true;
    setLoading(true);
    setError(null);
    setQuantity(1);
    setAddSuccess(false);

    api.getProductDetail(productId)
      .then((data) => {
        if (isMounted) {
          setProduct(data);
          if (data.variants && data.variants.length > 0) {
            setSelectedVariant(data.variants[0]);
          }
        }
      })
      .catch((err) => {
        if (isMounted) {
          setError(err instanceof Error ? err.message : 'Không thể tải thông tin sản phẩm.');
        }
      })
      .finally(() => {
        if (isMounted) setLoading(false);
      });

    return () => {
      isMounted = false;
    };
  }, [productId]);

  if (!productId) return null;

  const formatVnd = (amount: number) => {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
  };

  const getVariantLabel = (v: ApiProductVariant): string => {
    if (!v.attributes) return v.sku;
    if (Array.isArray(v.attributes)) {
      return v.attributes.map(a => `${a.name}: ${a.value}`).join(' | ');
    }
    return Object.entries(v.attributes).map(([k, val]) => `${k.toUpperCase()}: ${val}`).join(' | ');
  };

  const handleAddToCart = async (goToCart = false) => {
    if (!selectedVariant || !product) return;
    setIsAdding(true);
    try {
      await addItem(selectedVariant.id, quantity, {
        name: product.name,
        price: selectedVariant.price,
        sku: selectedVariant.sku,
        image_url: selectedVariant.image || product.thumbnail,
        attributes: selectedVariant.attributes,
      });

      setAddSuccess(true);
      setTimeout(() => setAddSuccess(false), 2500);

      if (goToCart) {
        onClose();
        onOpenCart();
      }
    } catch (err: unknown) {
      alert(err instanceof Error ? err.message : 'Lỗi khi thêm vào giỏ');
    } finally {
      setIsAdding(false);
    }
  };

  const currentPrice = selectedVariant ? selectedVariant.price : (product?.price || 0);
  const currentOriginalPrice = selectedVariant?.original_price || product?.original_price;
  const currentStock = selectedVariant ? selectedVariant.stock_qty : 0;
  const isAvailable = currentStock > 0;

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs p-4 overflow-y-auto">
      <div 
        className="bg-white text-slate-900 w-full max-w-4xl rounded-3xl shadow-2xl border border-slate-200 overflow-hidden relative my-8"
        onClick={(e) => e.stopPropagation()}
      >
        {/* Close Button */}
        <button
          onClick={onClose}
          className="absolute top-4 right-4 z-10 w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-800 transition cursor-pointer"
        >
          <span className="material-symbols-outlined text-xl">close</span>
        </button>

        {loading ? (
          <div className="p-16 flex flex-col items-center justify-center space-y-4">
            <div className="w-12 h-12 border-4 border-[#13634B] border-t-transparent rounded-full animate-spin"></div>
            <p className="text-xs font-bold text-slate-500">Đang tải thông số sản phẩm...</p>
          </div>
        ) : error ? (
          <div className="p-12 text-center space-y-4">
            <span className="material-symbols-outlined text-5xl text-rose-500">error</span>
            <p className="text-base font-bold text-rose-600">{error}</p>
            <button
              onClick={onClose}
              className="px-6 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-xs font-bold transition"
            >
              Đóng lại
            </button>
          </div>
        ) : product ? (
          <div className="grid grid-cols-1 md:grid-cols-2 gap-8 p-6 sm:p-8">
            {/* Left: Product Image on Pure Clean Light Backdrop */}
            <div className="flex flex-col gap-4">
              <div className="relative aspect-square rounded-2xl bg-slate-50 border border-slate-200/80 overflow-hidden flex items-center justify-center p-6 group">
                {product.thumbnail ? (
                  /* eslint-disable-next-line @next/next/no-img-element */
                  <img
                    src={product.thumbnail}
                    alt={product.name}
                    className="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500"
                  />
                ) : (
                  <div className="flex flex-col items-center justify-center text-slate-300">
                    <span className="material-symbols-outlined text-8xl">sports_tennis</span>
                    <span className="text-xs font-bold uppercase tracking-widest mt-2">Badminton Pro</span>
                  </div>
                )}

                {/* Brand Badge */}
                <span className="absolute top-4 left-4 bg-[#0a3629] text-white text-[11px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                  {product.brand?.name || 'Chính Hãng'}
                </span>

                {/* Stock Status Pill */}
                <div className={`absolute bottom-4 left-4 flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold backdrop-blur-xs ${
                  isAvailable ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200'
                }`}>
                  <span className={`w-2 h-2 rounded-full ${isAvailable ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500'}`}></span>
                  {isAvailable ? `Còn hàng (${currentStock} cái)` : 'Hết hàng'}
                </div>
              </div>

              {/* Trust Badges Bar */}
              <div className="grid grid-cols-3 gap-2 text-center text-[11px] text-slate-500 py-2 border-t border-b border-slate-100">
                <div className="flex flex-col items-center gap-1">
                  <span className="material-symbols-outlined text-[#13634B] text-lg">verified</span>
                  <span className="font-semibold">100% Chính Hãng</span>
                </div>
                <div className="flex flex-col items-center gap-1">
                  <span className="material-symbols-outlined text-[#13634B] text-lg">sync</span>
                  <span className="font-semibold">Đổi trả 15 ngày</span>
                </div>
                <div className="flex flex-col items-center gap-1">
                  <span className="material-symbols-outlined text-[#13634B] text-lg">local_shipping</span>
                  <span className="font-semibold">Giao nhanh 2h</span>
                </div>
              </div>
            </div>

            {/* Right: Info, Variant Switcher & CTA */}
            <div className="flex flex-col justify-between">
              <div>
                <div className="flex items-center gap-2 mb-2 text-xs font-bold text-[#13634B] uppercase tracking-wider">
                  <span>{product.category?.name}</span>
                  <span>•</span>
                  <span>Mã SKU: <span className="font-mono text-slate-600">{selectedVariant?.sku || product.sku}</span></span>
                </div>

                <h2 className="text-2xl font-black text-slate-900 leading-tight mb-3">
                  {product.name}
                </h2>

                {/* Price Display */}
                <div className="flex items-baseline gap-3 mb-6 pb-4 border-b border-slate-100">
                  <span className="text-3xl font-black text-[#13634B]">
                    {formatVnd(currentPrice)}
                  </span>
                  {currentOriginalPrice && currentOriginalPrice > currentPrice && (
                    <span className="text-base text-slate-400 line-through font-medium">
                      {formatVnd(currentOriginalPrice)}
                    </span>
                  )}
                  {currentOriginalPrice && currentOriginalPrice > currentPrice && (
                    <span className="bg-amber-100 text-amber-800 text-xs font-black px-2 py-0.5 rounded-full">
                      -{Math.round(((currentOriginalPrice - currentPrice) / currentOriginalPrice) * 100)}%
                    </span>
                  )}
                </div>

                {/* Product Description */}
                <p className="text-xs sm:text-sm text-slate-600 leading-relaxed mb-6">
                  {product.description || 'Sản phẩm thi đấu thể thao cao cấp chính hãng, được phân phối và bảo hành chính hãng toàn quốc.'}
                </p>

                {/* Variant Selector */}
                {product.variants && product.variants.length > 0 && (
                  <div className="mb-6">
                    <label className="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                      Chọn phiên bản / Thông số:
                    </label>
                    <div className="flex flex-wrap gap-2">
                      {product.variants.map((v) => {
                        const isSelected = selectedVariant?.id === v.id;
                        return (
                          <button
                            key={v.id}
                            type="button"
                            onClick={() => {
                              setSelectedVariant(v);
                              setQuantity(1);
                            }}
                            className={`px-4 py-2.5 rounded-xl text-xs font-bold transition-all border cursor-pointer ${
                              isSelected
                                ? 'bg-[#13634B] text-white border-[#13634B] shadow-sm shadow-[#13634B]/30 ring-2 ring-[#13634B]/15'
                                : 'bg-slate-50 border-slate-200 text-slate-700 hover:border-[#13634B]'
                            }`}
                          >
                            {getVariantLabel(v)}
                          </button>
                        );
                      })}
                    </div>
                  </div>
                )}

                {/* Quantity Selector */}
                <div className="mb-6">
                  <label className="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                    Số lượng:
                  </label>
                  <div className="flex items-center gap-3">
                    <div className="flex items-center rounded-xl border border-slate-200 bg-slate-50 p-1">
                      <button
                        type="button"
                        onClick={() => setQuantity(Math.max(1, quantity - 1))}
                        disabled={quantity <= 1}
                        className="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-slate-600 hover:bg-white transition disabled:opacity-30 cursor-pointer"
                      >
                        -
                      </button>
                      <span className="w-12 text-center text-sm font-black text-slate-900">
                        {quantity}
                      </span>
                      <button
                        type="button"
                        onClick={() => setQuantity(Math.min(currentStock, quantity + 1))}
                        disabled={quantity >= currentStock}
                        className="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-slate-600 hover:bg-white transition disabled:opacity-30 cursor-pointer"
                      >
                        +
                      </button>
                    </div>
                    <span className="text-xs text-slate-400">
                      Tồn kho: {currentStock} sản phẩm
                    </span>
                  </div>
                </div>
              </div>

              {/* Action Buttons */}
              <div className="space-y-3 pt-4 border-t border-slate-100">
                {addSuccess && (
                  <div className="flex items-center gap-2 p-2.5 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                    <span className="material-symbols-outlined text-sm">check_circle</span>
                    Đã thêm vào giỏ hàng thành công!
                  </div>
                )}

                <div className="grid grid-cols-2 gap-3">
                  <button
                    type="button"
                    onClick={() => handleAddToCart(false)}
                    disabled={!isAvailable || isAdding}
                    className="py-3.5 px-4 rounded-xl border-2 border-[#13634B] text-[#13634B] hover:bg-[#13634B]/5 font-bold text-xs sm:text-sm uppercase tracking-wide transition flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer"
                  >
                    <span className="material-symbols-outlined text-base">add_shopping_cart</span>
                    {isAdding ? 'Đang thêm...' : 'Thêm vào giỏ'}
                  </button>

                  <button
                    type="button"
                    onClick={() => handleAddToCart(true)}
                    disabled={!isAvailable || isAdding}
                    className="py-3.5 px-4 rounded-xl bg-[#13634B] hover:bg-[#0e4a38] text-white font-bold text-xs sm:text-sm uppercase tracking-wide transition shadow-md shadow-[#13634B]/20 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer"
                  >
                    <span className="material-symbols-outlined text-base">bolt</span>
                    Mua ngay
                  </button>
                </div>
              </div>
            </div>
          </div>
        ) : null}
      </div>
    </div>
  );
}

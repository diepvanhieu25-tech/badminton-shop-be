"use client";

import React, { useEffect } from 'react';
import Link from 'next/link';
import { useRouter } from 'next/navigation';
import { useCart } from '@/context/CartContext';

const FREESHIP_THRESHOLD = 1000000; // 1,000,000 VND

export default function MiniCart() {
  const router = useRouter();
  const {
    cart,
    cartCount,
    isMiniCartOpen,
    closeMiniCart,
    updateQty,
    removeItem,
    isLoading,
  } = useCart();

  // Close on Escape key press
  useEffect(() => {
    const handleKeyDown = (e: KeyboardEvent) => {
      if (e.key === 'Escape' && isMiniCartOpen) {
        closeMiniCart();
      }
    };
    window.addEventListener('keydown', handleKeyDown);
    return () => window.removeEventListener('keydown', handleKeyDown);
  }, [isMiniCartOpen, closeMiniCart]);

  // Prevent body scrolling when open
  useEffect(() => {
    if (isMiniCartOpen) {
      document.body.style.overflow = 'hidden';
    } else {
      document.body.style.overflow = 'unset';
    }
    return () => {
      document.body.style.overflow = 'unset';
    };
  }, [isMiniCartOpen]);

  if (!isMiniCartOpen) return null;

  const formatVnd = (amount: number) => {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
  };

  const totalPrice = cart?.total_price || 0;
  const freeshipProgress = Math.min(100, Math.round((totalPrice / FREESHIP_THRESHOLD) * 100));
  const remainingForFreeship = Math.max(0, FREESHIP_THRESHOLD - totalPrice);

  const handleCheckoutClick = () => {
    closeMiniCart();
    router.push('/checkout');
  };

  const handleViewCartClick = () => {
    closeMiniCart();
    router.push('/cart');
  };

  return (
    <div className="fixed inset-0 z-50 flex justify-end">
      {/* Backdrop */}
      <div
        onClick={closeMiniCart}
        className="fixed inset-0 bg-slate-950/50 backdrop-blur-xs transition-opacity duration-300 animate-fadeIn"
        aria-hidden="true"
      />

      {/* Flyout Drawer */}
      <div className="relative w-full max-w-md bg-white h-full shadow-2xl flex flex-col z-10 animate-slideLeft">
        {/* 1. Header */}
        <div className="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between bg-white">
          <div className="flex items-center gap-2.5">
            <div className="w-9 h-9 rounded-xl bg-[#13634B]/10 text-[#13634B] flex items-center justify-center">
              <span className="material-symbols-outlined text-xl">shopping_bag</span>
            </div>
            <div>
              <h2 className="text-base font-black uppercase text-slate-900 tracking-tight flex items-center gap-2">
                Giỏ Hàng Của Bạn
                <span className="bg-[#13634B] text-white text-[11px] font-bold px-2 py-0.5 rounded-full">
                  {cartCount}
                </span>
              </h2>
              <p className="text-[11px] text-slate-400 font-medium">DSPORT NEXUS • Phân phối chính hãng</p>
            </div>
          </div>

          <button
            onClick={closeMiniCart}
            title="Đóng"
            className="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-900 flex items-center justify-center transition cursor-pointer"
          >
            <span className="material-symbols-outlined text-lg">close</span>
          </button>
        </div>

        {/* 2. Free Shipping Bar */}
        <div className="bg-emerald-50/70 border-b border-emerald-100 px-4 sm:px-5 py-3">
          <div className="flex items-center justify-between text-xs font-bold mb-1.5">
            {remainingForFreeship > 0 ? (
              <span className="text-slate-700 flex items-center gap-1.5">
                <span className="material-symbols-outlined text-base text-[#13634B]">local_shipping</span>
                Mua thêm <strong className="text-[#13634B]">{formatVnd(remainingForFreeship)}</strong> để nhận Freeship
              </span>
            ) : (
              <span className="text-emerald-700 flex items-center gap-1.5">
                <span className="material-symbols-outlined text-base text-emerald-600">verified</span>
                🎉 Bạn đã đủ điều kiện <strong>Miễn Phí Vận Chuyển</strong>!
              </span>
            )}
            <span className="text-[11px] text-slate-500 font-mono">{freeshipProgress}%</span>
          </div>
          <div className="w-full h-2 bg-emerald-200/60 rounded-full overflow-hidden">
            <div
              className="h-full bg-[#13634B] rounded-full transition-all duration-500 ease-out"
              style={{ width: `${freeshipProgress}%` }}
            />
          </div>
        </div>

        {/* 3. Items List or Empty State */}
        <div className="flex-1 overflow-y-auto p-4 sm:p-5 space-y-4">
          {!cart || cart.items.length === 0 ? (
            <div className="h-full flex flex-col items-center justify-center text-center p-6 space-y-4">
              <div className="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center text-slate-300">
                <span className="material-symbols-outlined text-5xl">shopping_cart</span>
              </div>
              <div>
                <h3 className="text-base font-bold text-slate-800">Giỏ hàng của bạn đang trống</h3>
                <p className="text-xs text-slate-400 mt-1 max-w-xs">
                  Chưa có sản phẩm nào trong giỏ hàng. Hãy khám phá các dòng vợt, giày và phụ kiện mới nhất!
                </p>
              </div>
              <button
                onClick={() => {
                  closeMiniCart();
                  router.push('/products');
                }}
                className="px-6 py-2.5 rounded-xl bg-[#13634B] hover:bg-[#0e4a38] text-white text-xs font-bold uppercase tracking-wider transition shadow-md shadow-[#13634B]/20 cursor-pointer"
              >
                Khám phá sản phẩm ngay
              </button>
            </div>
          ) : (
            <div className="space-y-3">
              {cart.items.map((item) => (
                <div
                  key={item.id}
                  className="flex items-center gap-3 p-3 bg-white rounded-2xl border border-slate-200/80 hover:border-slate-300 transition group"
                >
                  {/* Thumbnail */}
                  <div className="w-18 h-18 rounded-xl bg-slate-50 border border-slate-100 flex-shrink-0 p-1 flex items-center justify-center overflow-hidden">
                    {item.image_url ? (
                      /* eslint-disable-next-line @next/next/no-img-element */
                      <img
                        src={item.image_url}
                        alt={item.name}
                        className="w-full h-full object-contain group-hover:scale-105 transition-transform"
                      />
                    ) : (
                      <span className="material-symbols-outlined text-2xl text-slate-300">sports_tennis</span>
                    )}
                  </div>

                  {/* Item Details */}
                  <div className="flex-1 min-w-0">
                    <Link
                      href={`/products`}
                      onClick={closeMiniCart}
                      className="block text-xs font-bold text-slate-900 hover:text-[#13634B] transition truncate leading-snug"
                      title={item.name}
                    >
                      {item.name}
                    </Link>

                    {item.sku && (
                      <p className="text-[10px] text-slate-400 font-mono mt-0.5 truncate">
                        Mã: {item.sku}
                      </p>
                    )}

                    {/* Attributes / Variant / Stringing info */}
                    {item.attributes && (
                      <div className="flex flex-wrap gap-1 mt-1">
                        {Array.isArray(item.attributes) ? (
                          item.attributes.map((attr, idx) => (
                            <span
                              key={idx}
                              className={`inline-flex items-center text-[10px] font-semibold px-1.5 py-0.5 rounded ${
                                attr.code === 'stringing'
                                  ? 'bg-emerald-50 text-emerald-800 border border-emerald-200'
                                  : 'bg-slate-100 text-slate-700'
                              }`}
                            >
                              {attr.code === 'stringing' ? '🏸 ' : ''}
                              {attr.name || attr.code}: <strong className="ml-1 text-slate-900">{attr.value}</strong>
                            </span>
                          ))
                        ) : (
                          Object.entries(item.attributes).map(([k, val], idx) => (
                            <span
                              key={idx}
                              className="inline-block bg-slate-100 text-slate-700 text-[10px] font-semibold px-1.5 py-0.5 rounded"
                            >
                              {k}: <strong className="text-slate-900">{String(val)}</strong>
                            </span>
                          ))
                        )}
                      </div>
                    )}

                    <div className="flex items-baseline gap-2 mt-1">
                      <span className="text-xs font-black text-[#13634B]">
                        {formatVnd(item.price)}
                      </span>
                    </div>

                    {/* Stepper & Trash */}
                    <div className="flex items-center justify-between mt-2 pt-2 border-t border-slate-100">
                      <div className="flex items-center rounded-lg border border-slate-200 bg-slate-50">
                        <button
                          type="button"
                          onClick={() => updateQty(item.id, item.quantity - 1)}
                          disabled={item.quantity <= 1 || isLoading}
                          className="w-6 h-6 flex items-center justify-center text-slate-500 hover:text-slate-800 disabled:opacity-30 cursor-pointer text-xs font-bold"
                        >
                          -
                        </button>
                        <span className="w-7 text-center text-xs font-black text-slate-800">
                          {item.quantity}
                        </span>
                        <button
                          type="button"
                          onClick={() => updateQty(item.id, item.quantity + 1)}
                          disabled={isLoading}
                          className="w-6 h-6 flex items-center justify-center text-slate-500 hover:text-slate-800 disabled:opacity-30 cursor-pointer text-xs font-bold"
                        >
                          +
                        </button>
                      </div>

                      <button
                        type="button"
                        onClick={() => removeItem(item.id)}
                        disabled={isLoading}
                        title="Xóa khỏi giỏ hàng"
                        className="text-slate-400 hover:text-rose-600 transition p-1 cursor-pointer flex items-center"
                      >
                        <span className="material-symbols-outlined text-base">delete</span>
                      </button>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          )}
        </div>

        {/* 4. Footer Summary & CTAs */}
        {cart && cart.items.length > 0 && (
          <div className="p-4 sm:p-5 border-t border-slate-200 bg-white space-y-3">
            <div className="flex items-center justify-between text-xs">
              <span className="text-slate-500 font-medium">Tạm tính:</span>
              <span className="text-lg font-black text-[#13634B]">
                {formatVnd(totalPrice)}
              </span>
            </div>

            <p className="text-[10px] text-slate-400">
              * Thuế VAT và phí vận chuyển sẽ được tính tại bước thanh toán.
            </p>

            <div className="grid grid-cols-2 gap-3 pt-1">
              <button
                type="button"
                onClick={handleViewCartClick}
                className="py-3 px-4 rounded-xl border border-slate-200 text-slate-800 hover:border-[#13634B] hover:text-[#13634B] font-bold text-xs uppercase tracking-wider transition text-center cursor-pointer"
              >
                Xem giỏ hàng
              </button>

              <button
                type="button"
                onClick={handleCheckoutClick}
                className="py-3 px-4 rounded-xl bg-[#13634B] hover:bg-[#0e4a38] text-white font-bold text-xs uppercase tracking-wider transition shadow-md shadow-[#13634B]/20 text-center flex items-center justify-center gap-1.5 cursor-pointer"
              >
                <span>Thanh toán</span>
                <span className="material-symbols-outlined text-sm">arrow_forward</span>
              </button>
            </div>
          </div>
        )}
      </div>
    </div>
  );
}

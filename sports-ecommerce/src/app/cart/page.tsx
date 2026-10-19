"use client";

import React, { useState } from 'react';
import Link from 'next/link';
import { useRouter } from 'next/navigation';
import Header from '@/components/Header';
import Footer from '@/components/Footer';
import Breadcrumb from '@/components/Breadcrumb';
import { useCart } from '@/context/CartContext';
import { useAuth } from '@/context/AuthContext';
import * as api from '@/services/api';

export default function CartPage() {
  const router = useRouter();
  const { cart, updateQty, removeItem, refreshCart } = useCart();
  const { token } = useAuth();

  const [voucherCode, setVoucherCode] = useState('');
  const [discountAmount, setDiscountAmount] = useState(0);
  const [voucherMsg, setVoucherMsg] = useState<{ text: string; success: boolean } | null>(null);
  const [isClearing, setIsClearing] = useState(false);

  const rawSubtotal = cart?.total_price || 0;
  const freeShipThreshold = 1500000;
  const isFreeShip = rawSubtotal >= freeShipThreshold;
  const shippingFee = rawSubtotal === 0 ? 0 : (isFreeShip ? 0 : 35000);
  const finalTotal = Math.max(0, rawSubtotal - discountAmount + shippingFee);

  const formatVnd = (amount: number) => {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
  };

  const handleApplyVoucher = (e: React.FormEvent) => {
    e.preventDefault();
    const clean = voucherCode.trim().toUpperCase();
    if (!clean) return;

    if (clean === 'BADMINTON10') {
      const discount = Math.round(rawSubtotal * 0.1);
      setDiscountAmount(discount);
      setVoucherMsg({ text: `Đã áp dụng mã BADMINTON10: Giảm 10% (-${formatVnd(discount)})`, success: true });
    } else if (clean === 'FREESHIP') {
      setDiscountAmount(shippingFee);
      setVoucherMsg({ text: 'Đã áp dụng mã FREESHIP: Miễn phí vận chuyển!', success: true });
    } else {
      setDiscountAmount(0);
      setVoucherMsg({ text: 'Mã giảm giá không hợp lệ hoặc đã hết hạn.', success: false });
    }
  };

  const handleClearCart = async () => {
    const confirmClear = window.confirm('Bạn có chắc chắn muốn xóa toàn bộ sản phẩm trong giỏ hàng?');
    if (!confirmClear) return;
    setIsClearing(true);
    try {
      if (token) {
        await api.clearCart(token);
      }
      if (typeof window !== 'undefined') {
        localStorage.removeItem('badminton_guest_cart');
      }
      await refreshCart();
    } catch (err: unknown) {
      alert(err instanceof Error ? err.message : 'Không thể xóa giỏ hàng');
    } finally {
      setIsClearing(false);
    }
  };

  return (
    <div className="flex flex-col min-h-screen bg-[#f8fafc] text-slate-900">
      <Header />

      <main className="flex-grow max-w-[1400px] w-full mx-auto px-4 lg:px-8 py-6">
        <Breadcrumb items={[{ label: 'Giỏ hàng của bạn' }]} />

        <div className="mb-8">
          <h1 className="text-2xl sm:text-3xl font-black uppercase text-slate-900 tracking-tight">
            GIỎ HÀNG CỦA BẠN
          </h1>
          <p className="text-xs text-slate-500 mt-1 font-medium">
            Kiểm tra danh sách sản phẩm và lựa chọn phương thức giao nhận phù hợp.
          </p>
        </div>

        {!cart || cart.items.length === 0 ? (
          <div className="bg-white rounded-3xl border border-slate-200/90 p-16 text-center space-y-4 shadow-xs">
            <div className="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mx-auto text-slate-400">
              <span className="material-symbols-outlined text-4xl">remove_shopping_cart</span>
            </div>
            <h2 className="text-lg font-bold text-slate-800">
              Giỏ hàng của bạn đang trống
            </h2>
            <p className="text-xs text-slate-400 max-w-sm mx-auto">
              Chưa có sản phẩm nào được chọn. Hãy khám phá các dòng vợt, giày và phụ kiện thi đấu mới nhất nhé!
            </p>
            <Link
              href="/products"
              className="inline-block px-8 py-3 rounded-xl bg-[#13634B] text-white font-bold text-xs uppercase tracking-wider hover:bg-[#0e4a38] transition shadow-md shadow-[#13634B]/20"
            >
              Khám phá sản phẩm ngay
            </Link>
          </div>
        ) : (
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {/* Left: Items Table (8 cols) */}
            <div className="lg:col-span-8 space-y-4">
              <div className="bg-white rounded-3xl border border-slate-200/90 overflow-hidden shadow-xs">
                {/* Table Header */}
                <div className="grid grid-cols-12 gap-4 p-4 border-b border-slate-100 bg-slate-50/70 text-xs font-bold uppercase tracking-wider text-slate-500">
                  <div className="col-span-6">Sản phẩm</div>
                  <div className="col-span-2 text-center">Đơn giá</div>
                  <div className="col-span-2 text-center">Số lượng</div>
                  <div className="col-span-2 text-right">Tổng phụ</div>
                </div>

                {/* Items List */}
                <div className="divide-y divide-slate-100">
                  {cart.items.map((item) => (
                    <div key={item.id} className="grid grid-cols-12 gap-4 p-5 items-center hover:bg-slate-50/50 transition">
                      {/* Product Thumbnail & Name */}
                      <div className="col-span-6 flex items-center gap-4 min-w-0">
                        <div className="w-16 h-16 rounded-xl bg-slate-50 border border-slate-200/80 p-1 flex items-center justify-center shrink-0 overflow-hidden">
                          {item.image_url ? (
                            /* eslint-disable-next-line @next/next/no-img-element */
                            <img src={item.image_url} alt={item.name} className="w-full h-full object-contain" />
                          ) : (
                            <span className="material-symbols-outlined text-[#13634B] text-2xl">sports_tennis</span>
                          )}
                        </div>
                        <div className="min-w-0 flex-1">
                          <h3 className="text-xs font-bold text-slate-900 truncate" title={item.name}>
                            {item.name}
                          </h3>
                          <div className="text-[11px] text-slate-400 font-mono mt-0.5">
                            SKU: {item.sku}
                          </div>
                          {item.attributes && (
                            <div className="flex flex-wrap gap-1 mt-1">
                              {Array.isArray(item.attributes) ? (
                                item.attributes.map((attr, idx) => (
                                  <span
                                    key={idx}
                                    className={`inline-flex items-center text-[10px] font-semibold px-2 py-0.5 rounded ${
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
                                    className="inline-block bg-slate-100 text-slate-700 text-[10px] font-semibold px-2 py-0.5 rounded"
                                  >
                                    {k}: <strong className="text-slate-900">{String(val)}</strong>
                                  </span>
                                ))
                              )}
                            </div>
                          )}
                          <button
                            onClick={() => removeItem(item.id)}
                            className="text-[11px] text-rose-500 hover:text-rose-700 font-bold mt-1.5 inline-flex items-center gap-1 cursor-pointer"
                          >
                            <span className="material-symbols-outlined text-xs">delete</span>
                            Xóa món này
                          </button>
                        </div>
                      </div>

                      {/* Unit Price */}
                      <div className="col-span-2 text-center text-xs font-semibold text-slate-600">
                        {formatVnd(item.price)}
                      </div>

                      {/* Quantity Stepper */}
                      <div className="col-span-2 flex items-center justify-center">
                        <div className="flex items-center rounded-xl border border-slate-200 bg-slate-50 p-1">
                          <button
                            onClick={() => updateQty(item.id, Math.max(1, item.quantity - 1))}
                            className="w-6 h-6 rounded flex items-center justify-center font-bold text-slate-600 hover:bg-white text-xs cursor-pointer"
                          >
                            -
                          </button>
                          <span className="w-8 text-center text-xs font-black text-slate-900">
                            {item.quantity}
                          </span>
                          <button
                            onClick={() => updateQty(item.id, item.quantity + 1)}
                            className="w-6 h-6 rounded flex items-center justify-center font-bold text-slate-600 hover:bg-white text-xs cursor-pointer"
                          >
                            +
                          </button>
                        </div>
                      </div>

                      {/* Line Subtotal */}
                      <div className="col-span-2 text-right text-xs font-black text-[#13634B]">
                        {formatVnd(item.subtotal)}
                      </div>
                    </div>
                  ))}
                </div>

                {/* Table Footer Actions */}
                <div className="p-4 border-t border-slate-100 bg-slate-50/70 flex items-center justify-between">
                  <Link
                    href="/products"
                    className="text-xs font-bold text-[#13634B] hover:underline flex items-center gap-1"
                  >
                    <span className="material-symbols-outlined text-sm">arrow_back</span>
                    Tiếp tục mua hàng
                  </Link>

                  <button
                    onClick={handleClearCart}
                    disabled={isClearing}
                    className="text-xs font-bold text-rose-600 hover:underline flex items-center gap-1 cursor-pointer disabled:opacity-50"
                  >
                    <span className="material-symbols-outlined text-sm">delete_sweep</span>
                    {isClearing ? 'Đang xóa...' : 'Xóa sạch giỏ hàng'}
                  </button>
                </div>
              </div>
            </div>

            {/* Right: Order Summary Sidebar (4 cols) */}
            <div className="lg:col-span-4 space-y-4">
              <div className="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs space-y-6 sticky top-24">
                <h3 className="font-bold text-xs uppercase tracking-wider text-slate-900 pb-3 border-b border-slate-100">
                  Tóm tắt đơn hàng
                </h3>

                {/* Free Shipping Meter */}
                <div className="bg-[#13634B]/5 rounded-2xl p-4 border border-[#13634B]/10 space-y-2">
                  <div className="flex items-center justify-between text-xs font-bold">
                    <span className="flex items-center gap-1.5 text-[#13634B]">
                      <span className="material-symbols-outlined text-sm">local_shipping</span>
                      {isFreeShip ? 'Đã đủ điều kiện FREESHIP!' : `Mua thêm ${formatVnd(freeShipThreshold - rawSubtotal)}`}
                    </span>
                    <span className="text-slate-500">{Math.min(100, Math.round((rawSubtotal / freeShipThreshold) * 100))}%</span>
                  </div>
                  <div className="w-full h-1.5 bg-slate-200 rounded-full overflow-hidden">
                    <div
                      className="h-full bg-[#13634B] rounded-full transition-all duration-500"
                      style={{ width: `${Math.min(100, (rawSubtotal / freeShipThreshold) * 100)}%` }}
                    ></div>
                  </div>
                </div>

                {/* Voucher Apply Form */}
                <form onSubmit={handleApplyVoucher} className="space-y-2">
                  <label className="block text-xs font-bold text-slate-700 uppercase">
                    Mã ưu đãi / Voucher:
                  </label>
                  <div className="flex gap-2">
                    <input
                      type="text"
                      value={voucherCode}
                      onChange={(e) => setVoucherCode(e.target.value)}
                      placeholder="Thử: BADMINTON10"
                      className="flex-1 px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold uppercase focus:outline-none focus:border-[#13634B] focus:bg-white"
                    />
                    <button
                      type="submit"
                      className="px-4 py-2.5 rounded-xl bg-[#13634B] text-white text-xs font-bold hover:bg-[#0e4a38] transition cursor-pointer"
                    >
                      Áp dụng
                    </button>
                  </div>
                  {voucherMsg && (
                    <p className={`text-[11px] font-bold ${voucherMsg.success ? 'text-emerald-700' : 'text-rose-600'}`}>
                      {voucherMsg.text}
                    </p>
                  )}
                </form>

                {/* Calculations */}
                <div className="space-y-2.5 pt-4 border-t border-slate-100 text-xs">
                  <div className="flex justify-between items-center text-slate-500">
                    <span>Tạm tính:</span>
                    <span className="font-bold text-slate-800">{formatVnd(rawSubtotal)}</span>
                  </div>
                  {discountAmount > 0 && (
                    <div className="flex justify-between items-center text-emerald-700 font-bold">
                      <span>Giảm giá:</span>
                      <span>-{formatVnd(discountAmount)}</span>
                    </div>
                  )}
                  <div className="flex justify-between items-center text-slate-500">
                    <span>Phí vận chuyển:</span>
                    <span>{shippingFee === 0 ? <strong className="text-emerald-700 font-bold">MIỄN PHÍ</strong> : formatVnd(shippingFee)}</span>
                  </div>
                  <div className="flex justify-between items-center text-base font-black border-t border-slate-200 pt-3 text-slate-900">
                    <span>Tổng thanh toán:</span>
                    <span className="text-xl text-[#13634B]">{formatVnd(finalTotal)}</span>
                  </div>
                </div>

                {/* Proceed to Checkout Button */}
                <button
                  onClick={() => router.push('/checkout')}
                  className="w-full py-4 rounded-xl bg-[#13634B] hover:bg-[#0e4a38] text-white font-bold text-xs uppercase tracking-wider transition shadow-md shadow-[#13634B]/20 flex items-center justify-center gap-2 cursor-pointer"
                >
                  <span className="material-symbols-outlined text-lg">lock</span>
                  Tiến hành thanh toán
                </button>
              </div>
            </div>
          </div>
        )}
      </main>

      <Footer />
    </div>
  );
}

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

export default function CheckoutPage() {
  const router = useRouter();
  const { cart, refreshCart, isLoading: cartLoading } = useCart();
  const { token, user, login, isLoading: authLoading } = useAuth();

  // Form fields
  const [receiverName, setReceiverName] = useState(user?.name || '');
  const [receiverPhone, setReceiverPhone] = useState(user?.phone || '');
  const [shippingAddress, setShippingAddress] = useState('');
  const [paymentMethod, setPaymentMethod] = useState<'cod' | 'vnpay' | 'momo' | 'bank_transfer'>('cod');
  const [note, setNote] = useState('');

  // UI state
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [error, setError] = useState<string | null>(null);

  // Quick login for unauthenticated users
  const [demoLoginLoading, setDemoLoginLoading] = useState(false);

  const handleDemoLogin = async () => {
    setDemoLoginLoading(true);
    try {
      const newToken = await login('buyer@badminton.vn', 'SecurePassword123!');
      await refreshCart(newToken);
    } catch (err: unknown) {
      alert(err instanceof Error ? err.message : 'Đăng nhập thất bại');
    } finally {
      setDemoLoginLoading(false);
    }
  };

  const rawSubtotal = cart?.total_price || 0;
  const freeShipThreshold = 1500000;
  const isFreeShip = rawSubtotal >= freeShipThreshold;
  const shippingFee = rawSubtotal === 0 ? 0 : (isFreeShip ? 0 : 35000);
  const finalTotal = Math.max(0, rawSubtotal + shippingFee);

  const formatVnd = (amount: number) => {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
  };

  const handlePlaceOrder = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!token) {
      setError('Vui lòng đăng nhập tài khoản để đặt hàng');
      return;
    }

    if (!receiverPhone.match(/^(0)[0-9]{9}$/)) {
      setError('Số điện thoại không hợp lệ (cần 10 chữ số, bắt đầu bằng số 0)');
      return;
    }

    if (!shippingAddress.trim()) {
      setError('Vui lòng nhập địa chỉ nhận hàng chi tiết');
      return;
    }

    setError(null);
    setIsSubmitting(true);
    try {
      const order = await api.createOrder(token, {
        receiver_name: receiverName || user?.name || 'Khách hàng',
        receiver_phone: receiverPhone,
        shipping_address: shippingAddress,
        payment_method: paymentMethod,
        note: note || undefined,
      });

      await refreshCart();

      // If VNPay is selected, redirect to VNPay Gateway URL
      if (paymentMethod === 'vnpay') {
        try {
          const vnpayUrl = await api.createVnpayUrl(order.code, token);
          if (vnpayUrl && (vnpayUrl.startsWith('http://') || vnpayUrl.startsWith('https://') || vnpayUrl.startsWith('/'))) {
            window.location.href = vnpayUrl;
            return;
          }
        } catch (vnpErr) {
          console.error('VNPay URL error:', vnpErr);
        }
      }

      router.push(`/order-success?code=${order.code}`);
    } catch (err: unknown) {
      setError(err instanceof Error ? err.message : 'Đặt hàng thất bại. Vui lòng kiểm tra lại thông tin.');
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <div className="flex flex-col min-h-screen bg-[#f8fafc] text-slate-900">
      <Header />

      <main className="flex-grow max-w-[1400px] w-full mx-auto px-4 lg:px-8 py-6">
        <Breadcrumb
          items={[
            { label: 'Giỏ hàng', href: '/cart' },
            { label: 'Thanh toán đơn hàng' },
          ]}
        />

        {/* Stepper Progress */}
        <div className="max-w-2xl mx-auto my-8">
          <div className="flex items-center justify-between text-xs font-bold uppercase tracking-wider">
            <div className="flex items-center gap-2 text-[#13634B]">
              <span className="w-6 h-6 rounded-full bg-[#13634B] text-white flex items-center justify-center text-xs">✓</span>
              <span>1. Giỏ hàng</span>
            </div>
            <div className="w-16 h-0.5 bg-[#13634B]"></div>
            <div className="flex items-center gap-2 text-[#13634B]">
              <span className="w-6 h-6 rounded-full bg-[#13634B] text-white flex items-center justify-center text-xs">2</span>
              <span>2. Thanh toán</span>
            </div>
            <div className="w-16 h-0.5 bg-slate-200"></div>
            <div className="flex items-center gap-2 text-slate-400">
              <span className="w-6 h-6 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-xs">3</span>
              <span>3. Hoàn tất</span>
            </div>
          </div>
        </div>

        {/* Check loading / auth / empty cart states */}
        {authLoading || (cartLoading && !cart) ? (
          <div className="max-w-md mx-auto bg-white rounded-3xl border border-slate-200/90 p-12 text-center space-y-4 shadow-xs">
            <div className="inline-block animate-spin rounded-full h-9 w-9 border-3 border-[#13634B] border-t-transparent mb-2"></div>
            <h3 className="text-sm font-bold text-slate-700">Đang kiểm tra giỏ hàng và dữ liệu thanh toán...</h3>
          </div>
        ) : !token ? (
          <div className="max-w-md mx-auto bg-white rounded-3xl border border-slate-200/90 p-8 text-center space-y-5 shadow-xs">
            <div className="w-16 h-16 rounded-full bg-emerald-50 text-[#13634B] flex items-center justify-center mx-auto">
              <span className="material-symbols-outlined text-3xl">lock</span>
            </div>
            <h2 className="text-xl font-bold text-slate-900">Đăng nhập để đặt hàng</h2>
            <p className="text-xs text-slate-500 leading-relaxed">
              Bạn cần đăng nhập để lưu trữ thông tin đơn hàng, bảo hành chính hãng và theo dõi lộ trình giao nhận.
            </p>
            <div className="space-y-2 pt-2">
              <Link
                href="/login?returnUrl=/checkout"
                className="block w-full py-3 rounded-xl bg-[#13634B] text-white font-bold text-xs uppercase tracking-wider hover:bg-[#0e4a38] transition shadow-md shadow-[#13634B]/20"
              >
                Đăng nhập tài khoản của bạn
              </Link>
              <button
                type="button"
                onClick={handleDemoLogin}
                disabled={demoLoginLoading}
                className="w-full py-2.5 rounded-xl border border-dashed border-[#13634B]/40 bg-[#13634B]/5 text-[#13634B] font-bold text-xs hover:bg-[#13634B]/10 transition cursor-pointer"
              >
                {demoLoginLoading ? 'Đang xác thực...' : 'Đăng nhập nhanh mẫu (buyer@badminton.vn)'}
              </button>
            </div>
          </div>
        ) : !cart || cart.items.length === 0 ? (
          <div className="max-w-md mx-auto bg-white rounded-3xl border border-slate-200/90 p-12 text-center space-y-4 shadow-xs">
            <span className="material-symbols-outlined text-5xl text-slate-300">shopping_bag</span>
            <h3 className="text-base font-bold text-slate-800">Không có sản phẩm nào để thanh toán</h3>
            <Link
              href="/products"
              className="inline-block px-6 py-2.5 rounded-xl bg-[#13634B] text-white text-xs font-bold hover:bg-[#0e4a38]"
            >
              Chọn sản phẩm ngay
            </Link>
          </div>
        ) : (
          <form onSubmit={handlePlaceOrder} className="grid grid-cols-1 lg:grid-cols-12 gap-8 my-6">
            {/* Left: Shipping & Payment Details (7 cols) */}
            <div className="lg:col-span-7 space-y-6">
              {error && (
                <div className="p-4 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-2xl font-bold flex items-center gap-2">
                  <span className="material-symbols-outlined text-base">error</span>
                  <span>{error}</span>
                </div>
              )}

              {/* 1. Receiver Info */}
              <div className="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-4">
                <div className="flex items-center gap-2 pb-3 border-b border-slate-100">
                  <span className="w-6 h-6 rounded-lg bg-[#13634B]/10 text-[#13634B] flex items-center justify-center font-bold text-xs">1</span>
                  <h3 className="text-xs font-bold uppercase tracking-wider text-slate-900">
                    Địa chỉ và Người nhận hàng
                  </h3>
                </div>

                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label className="block text-xs font-bold uppercase text-slate-700 mb-1">
                      Họ và tên người nhận *
                    </label>
                    <input
                      type="text"
                      required
                      value={receiverName}
                      onChange={(e) => setReceiverName(e.target.value)}
                      placeholder="Ví dụ: Nguyễn Văn A"
                      className="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium focus:outline-none focus:border-[#13634B] focus:bg-white"
                    />
                  </div>

                  <div>
                    <label className="block text-xs font-bold uppercase text-slate-700 mb-1">
                      Số điện thoại nhận hàng *
                    </label>
                    <input
                      type="tel"
                      required
                      pattern="^(0)[0-9]{9}$"
                      value={receiverPhone}
                      onChange={(e) => setReceiverPhone(e.target.value)}
                      placeholder="Ví dụ: 0912345678 (10 số)"
                      className="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium focus:outline-none focus:border-[#13634B] focus:bg-white"
                    />
                  </div>
                </div>

                <div>
                  <label className="block text-xs font-bold uppercase text-slate-700 mb-1">
                    Địa chỉ giao hàng chi tiết *
                  </label>
                  <textarea
                    required
                    rows={2}
                    value={shippingAddress}
                    onChange={(e) => setShippingAddress(e.target.value)}
                    placeholder="Số nhà, ngõ/ngách, tên đường, phường/xã, quận/huyện, tỉnh/thành phố..."
                    className="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium focus:outline-none focus:border-[#13634B] focus:bg-white"
                  />
                </div>

                <div>
                  <label className="block text-xs font-bold uppercase text-slate-700 mb-1">
                    Ghi chú đơn hàng (Tùy chọn)
                  </label>
                  <input
                    type="text"
                    value={note}
                    onChange={(e) => setNote(e.target.value)}
                    placeholder="Giao hàng giờ hành chính, gọi trước khi đến..."
                    className="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium focus:outline-none focus:border-[#13634B] focus:bg-white"
                  />
                </div>
              </div>

              {/* 2. Payment Method */}
              <div className="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-4">
                <div className="flex items-center gap-2 pb-3 border-b border-slate-100">
                  <span className="w-6 h-6 rounded-lg bg-[#13634B]/10 text-[#13634B] flex items-center justify-center font-bold text-xs">2</span>
                  <h3 className="text-xs font-bold uppercase tracking-wider text-slate-900">
                    Phương thức thanh toán
                  </h3>
                </div>

                <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                  {[
                    { id: 'cod', label: 'Thanh toán COD', desc: 'Thanh toán tiền mặt khi nhận hàng', icon: 'payments' },
                    { id: 'vnpay', label: 'Cổng VNPay', desc: 'Thẻ ATM nội địa, QR Pay, Visa/Master', icon: 'qr_code_2' },
                    { id: 'momo', label: 'Ví MoMo', desc: 'Thanh toán nhanh qua ví điện tử MoMo', icon: 'account_balance_wallet' },
                    { id: 'bank_transfer', label: 'Chuyển khoản VietQR', desc: 'Quét mã VietQR 24/7 tức thì', icon: 'account_balance' },
                  ].map((method) => {
                    const isSelected = paymentMethod === method.id;
                    return (
                      <div
                        key={method.id}
                        onClick={() => setPaymentMethod(method.id as typeof paymentMethod)}
                        className={`p-4 rounded-2xl border transition-all cursor-pointer flex flex-col justify-between ${
                          isSelected
                            ? 'border-[#13634B] bg-[#13634B]/5 ring-1 ring-[#13634B]'
                            : 'border-slate-200 bg-slate-50 hover:border-slate-300'
                        }`}
                      >
                        <div className="flex items-center justify-between mb-2">
                          <span className="material-symbols-outlined text-[#13634B] text-xl">{method.icon}</span>
                          <span className={`w-4 h-4 rounded-full border-2 flex items-center justify-center ${
                            isSelected ? 'border-[#13634B]' : 'border-slate-300'
                          }`}>
                            {isSelected && <span className="w-2 h-2 rounded-full bg-[#13634B]"></span>}
                          </span>
                        </div>
                        <div className="font-bold text-xs text-slate-900">{method.label}</div>
                        <div className="text-[11px] text-slate-500 mt-0.5">{method.desc}</div>
                      </div>
                    );
                  })}
                </div>
              </div>
            </div>

            {/* Right: Order Items & Total Summary (5 cols) */}
            <div className="lg:col-span-5 space-y-4">
              <div className="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-6 sticky top-24">
                <h3 className="text-xs font-bold uppercase tracking-wider text-slate-900 pb-3 border-b border-slate-100 flex items-center justify-between">
                  <span>Kiểm tra đơn hàng ({cart.total_qty})</span>
                  <Link href="/cart" className="text-[11px] text-[#13634B] font-bold hover:underline">
                    Sửa giỏ hàng
                  </Link>
                </h3>

                {/* Item List Preview */}
                <div className="space-y-3 max-h-64 overflow-y-auto pr-1">
                  {cart.items.map((item) => (
                    <div key={item.id} className="flex items-center gap-3 py-2 border-b border-slate-100">
                      <div className="w-12 h-12 rounded-xl bg-slate-50 border border-slate-100 p-1 flex items-center justify-center shrink-0">
                        {item.image_url ? (
                          /* eslint-disable-next-line @next/next/no-img-element */
                          <img src={item.image_url} alt={item.name} className="w-full h-full object-contain" />
                        ) : (
                          <span className="material-symbols-outlined text-[#13634B] text-base">sports_tennis</span>
                        )}
                      </div>
                      <div className="min-w-0 flex-1">
                        <h4 className="text-xs font-bold text-slate-900 truncate">{item.name}</h4>
                        {item.attributes && (
                          <div className="flex flex-wrap gap-1 mt-0.5 mb-1">
                            {Array.isArray(item.attributes) ? (
                              item.attributes.map((attr, idx) => (
                                <span
                                  key={idx}
                                  className={`inline-flex items-center text-[9px] font-semibold px-1.5 py-0.2 rounded ${
                                    attr.code === 'stringing'
                                      ? 'bg-emerald-50 text-emerald-800 border border-emerald-200'
                                      : 'bg-slate-100 text-slate-600'
                                  }`}
                                >
                                  {attr.code === 'stringing' ? '🏸 ' : ''}
                                  {attr.name || attr.code}: <strong className="ml-0.5 text-slate-800">{attr.value}</strong>
                                </span>
                              ))
                            ) : (
                              Object.entries(item.attributes).map(([k, val], idx) => (
                                <span
                                  key={idx}
                                  className="inline-block bg-slate-100 text-slate-600 text-[9px] font-semibold px-1.5 py-0.2 rounded"
                                >
                                  {k}: <strong className="text-slate-800">{String(val)}</strong>
                                </span>
                              ))
                            )}
                          </div>
                        )}
                        <div className="text-[11px] text-slate-400">SL: {item.quantity} x {formatVnd(item.price)}</div>
                      </div>
                      <div className="text-xs font-black text-slate-800">
                        {formatVnd(item.subtotal)}
                      </div>
                    </div>
                  ))}
                </div>

                {/* Billing Summary */}
                <div className="space-y-2 pt-2 text-xs">
                  <div className="flex justify-between items-center text-slate-500">
                    <span>Tạm tính tiền hàng:</span>
                    <span className="font-bold text-slate-800">{formatVnd(rawSubtotal)}</span>
                  </div>
                  <div className="flex justify-between items-center text-slate-500">
                    <span>Phí giao hàng:</span>
                    <span>{shippingFee === 0 ? <strong className="text-emerald-700">MIỄN PHÍ</strong> : formatVnd(shippingFee)}</span>
                  </div>
                  <div className="flex justify-between items-center text-base font-black border-t border-slate-200 pt-3 text-slate-900">
                    <span>Tổng thanh toán:</span>
                    <span className="text-xl text-[#13634B]">{formatVnd(finalTotal)}</span>
                  </div>
                </div>

                {/* Place Order CTA */}
                <button
                  type="submit"
                  disabled={isSubmitting}
                  className="w-full py-4 rounded-xl bg-[#13634B] hover:bg-[#0e4a38] text-white font-bold text-xs uppercase tracking-wider transition shadow-md shadow-[#13634B]/20 flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50"
                >
                  <span className="material-symbols-outlined text-lg">check_circle</span>
                  {isSubmitting ? 'Đang xử lý đơn hàng...' : `Đặt hàng ngay (${formatVnd(finalTotal)})`}
                </button>
              </div>
            </div>
          </form>
        )}
      </main>

      <Footer />
    </div>
  );
}

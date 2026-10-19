"use client";

import React, { useState } from 'react';
import { useCart } from '@/context/CartContext';
import { useAuth } from '@/context/AuthContext';
import * as api from '@/services/api';

interface CartDrawerProps {
  isOpen: boolean;
  onClose: () => void;
  onOpenAuth: () => void;
  onOpenOrders?: () => void;
}

export default function CartDrawer({
  isOpen,
  onClose,
  onOpenAuth,
  onOpenOrders,
}: CartDrawerProps) {
  const { cart, updateQty, removeItem, refreshCart } = useCart();
  const { token, user } = useAuth();

  // Multi-step state: 'cart' | 'checkout'
  const [step, setStep] = useState<'cart' | 'checkout'>('cart');

  // Checkout inputs
  const [receiverName, setReceiverName] = useState(user?.name || '');
  const [receiverPhone, setReceiverPhone] = useState(user?.phone || '');
  const [shippingAddress, setShippingAddress] = useState('');
  const [paymentMethod, setPaymentMethod] = useState<'cod' | 'vnpay' | 'momo' | 'bank_transfer'>('cod');
  const [note, setNote] = useState('');

  // Discount voucher
  const [voucherCode, setVoucherCode] = useState('');
  const [discountAmount, setDiscountAmount] = useState(0);
  const [voucherMsg, setVoucherMsg] = useState<{ text: string; success: boolean } | null>(null);

  // Status & Feedback
  const [orderSuccess, setOrderSuccess] = useState<string | null>(null);
  const [error, setError] = useState<string | null>(null);
  const [isSubmitting, setIsSubmitting] = useState(false);

  if (!isOpen) return null;

  const rawSubtotal = cart?.total_price || 0;
  // Free shipping threshold: 1,500,000₫
  const freeShipThreshold = 1500000;
  const isFreeShip = rawSubtotal >= freeShipThreshold;
  const shippingFee = rawSubtotal === 0 ? 0 : (isFreeShip ? 0 : 35000);
  const finalTotal = Math.max(0, rawSubtotal - discountAmount + shippingFee);

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

  const handleCheckout = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!token) {
      onOpenAuth();
      return;
    }

    if (!receiverPhone.match(/^(0)[0-9]{9}$/)) {
      setError('Số điện thoại không hợp lệ (cần 10 chữ số, bắt đầu bằng 0).');
      return;
    }

    if (!shippingAddress.trim()) {
      setError('Vui lòng nhập địa chỉ giao hàng cụ thể.');
      return;
    }

    setError(null);
    setIsSubmitting(true);
    try {
      // Backend validates payment_method: 'cod' | 'vnpay' | 'momo'
      const sanitizedPaymentMethod = paymentMethod === 'bank_transfer' ? 'cod' : paymentMethod;
      const combinedNote = [
        paymentMethod === 'bank_transfer' ? '[Chuyển khoản VietQR]' : '',
        voucherCode ? `[Voucher: ${voucherCode}]` : '',
        note,
      ].filter(Boolean).join(' ');

      const order = await api.createOrder(token, {
        receiver_name: receiverName || user?.name || 'Khách hàng',
        receiver_phone: receiverPhone,
        shipping_address: shippingAddress,
        payment_method: sanitizedPaymentMethod,
        note: combinedNote || undefined,
      });

      setOrderSuccess(order.code);
      await refreshCart();
    } catch (err: unknown) {
      setError(err instanceof Error ? err.message : 'Đặt hàng thất bại. Vui lòng thử lại.');
    } finally {
      setIsSubmitting(false);
    }
  };

  const formatVnd = (amount: number) => {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
  };

  return (
    <div className="fixed inset-0 z-50 overflow-hidden bg-black/50 backdrop-blur-xs flex justify-end">
      <div 
        className="w-full max-w-lg bg-white text-slate-900 h-full shadow-2xl flex flex-col justify-between overflow-y-auto animate-slideInRight"
        onClick={(e) => e.stopPropagation()}
      >
        {/* Header */}
        <div className="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/80 sticky top-0 z-10 backdrop-blur-xs">
          <div className="flex items-center gap-3">
            <div className="w-10 h-10 rounded-xl bg-[#13634B]/10 text-[#13634B] flex items-center justify-center">
              <span className="material-symbols-outlined text-2xl">shopping_cart</span>
            </div>
            <div>
              <h2 className="text-lg font-black text-slate-900">
                {step === 'checkout' ? 'Xác nhận đơn hàng' : 'Giỏ hàng của bạn'}
              </h2>
              <p className="text-xs text-slate-400">
                {cart?.items.length || 0} món hàng trong giỏ
              </p>
            </div>
          </div>
          <button
            onClick={onClose}
            className="w-9 h-9 rounded-full bg-white hover:bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-800 transition cursor-pointer"
          >
            <span className="material-symbols-outlined text-xl">close</span>
          </button>
        </div>

        {/* Free Shipping Progress Meter */}
        {cart && cart.items.length > 0 && !orderSuccess && (
          <div className="bg-[#13634B]/5 px-5 py-3 border-b border-[#13634B]/10">
            <div className="flex items-center justify-between text-xs mb-1.5 font-bold">
              <span className="flex items-center gap-1.5 text-[#13634B]">
                <span className="material-symbols-outlined text-sm">local_shipping</span>
                {isFreeShip ? 'Bạn đủ điều kiện MIỄN PHÍ VẬN CHUYỂN!' : `Mua thêm ${formatVnd(freeShipThreshold - rawSubtotal)} để Freeship`}
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
        )}

        {/* Main Content Area */}
        <div className="flex-1 p-5 space-y-4 overflow-y-auto">
          {orderSuccess ? (
            /* Order Placed Success View */
            <div className="text-center py-12 px-4 space-y-5">
              <div className="w-20 h-20 bg-emerald-100 text-[#13634B] rounded-full flex items-center justify-center mx-auto shadow-inner">
                <span className="material-symbols-outlined text-4xl">check_circle</span>
              </div>
              <h3 className="text-2xl font-black text-slate-900">
                Đặt hàng thành công!
              </h3>
              <p className="text-xs text-slate-500 max-w-sm mx-auto leading-relaxed">
                Cảm ơn bạn đã tin tưởng mua sắm tại BadmintonPro. Đơn hàng của bạn đã được ghi nhận và đang được chuẩn bị.
              </p>

              <div className="p-4 bg-slate-50 rounded-2xl border border-slate-200 inline-block w-full text-left">
                <div className="text-xs text-slate-500 mb-1">Mã đơn hàng điện tử:</div>
                <div className="font-mono font-black text-lg text-[#13634B]">{orderSuccess}</div>
                <div className="text-[11px] text-slate-400 mt-2">
                  Bạn có thể dùng mã này để tra cứu trạng thái đơn hàng bất cứ lúc nào.
                </div>
              </div>

              <div className="flex flex-col gap-2 pt-2">
                {onOpenOrders && (
                  <button
                    onClick={() => {
                      setOrderSuccess(null);
                      setStep('cart');
                      onClose();
                      onOpenOrders();
                    }}
                    className="w-full py-3 rounded-xl bg-slate-900 text-white font-bold text-xs uppercase tracking-wider hover:bg-slate-800 transition shadow-xs cursor-pointer"
                  >
                    Xem lịch sử đơn hàng
                  </button>
                )}
                <button
                  onClick={() => {
                    setOrderSuccess(null);
                    setStep('cart');
                    onClose();
                  }}
                  className="w-full py-3 rounded-xl bg-[#13634B] text-white font-bold text-xs uppercase tracking-wider hover:bg-[#0e4a38] transition shadow-md shadow-[#13634B]/20 cursor-pointer"
                >
                  Tiếp tục mua sắm
                </button>
              </div>
            </div>
          ) : !cart || cart.items.length === 0 ? (
            /* Empty Cart View */
            <div className="text-center py-20 space-y-4">
              <div className="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mx-auto text-slate-400">
                <span className="material-symbols-outlined text-4xl">remove_shopping_cart</span>
              </div>
              <h3 className="text-base font-bold text-slate-800">
                Giỏ hàng của bạn đang trống
              </h3>
              <p className="text-xs text-slate-400 max-w-xs mx-auto">
                Chưa có sản phẩm nào. Khám phá các dòng vợt, giày và phụ kiện thi đấu mới nhất nhé!
              </p>
              <button
                onClick={onClose}
                className="px-6 py-2.5 rounded-xl bg-[#13634B] text-white font-bold text-xs uppercase tracking-wider hover:bg-[#0e4a38] transition shadow-md shadow-[#13634B]/20 cursor-pointer"
              >
                Khám phá ngay
              </button>
            </div>
          ) : step === 'checkout' ? (
            /* Step 2: Checkout Form */
            <form onSubmit={handleCheckout} className="space-y-4">
              <div className="flex items-center justify-between pb-2 border-b border-slate-100">
                <span className="text-xs font-bold uppercase tracking-wider text-[#13634B]">
                  Thông tin giao hàng & Thanh toán
                </span>
                <button
                  type="button"
                  onClick={() => setStep('cart')}
                  className="text-xs text-slate-500 hover:text-[#13634B] font-bold flex items-center gap-1 cursor-pointer"
                >
                  <span className="material-symbols-outlined text-sm">arrow_back</span>
                  Sửa giỏ hàng
                </button>
              </div>

              {error && (
                <div className="p-3 bg-rose-50 border border-rose-200 text-rose-600 text-xs rounded-xl font-bold flex items-center gap-2">
                  <span className="material-symbols-outlined text-sm">error</span>
                  {error}
                </div>
              )}

              <div>
                <label className="block text-xs font-bold text-slate-700 uppercase mb-1">
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
                <label className="block text-xs font-bold text-slate-700 uppercase mb-1">
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

              <div>
                <label className="block text-xs font-bold text-slate-700 uppercase mb-1">
                  Địa chỉ nhận hàng chi tiết *
                </label>
                <textarea
                  required
                  rows={2}
                  value={shippingAddress}
                  onChange={(e) => setShippingAddress(e.target.value)}
                  placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố..."
                  className="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium focus:outline-none focus:border-[#13634B] focus:bg-white"
                />
              </div>

              {/* Payment Methods Grid */}
              <div>
                <label className="block text-xs font-bold text-slate-700 uppercase mb-2">
                  Hình thức thanh toán:
                </label>
                <div className="grid grid-cols-2 gap-2">
                  {[
                    { id: 'cod', label: 'Thanh toán COD', desc: 'Nhận hàng trả tiền mặt', icon: 'payments' },
                    { id: 'vnpay', label: 'Cổng VNPay', desc: 'Thẻ ATM & Quét QR Pay', icon: 'qr_code_2' },
                    { id: 'momo', label: 'Ví MoMo', desc: 'Thanh toán ví MoMo', icon: 'account_balance_wallet' },
                    { id: 'bank_transfer', label: 'Chuyển khoản VietQR', desc: 'Chuyển khoản 24/7', icon: 'account_balance' },
                  ].map((method) => {
                    const isSelected = paymentMethod === method.id;
                    return (
                      <button
                        key={method.id}
                        type="button"
                        onClick={() => setPaymentMethod(method.id as typeof paymentMethod)}
                        className={`p-3 rounded-xl border text-left flex flex-col justify-between transition-all cursor-pointer ${
                          isSelected
                            ? 'border-[#13634B] bg-[#13634B]/5 ring-1 ring-[#13634B]'
                            : 'border-slate-200 bg-slate-50 hover:border-slate-300'
                        }`}
                      >
                        <div className="flex items-center justify-between mb-1">
                          <span className="material-symbols-outlined text-[#13634B] text-lg">{method.icon}</span>
                          <span className={`w-3.5 h-3.5 rounded-full border-2 flex items-center justify-center ${
                            isSelected ? 'border-[#13634B]' : 'border-slate-300'
                          }`}>
                            {isSelected && <span className="w-1.5 h-1.5 rounded-full bg-[#13634B]"></span>}
                          </span>
                        </div>
                        <div className="font-bold text-xs text-slate-900">{method.label}</div>
                        <div className="text-[10px] text-slate-500">{method.desc}</div>
                      </button>
                    );
                  })}
                </div>
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-700 uppercase mb-1">
                  Ghi chú đơn hàng (Tùy chọn)
                </label>
                <input
                  type="text"
                  value={note}
                  onChange={(e) => setNote(e.target.value)}
                  placeholder="Ví dụ: Giao giờ hành chính, gọi trước khi tới..."
                  className="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium focus:outline-none focus:border-[#13634B]"
                />
              </div>

              {/* Action Buttons */}
              <div className="flex gap-2 pt-2">
                <button
                  type="button"
                  onClick={() => setStep('cart')}
                  className="w-1/3 py-3 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer"
                >
                  Quay lại
                </button>
                <button
                  type="submit"
                  disabled={isSubmitting}
                  className="w-2/3 py-3 rounded-xl bg-[#13634B] text-white font-bold text-xs uppercase tracking-wider hover:bg-[#0e4a38] transition shadow-md shadow-[#13634B]/20 disabled:opacity-50 flex items-center justify-center gap-2 cursor-pointer"
                >
                  <span className="material-symbols-outlined text-sm">lock</span>
                  {isSubmitting ? 'Đang gửi đơn...' : `Thanh toán ${formatVnd(finalTotal)}`}
                </button>
              </div>
            </form>
          ) : (
            /* Step 1: Cart Items List */
            <div className="space-y-4">
              {cart.items.map((item) => (
                <div
                  key={item.id}
                  className="flex items-center gap-3 p-3.5 rounded-2xl border border-slate-200/80 bg-white hover:border-slate-300 transition"
                >
                  <div className="w-16 h-16 rounded-xl bg-slate-50 border border-slate-100 p-1 flex items-center justify-center shrink-0 overflow-hidden">
                    {item.image_url ? (
                      /* eslint-disable-next-line @next/next/no-img-element */
                      <img src={item.image_url} alt={item.name} className="w-full h-full object-contain" />
                    ) : (
                      <span className="material-symbols-outlined text-[#13634B] text-2xl">sports_tennis</span>
                    )}
                  </div>

                  <div className="flex-1 min-w-0">
                    <h4 className="text-xs font-bold text-slate-900 truncate" title={item.name}>
                      {item.name}
                    </h4>
                    <div className="text-[11px] text-slate-400 font-mono mt-0.5">
                      {item.sku}
                    </div>
                    <div className="text-xs font-black text-[#13634B] mt-1">
                      {formatVnd(item.price)}
                    </div>
                  </div>

                  {/* Quantity Stepper */}
                  <div className="flex items-center gap-1.5">
                    <button
                      type="button"
                      onClick={() => updateQty(item.id, Math.max(1, item.quantity - 1))}
                      className="w-7 h-7 flex items-center justify-center rounded-lg bg-slate-50 border border-slate-200 text-xs font-bold hover:border-[#13634B] transition cursor-pointer"
                    >
                      -
                    </button>
                    <span className="text-xs font-bold px-1.5 min-w-[20px] text-center">
                      {item.quantity}
                    </span>
                    <button
                      type="button"
                      onClick={() => updateQty(item.id, item.quantity + 1)}
                      className="w-7 h-7 flex items-center justify-center rounded-lg bg-slate-50 border border-slate-200 text-xs font-bold hover:border-[#13634B] transition cursor-pointer"
                    >
                      +
                    </button>
                    <button
                      type="button"
                      onClick={() => removeItem(item.id)}
                      className="ml-2 w-7 h-7 flex items-center justify-center text-slate-400 hover:text-rose-600 transition cursor-pointer"
                      title="Xóa món"
                    >
                      <span className="material-symbols-outlined text-base">delete</span>
                    </button>
                  </div>
                </div>
              ))}

              {/* Coupon Voucher Form */}
              <form onSubmit={handleApplyVoucher} className="pt-2">
                <div className="flex gap-2">
                  <input
                    type="text"
                    value={voucherCode}
                    onChange={(e) => setVoucherCode(e.target.value)}
                    placeholder="Nhập mã ưu đãi (Thử: BADMINTON10)"
                    className="flex-1 px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold uppercase focus:outline-none focus:border-[#13634B] focus:bg-white"
                  />
                  <button
                    type="submit"
                    className="px-4 py-2 rounded-xl bg-[#13634B] text-white text-xs font-bold hover:bg-[#0e4a38] transition cursor-pointer shadow-xs"
                  >
                    Áp dụng
                  </button>
                </div>
                {voucherMsg && (
                  <p className={`text-[11px] mt-1.5 font-bold ${voucherMsg.success ? 'text-emerald-700' : 'text-rose-600'}`}>
                    {voucherMsg.text}
                  </p>
                )}
              </form>
            </div>
          )}
        </div>

        {/* Footer Billing Breakdown & Primary CTA */}
        {cart && cart.items.length > 0 && !orderSuccess && step === 'cart' && (
          <div className="p-5 border-t border-slate-100 bg-slate-50/80 space-y-3">
            <div className="space-y-1.5 text-xs">
              <div className="flex justify-between items-center text-slate-500">
                <span>Tạm tính ({cart.total_qty} sản phẩm):</span>
                <span className="font-bold text-slate-800">{formatVnd(rawSubtotal)}</span>
              </div>
              {discountAmount > 0 && (
                <div className="flex justify-between items-center text-emerald-700 font-bold">
                  <span>Giảm giá Voucher:</span>
                  <span>-{formatVnd(discountAmount)}</span>
                </div>
              )}
              <div className="flex justify-between items-center text-slate-500">
                <span>Phí vận chuyển:</span>
                <span>{shippingFee === 0 ? <strong className="text-emerald-700">MIỄN PHÍ</strong> : formatVnd(shippingFee)}</span>
              </div>
              <div className="flex justify-between items-center text-sm font-black border-t border-slate-200 pt-2 text-slate-900">
                <span>Tổng cộng:</span>
                <span className="text-[#13634B] text-base">{formatVnd(finalTotal)}</span>
              </div>
            </div>

            <button
              onClick={() => {
                if (!token) {
                  onOpenAuth();
                } else {
                  setStep('checkout');
                }
              }}
              className="w-full py-3.5 rounded-xl bg-[#13634B] text-white font-bold text-xs uppercase tracking-wider hover:bg-[#0e4a38] transition shadow-md shadow-[#13634B]/20 flex items-center justify-center gap-2 cursor-pointer"
            >
              <span className="material-symbols-outlined text-base">check_circle</span>
              {token ? 'Tiến hành đặt hàng' : 'Đăng nhập để đặt hàng'}
            </button>
          </div>
        )}
      </div>
    </div>
  );
}

"use client";

import React, { Suspense } from 'react';
import Link from 'next/link';
import { useSearchParams } from 'next/navigation';
import Header from '@/components/Header';
import Footer from '@/components/Footer';

function OrderSuccessContent() {
  const searchParams = useSearchParams();
  const code = searchParams.get('code') || 'ORD-UNKNOWN';
  const paymentStatus = searchParams.get('payment');
  const msg = searchParams.get('msg');

  const isVnpaySuccess = paymentStatus === 'vnpay_success';
  const isVnpayFailed = paymentStatus === 'vnpay_failed';

  return (
    <div className="flex flex-col min-h-screen bg-[#f8fafc] text-slate-900">
      <Header />

      <main className="flex-grow max-w-2xl w-full mx-auto px-4 py-16 flex items-center justify-center">
        <div className="bg-white rounded-3xl border border-slate-200/90 p-8 sm:p-12 text-center space-y-6 shadow-xs w-full">
          {isVnpayFailed ? (
            <div className="w-20 h-20 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center mx-auto shadow-inner">
              <span className="material-symbols-outlined text-4xl">warning</span>
            </div>
          ) : (
            <div className="w-20 h-20 rounded-full bg-emerald-100 text-[#13634B] flex items-center justify-center mx-auto shadow-inner">
              <span className="material-symbols-outlined text-4xl">check_circle</span>
            </div>
          )}

          <div>
            <h1 className="text-2xl sm:text-3xl font-black uppercase text-slate-900 tracking-tight">
              {isVnpayFailed ? 'ĐƠN HÀNG ĐANG CHỜ THANH TOÁN' : 'ĐẶT HÀNG THÀNH CÔNG!'}
            </h1>
            {isVnpaySuccess && (
              <div className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 text-xs font-bold mt-2 border border-emerald-200">
                <span className="material-symbols-outlined text-sm">verified</span>
                <span>Thanh toán VNPay thành công</span>
              </div>
            )}
            {isVnpayFailed && (
              <div className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 text-amber-800 text-xs font-bold mt-2 border border-amber-200">
                <span className="material-symbols-outlined text-sm">info</span>
                <span>{msg ? decodeURIComponent(msg) : 'Giao dịch VNPay chưa hoàn tất'}</span>
              </div>
            )}
          </div>

          <p className="text-xs text-slate-500 max-w-md mx-auto leading-relaxed">
            {isVnpayFailed
              ? 'Hệ thống đã lưu đơn hàng của bạn. Bạn có thể thanh toán lại qua VNPay hoặc chuyển sang hình thức thanh toán khi nhận hàng (COD).'
              : 'Cảm ơn bạn đã lựa chọn DSPORT NEXUS. Hệ thống đã ghi nhận đơn hàng và đang chuẩn bị kiện hàng với tiêu chuẩn đan vợt và đóng gói an toàn nhất.'}
          </p>

          {/* Order Code Box */}
          <div className="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 inline-block w-full text-left space-y-1">
            <div className="text-xs text-slate-400 font-semibold uppercase">Mã vận đơn điện tử</div>
            <div className="font-mono text-xl font-black text-[#13634B]">{code}</div>
            <div className="text-[11px] text-slate-500 pt-1">
              Bạn có thể dùng mã này để tra cứu trạng thái đơn hàng bất cứ lúc nào.
            </div>
          </div>

          {/* Action CTAs */}
          <div className="flex flex-col sm:flex-row gap-3 pt-4">
            {isVnpayFailed ? (
              <Link
                href={`/orders/payment/vnpay/simulate?code=${code}`}
                className="flex-1 py-3.5 rounded-xl bg-[#005baa] text-white font-bold text-xs uppercase tracking-wider hover:bg-[#004885] transition shadow-md shadow-blue-900/20 flex items-center justify-center gap-1.5"
              >
                <span className="material-symbols-outlined text-base">credit_card</span>
                Thanh toán lại qua VNPay
              </Link>
            ) : (
              <Link
                href={`/orders/${code}`}
                className="flex-1 py-3.5 rounded-xl bg-[#13634B] text-white font-bold text-xs uppercase tracking-wider hover:bg-[#0e4a38] transition shadow-md shadow-[#13634B]/20 flex items-center justify-center gap-1.5"
              >
                <span className="material-symbols-outlined text-base">local_shipping</span>
                Theo dõi đơn hàng này
              </Link>
            )}

            <Link
              href="/orders"
              className="flex-1 py-3.5 rounded-xl border border-slate-200 bg-white text-slate-700 font-bold text-xs uppercase tracking-wider hover:border-[#13634B] hover:text-[#13634B] transition flex items-center justify-center gap-1.5"
            >
              <span className="material-symbols-outlined text-base">receipt_long</span>
              Lịch sử đơn hàng
            </Link>
          </div>

          <div>
            <Link href="/products" className="text-xs text-slate-400 hover:text-[#13634B] font-bold">
              ← Tiếp tục mua sắm các sản phẩm khác
            </Link>
          </div>
        </div>
      </main>

      <Footer />
    </div>
  );
}

export default function OrderSuccessPage() {
  return (
    <Suspense fallback={
      <div className="min-h-screen flex items-center justify-center bg-[#f8fafc]">
        <div className="w-10 h-10 border-4 border-[#13634B] border-t-transparent rounded-full animate-spin"></div>
      </div>
    }>
      <OrderSuccessContent />
    </Suspense>
  );
}

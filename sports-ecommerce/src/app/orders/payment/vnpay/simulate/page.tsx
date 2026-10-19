"use client";

import React, { useEffect, useState, Suspense } from 'react';
import { useRouter, useSearchParams } from 'next/navigation';
import Header from '@/components/Header';
import Footer from '@/components/Footer';
import Breadcrumb from '@/components/Breadcrumb';
import { useAuth } from '@/context/AuthContext';
import * as api from '@/services/api';
import { ApiOrder } from '@/types/api';

function VnpaySimulateContent() {
  const router = useRouter();
  const searchParams = useSearchParams();
  const orderCode = searchParams.get('code') || '';
  const { token } = useAuth();

  const [order, setOrder] = useState<ApiOrder | null>(null);
  const [isLoading, setIsLoading] = useState(true);
  const [isProcessing, setIsProcessing] = useState(false);
  const [selectedBank, setSelectedBank] = useState('NCB');

  useEffect(() => {
    if (!orderCode) {
      setIsLoading(false);
      return;
    }

    if (token) {
      api.getOrderDetail(orderCode, token)
        .then((data) => setOrder(data))
        .catch((err) => console.error("Failed loading order for VNPay:", err))
        .finally(() => setIsLoading(false));
    } else {
      setIsLoading(false);
    }
  }, [orderCode, token]);

  const formatVnd = (amount: number) => {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
  };

  const handleSimulatePayment = (responseCode: string) => {
    if (!orderCode) return;
    setIsProcessing(true);

    const amount = (order?.financial?.total || 100000) * 100;
    const callbackUrl = `http://localhost:8088/api/v1/payment/vnpay/callback?vnp_ResponseCode=${responseCode}&vnp_TxnRef=${encodeURIComponent(orderCode)}&vnp_Amount=${amount}&vnp_TransactionNo=${Date.now()}&vnp_BankCode=${selectedBank}&vnp_Simulate=1`;

    window.location.href = callbackUrl;
  };

  const banks = [
    { code: 'NCB', name: 'Ngân hàng Quốc Dân (NCB Test)', icon: 'account_balance' },
    { code: 'VIETCOMBANK', name: 'Vietcombank', icon: 'account_balance' },
    { code: 'TECHCOMBANK', name: 'Techcombank', icon: 'account_balance' },
    { code: 'MBBANK', name: 'MB Quân Đội', icon: 'account_balance' },
    { code: 'AGRIBANK', name: 'Agribank', icon: 'account_balance' },
  ];

  return (
    <div className="flex flex-col min-h-screen bg-[#f1f5f9] text-slate-900">
      <Header />

      <main className="flex-grow max-w-[1000px] w-full mx-auto px-4 py-8">
        <Breadcrumb
          items={[
            { label: 'Thanh toán', href: '/checkout' },
            { label: 'Cổng thanh toán VNPay Sandbox' },
          ]}
        />

        {/* VNPay Official Branded Header */}
        <div className="bg-gradient-to-r from-[#005baa] to-[#0072ce] text-white rounded-3xl p-6 sm:p-8 shadow-lg shadow-blue-900/10 mb-6 flex flex-col sm:flex-row items-center justify-between gap-6">
          <div className="flex items-center gap-4 text-center sm:text-left">
            <div className="w-14 h-14 rounded-2xl bg-white text-[#005baa] flex items-center justify-center font-black text-2xl shadow-md tracking-tighter">
              VNPAY
            </div>
            <div>
              <div className="flex items-center gap-2 justify-center sm:justify-start">
                <span className="text-xs font-bold uppercase tracking-widest text-blue-200">
                  CỔNG THANH TOÁN ĐIỆN TỬ
                </span>
                <span className="bg-amber-400 text-slate-900 text-[10px] font-black px-2 py-0.5 rounded-full uppercase">
                  Sandbox Testing
                </span>
              </div>
              <h1 className="text-xl sm:text-2xl font-black mt-0.5">
                Xác Nhận Giao Dịch Trực Tuyến
              </h1>
              <p className="text-xs text-blue-100 font-medium">
                Môi trường kiểm thử an toàn kết nối hệ thống DSPORT NEXUS
              </p>
            </div>
          </div>

          <div className="bg-white/10 backdrop-blur-xs border border-white/20 rounded-2xl p-4 text-center sm:text-right min-w-[200px]">
            <span className="text-[11px] text-blue-100 block uppercase font-bold tracking-wider">
              Số tiền cần thanh toán
            </span>
            <span className="text-2xl font-black text-white">
              {order ? formatVnd(order.financial?.total || 0) : '---'}
            </span>
          </div>
        </div>

        {isLoading ? (
          <div className="bg-white rounded-3xl border border-slate-200/90 p-12 text-center shadow-xs">
            <div className="inline-block animate-spin rounded-full h-9 w-9 border-3 border-[#005baa] border-t-transparent mb-3"></div>
            <p className="text-xs font-bold text-slate-500 uppercase tracking-wider">
              Đang tải dữ liệu đơn hàng VNPay...
            </p>
          </div>
        ) : !orderCode ? (
          <div className="bg-white rounded-3xl border border-slate-200/90 p-12 text-center space-y-4 shadow-xs">
            <span className="material-symbols-outlined text-5xl text-rose-500">error</span>
            <h2 className="text-lg font-bold text-slate-800">Không tìm thấy mã đơn hàng cần thanh toán</h2>
            <button
              onClick={() => router.push('/orders')}
              className="px-6 py-2.5 rounded-xl bg-[#13634B] text-white text-xs font-bold hover:bg-[#0e4a38]"
            >
              Xem danh sách đơn hàng
            </button>
          </div>
        ) : (
          <div className="grid grid-cols-1 md:grid-cols-12 gap-6">
            {/* Left Column: Bank Selection & Sandbox Simulation Actions (8 cols) */}
            <div className="md:col-span-8 space-y-6">
              {/* Payment Methods */}
              <div className="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-7 shadow-xs space-y-4">
                <div className="flex items-center justify-between pb-3 border-b border-slate-100">
                  <h3 className="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-2">
                    <span className="material-symbols-outlined text-base text-[#005baa]">account_balance</span>
                    Chọn Ngân Hàng Thanh Toán Thử Nghiệm
                  </h3>
                  <span className="text-[11px] text-slate-400">Sandbox Test Mode</span>
                </div>

                <div className="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                  {banks.map((bank) => (
                    <button
                      key={bank.code}
                      type="button"
                      onClick={() => setSelectedBank(bank.code)}
                      className={`p-3.5 rounded-2xl border text-left flex items-center gap-3 transition cursor-pointer ${
                        selectedBank === bank.code
                          ? 'border-[#005baa] bg-blue-50/60 ring-2 ring-[#005baa]/20'
                          : 'border-slate-200 hover:border-slate-300 bg-white'
                      }`}
                    >
                      <div className={`w-8 h-8 rounded-xl flex items-center justify-center ${
                        selectedBank === bank.code ? 'bg-[#005baa] text-white' : 'bg-slate-100 text-slate-600'
                      }`}>
                        <span className="material-symbols-outlined text-base">{bank.icon}</span>
                      </div>
                      <div>
                        <div className="text-xs font-bold text-slate-900">{bank.name}</div>
                        <div className="text-[10px] text-slate-400 font-mono">{bank.code}</div>
                      </div>
                    </button>
                  ))}
                </div>

                <div className="p-4 rounded-2xl bg-amber-50/80 border border-amber-200/80 text-amber-900 text-xs leading-relaxed space-y-1">
                  <div className="font-bold flex items-center gap-1.5 text-amber-800">
                    <span className="material-symbols-outlined text-base">info</span>
                    Thông báo môi trường thử nghiệm
                  </div>
                  <p className="text-[11px] text-amber-800/90">
                    Bạn đang ở trình giả lập cổng thanh toán VNPay Sandbox. Bấm nút <strong>&quot;Thanh toán thành công&quot;</strong> bên dưới để hoàn tất giao dịch tự động và cập nhật trạng thái đơn hàng sang <strong>ĐÃ THANH TOÁN (PAID)</strong>.
                  </p>
                </div>

                {/* Simulation Buttons */}
                <div className="pt-2 space-y-3">
                  <button
                    type="button"
                    disabled={isProcessing}
                    onClick={() => handleSimulatePayment('00')}
                    className="w-full py-3.5 px-4 rounded-2xl bg-gradient-to-r from-emerald-600 to-[#13634B] hover:from-emerald-700 hover:to-[#0e4a38] text-white font-bold text-xs uppercase tracking-wider transition shadow-md shadow-emerald-700/20 flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50"
                  >
                    <span className="material-symbols-outlined text-lg">check_circle</span>
                    <span>{isProcessing ? 'Đang gửi phản hồi VNPay...' : 'Xác nhận Thanh toán Thành công (Test Mã 00)'}</span>
                  </button>

                  <button
                    type="button"
                    disabled={isProcessing}
                    onClick={() => handleSimulatePayment('24')}
                    className="w-full py-3 px-4 rounded-2xl border border-slate-200 hover:bg-slate-50 text-slate-600 font-bold text-xs uppercase tracking-wider transition flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50"
                  >
                    <span className="material-symbols-outlined text-base text-rose-500">cancel</span>
                    <span>Hủy giao dịch / Thử nghiệm thất bại (Test Mã 24)</span>
                  </button>
                </div>
              </div>
            </div>

            {/* Right Column: Order Details Summary (4 cols) */}
            <div className="md:col-span-4 space-y-4">
              <div className="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs space-y-4">
                <h3 className="text-xs font-bold uppercase tracking-wider text-slate-700 pb-3 border-b border-slate-100 flex items-center gap-2">
                  <span className="material-symbols-outlined text-base text-[#13634B]">receipt_long</span>
                  Chi Tiết Đơn Hàng
                </h3>

                <div className="space-y-3 text-xs">
                  <div>
                    <span className="text-slate-400 block text-[11px]">Mã đơn hàng:</span>
                    <span className="font-mono font-bold text-slate-900">{orderCode}</span>
                  </div>

                  {order && (
                    <>
                      <div>
                        <span className="text-slate-400 block text-[11px]">Người nhận:</span>
                        <span className="font-bold text-slate-800">{order.receiver_info?.name} ({order.receiver_info?.phone})</span>
                      </div>

                      <div>
                        <span className="text-slate-400 block text-[11px]">Địa chỉ giao hàng:</span>
                        <span className="text-slate-700 line-clamp-2">{order.receiver_info?.address}</span>
                      </div>

                      <div className="pt-2 border-t border-slate-100">
                        <div className="flex justify-between text-slate-500 mb-1">
                          <span>Tạm tính:</span>
                          <span>{formatVnd(order.financial?.subtotal || 0)}</span>
                        </div>
                        <div className="flex justify-between text-slate-500 mb-1">
                          <span>Phí vận chuyển:</span>
                          <span>{formatVnd(order.financial?.shipping_fee || 0)}</span>
                        </div>
                        <div className="flex justify-between font-black text-slate-900 pt-2 border-t border-slate-100 text-sm">
                          <span>Tổng thanh toán:</span>
                          <span className="text-[#005baa]">{formatVnd(order.financial?.total || 0)}</span>
                        </div>
                      </div>
                    </>
                  )}
                </div>

                <div className="pt-2">
                  <button
                    type="button"
                    onClick={() => router.push(`/orders/${orderCode}`)}
                    className="w-full py-2.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-bold hover:bg-slate-50 transition text-center"
                  >
                    Xem chi tiết đơn hàng
                  </button>
                </div>
              </div>
            </div>
          </div>
        )}
      </main>

      <Footer />
    </div>
  );
}

export default function VnpaySimulatePage() {
  return (
    <Suspense fallback={
      <div className="min-h-screen flex items-center justify-center bg-[#f8fafc]">
        <div className="animate-spin rounded-full h-9 w-9 border-3 border-[#005baa] border-t-transparent"></div>
      </div>
    }>
      <VnpaySimulateContent />
    </Suspense>
  );
}

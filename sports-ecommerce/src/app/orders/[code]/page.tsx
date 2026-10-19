"use client";

import React, { useEffect, useState, use } from 'react';
import Link from 'next/link';
import Header from '@/components/Header';
import Footer from '@/components/Footer';
import Breadcrumb from '@/components/Breadcrumb';
import { useAuth } from '@/context/AuthContext';
import * as api from '@/services/api';
import { ApiOrder } from '@/types/api';

interface OrderDetailPageProps {
  params: Promise<{ code: string }>;
}

export default function OrderDetailPage({ params }: OrderDetailPageProps) {
  const { code } = use(params);
  const { token } = useAuth();

  const [order, setOrder] = useState<ApiOrder | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [cancelling, setCancelling] = useState(false);

  const fetchOrderDetail = React.useCallback(async () => {
    if (!token) return;
    setLoading(true);
    setError(null);
    try {
      const data = await api.getOrderDetail(code, token);
      setOrder(data);
    } catch (err: unknown) {
      setError(err instanceof Error ? err.message : 'Không tìm thấy chi tiết đơn hàng');
    } finally {
      setLoading(false);
    }
  }, [code, token]);

  useEffect(() => {
    fetchOrderDetail();
  }, [fetchOrderDetail]);

  const handleCancelOrder = async () => {
    if (!token || !order) return;
    const confirmCancel = window.confirm(`Bạn có chắc chắn muốn hủy đơn hàng #${order.code}?`);
    if (!confirmCancel) return;

    setCancelling(true);
    try {
      await api.cancelOrder(order.code, token);
      await fetchOrderDetail();
    } catch (err: unknown) {
      alert(err instanceof Error ? err.message : 'Hủy đơn hàng thất bại');
    } finally {
      setCancelling(false);
    }
  };

  const formatVnd = (amount: number) => {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
  };

  const isPending = order?.status?.key?.toLowerCase() === 'pending' || order?.status?.key?.toLowerCase() === 'cho_xac_nhan';
  const isCancelled = order?.status?.key?.toLowerCase() === 'cancelled' || order?.status?.key?.toLowerCase() === 'da_huy';

  return (
    <div className="flex flex-col min-h-screen bg-[#f8fafc] text-slate-900">
      <Header />

      <main className="flex-grow max-w-[1200px] w-full mx-auto px-4 lg:px-8 py-6">
        <Breadcrumb
          items={[
            { label: 'Lịch sử đơn hàng', href: '/orders' },
            { label: `Đơn hàng #${code}` },
          ]}
        />

        {!token ? (
          <div className="max-w-md mx-auto bg-white rounded-3xl border border-slate-200/90 p-12 text-center space-y-4 my-8 shadow-xs">
            <span className="material-symbols-outlined text-5xl text-slate-300">lock</span>
            <h2 className="text-base font-bold text-slate-800">Vui lòng đăng nhập để xem đơn hàng</h2>
            <Link
              href={`/login?returnUrl=/orders/${code}`}
              className="inline-block px-6 py-2.5 rounded-xl bg-[#13634B] text-white text-xs font-bold hover:bg-[#0e4a38]"
            >
              Đăng nhập ngay
            </Link>
          </div>
        ) : loading ? (
          <div className="bg-white rounded-3xl border border-slate-200/90 p-16 text-center space-y-4 my-8">
            <div className="w-10 h-10 border-4 border-[#13634B] border-t-transparent rounded-full animate-spin mx-auto"></div>
            <p className="text-xs font-bold text-slate-500">Đang tải thông tin đơn hàng #{code}...</p>
          </div>
        ) : error || !order ? (
          <div className="bg-white rounded-3xl border border-slate-200/90 p-16 text-center space-y-4 my-8">
            <span className="material-symbols-outlined text-5xl text-rose-500">error</span>
            <h2 className="text-base font-bold text-slate-800">{error || 'Không tìm thấy đơn hàng'}</h2>
            <Link
              href="/orders"
              className="inline-block px-6 py-2.5 rounded-xl bg-[#13634B] text-white text-xs font-bold hover:bg-[#0e4a38]"
            >
              Xem danh sách đơn hàng
            </Link>
          </div>
        ) : (
          <div className="space-y-6 my-6">
            {/* Header Card */}
            <div className="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
              <div>
                <div className="flex items-center gap-3">
                  <h1 className="text-xl sm:text-2xl font-black text-slate-900 font-mono">
                    #{order.code}
                  </h1>
                  <span className={`px-3 py-1 rounded-full text-xs font-bold ${
                    isCancelled
                      ? 'bg-rose-50 text-rose-700 border border-rose-200'
                      : isPending
                      ? 'bg-amber-50 text-amber-700 border border-amber-200'
                      : 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                  }`}>
                    {order.status?.label || 'Đang cập nhật'}
                  </span>
                </div>
                <p className="text-xs text-slate-500 mt-1">
                  Thời gian đặt hàng: {new Date(order.created_at).toLocaleString('vi-VN')}
                </p>
              </div>

              {isPending && (
                <button
                  onClick={handleCancelOrder}
                  disabled={cancelling}
                  className="px-5 py-2.5 rounded-xl border border-rose-300 text-rose-600 hover:bg-rose-50 text-xs font-bold transition disabled:opacity-50 cursor-pointer"
                >
                  {cancelling ? 'Đang hủy...' : 'Hủy đơn hàng này'}
                </button>
              )}
            </div>

            {/* Tracking Status Stepper */}
            {!isCancelled && (
              <div className="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs">
                <h3 className="text-xs font-bold uppercase tracking-wider text-slate-700 mb-6">
                  Tiến độ vận chuyển đơn hàng
                </h3>
                <div className="grid grid-cols-4 gap-2 text-center text-xs">
                  {[
                    { step: 1, label: 'Đã đặt hàng', active: true, icon: 'receipt' },
                    { step: 2, label: 'Đang xử lý', active: !isPending, icon: 'inventory' },
                    { step: 3, label: 'Đang giao hàng', active: order.status?.key?.toLowerCase() === 'shipping' || order.status?.key?.toLowerCase() === 'completed', icon: 'local_shipping' },
                    { step: 4, label: 'Giao thành công', active: order.status?.key?.toLowerCase() === 'completed', icon: 'task_alt' },
                  ].map((s) => (
                    <div key={s.step} className="flex flex-col items-center gap-2">
                      <div className={`w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm ${
                        s.active
                          ? 'bg-[#13634B] text-white shadow-xs'
                          : 'bg-slate-100 text-slate-400'
                      }`}>
                        <span className="material-symbols-outlined text-base">{s.icon}</span>
                      </div>
                      <span className={`text-[11px] font-bold ${s.active ? 'text-[#13634B]' : 'text-slate-400'}`}>
                        {s.label}
                      </span>
                    </div>
                  ))}
                </div>
              </div>
            )}

            {/* Two-column Order Details */}
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              {/* Receiver & Delivery Information */}
              <div className="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs space-y-3">
                <h3 className="text-xs font-bold uppercase tracking-wider text-slate-900 pb-2 border-b border-slate-100 flex items-center gap-1.5">
                  <span className="material-symbols-outlined text-[#13634B] text-base">location_on</span>
                  Thông tin người nhận
                </h3>
                <div className="text-xs space-y-1.5">
                  <div className="text-slate-900 font-bold">{order.receiver_info?.name}</div>
                  <div className="text-slate-600 font-medium">Số điện thoại: {order.receiver_info?.phone}</div>
                  <div className="text-slate-500 leading-relaxed">Địa chỉ: {order.receiver_info?.address}</div>
                  {order.note && (
                    <div className="p-2.5 rounded-xl bg-slate-50 text-[11px] text-slate-600 border border-slate-100 mt-2">
                      <strong>Ghi chú:</strong> {order.note}
                    </div>
                  )}
                </div>
              </div>

              {/* Payment Details */}
              <div className="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs space-y-3">
                <h3 className="text-xs font-bold uppercase tracking-wider text-slate-900 pb-2 border-b border-slate-100 flex items-center gap-1.5">
                  <span className="material-symbols-outlined text-[#13634B] text-base">payment</span>
                  Phương thức & Thanh toán
                </h3>
                <div className="text-xs space-y-2">
                  <div className="flex justify-between">
                    <span className="text-slate-500">Hình thức:</span>
                    <span className="font-bold text-slate-900 uppercase">{order.payment?.method}</span>
                  </div>
                  <div className="flex justify-between">
                    <span className="text-slate-500">Trạng thái thanh toán:</span>
                    <span className="font-bold text-[#13634B] uppercase">{order.payment?.status}</span>
                  </div>
                  <div className="flex justify-between border-t border-slate-100 pt-2">
                    <span className="text-slate-500">Tạm tính hàng:</span>
                    <span className="font-bold">{formatVnd(order.financial?.subtotal || 0)}</span>
                  </div>
                  <div className="flex justify-between">
                    <span className="text-slate-500">Phí giao hàng:</span>
                    <span className="font-bold">{formatVnd(order.financial?.shipping_fee || 0)}</span>
                  </div>
                  <div className="flex justify-between border-t border-slate-200 pt-2 text-sm font-black">
                    <span className="text-slate-900">Tổng thanh toán:</span>
                    <span className="text-[#13634B]">{formatVnd(order.financial?.total || 0)}</span>
                  </div>
                </div>
              </div>
            </div>

            {/* Back to Orders Link */}
            <div className="text-center pt-4">
              <Link
                href="/orders"
                className="inline-flex items-center gap-1 text-xs font-bold text-[#13634B] hover:underline"
              >
                <span className="material-symbols-outlined text-sm">arrow_back</span>
                Quay lại danh sách tất cả đơn hàng
              </Link>
            </div>
          </div>
        )}
      </main>

      <Footer />
    </div>
  );
}

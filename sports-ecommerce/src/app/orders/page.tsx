"use client";

import React, { useEffect, useState, useCallback } from 'react';
import Link from 'next/link';
import Header from '@/components/Header';
import Footer from '@/components/Footer';
import Breadcrumb from '@/components/Breadcrumb';
import { useAuth } from '@/context/AuthContext';
import * as api from '@/services/api';
import { ApiOrder } from '@/types/api';

export default function OrdersPage() {
  const { token } = useAuth();

  const [orders, setOrders] = useState<ApiOrder[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [filterTab, setFilterTab] = useState<'all' | 'pending' | 'shipping' | 'completed' | 'cancelled'>('all');
  const [cancellingCode, setCancellingCode] = useState<string | null>(null);

  const fetchOrders = useCallback(async () => {
    if (!token) return;
    setLoading(true);
    setError(null);
    try {
      const data = await api.getMyOrders(token, 50);
      setOrders(data || []);
    } catch (err: unknown) {
      setError(err instanceof Error ? err.message : 'Không thể tải danh sách đơn hàng');
    } finally {
      setLoading(false);
    }
  }, [token]);

  useEffect(() => {
    fetchOrders();
  }, [fetchOrders]);

  const handleCancel = async (code: string) => {
    if (!token) return;
    const confirmCancel = window.confirm(`Bạn có chắc chắn muốn hủy đơn hàng #${code}?`);
    if (!confirmCancel) return;

    setCancellingCode(code);
    try {
      await api.cancelOrder(code, token);
      await fetchOrders();
    } catch (err: unknown) {
      alert(err instanceof Error ? err.message : 'Hủy đơn hàng thất bại');
    } finally {
      setCancellingCode(null);
    }
  };

  const formatVnd = (amount: number) => {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
  };

  const filteredOrders = orders.filter((order) => {
    const key = order.status?.key?.toLowerCase();
    if (filterTab === 'pending') return key === 'pending' || key === 'cho_xac_nhan';
    if (filterTab === 'shipping') return key === 'shipping' || key === 'dang_giao';
    if (filterTab === 'completed') return key === 'completed' || key === 'hoan_thanh';
    if (filterTab === 'cancelled') return key === 'cancelled' || key === 'da_huy';
    return true;
  });

  return (
    <div className="flex flex-col min-h-screen bg-[#f8fafc] text-slate-900">
      <Header />

      <main className="flex-grow max-w-[1200px] w-full mx-auto px-4 lg:px-8 py-6">
        <Breadcrumb items={[{ label: 'Lịch sử đơn hàng của tôi' }]} />

        <div className="mb-8">
          <h1 className="text-2xl sm:text-3xl font-black uppercase text-slate-900 tracking-tight">
            LỊCH SỬ ĐƠN HÀNG
          </h1>
          <p className="text-xs text-slate-500 mt-1 font-medium">
            Theo dõi trạng thái xử lý, vận chuyển và tra cứu bảo hành các sản phẩm bạn đã đặt.
          </p>
        </div>

        {!token ? (
          <div className="max-w-md mx-auto bg-white rounded-3xl border border-slate-200/90 p-12 text-center space-y-4 shadow-xs my-8">
            <span className="material-symbols-outlined text-5xl text-slate-300">lock</span>
            <h2 className="text-base font-bold text-slate-800">Vui lòng đăng nhập để xem đơn hàng</h2>
            <Link
              href="/login?returnUrl=/orders"
              className="inline-block px-6 py-2.5 rounded-xl bg-[#13634B] text-white text-xs font-bold hover:bg-[#0e4a38]"
            >
              Đăng nhập tài khoản
            </Link>
          </div>
        ) : loading ? (
          <div className="bg-white rounded-3xl border border-slate-200/90 p-16 text-center space-y-4 my-8">
            <div className="w-10 h-10 border-4 border-[#13634B] border-t-transparent rounded-full animate-spin mx-auto"></div>
            <p className="text-xs font-bold text-slate-500">Đang tải lịch sử đơn hàng...</p>
          </div>
        ) : error ? (
          <div className="bg-white rounded-3xl border border-slate-200/90 p-12 text-center space-y-4 my-8">
            <span className="material-symbols-outlined text-5xl text-rose-500">error</span>
            <p className="text-sm font-bold text-rose-600">{error}</p>
            <button
              onClick={fetchOrders}
              className="px-6 py-2 rounded-xl bg-[#13634B] text-white text-xs font-bold hover:bg-[#0e4a38]"
            >
              Thử lại
            </button>
          </div>
        ) : orders.length === 0 ? (
          <div className="bg-white rounded-3xl border border-slate-200/90 p-16 text-center space-y-4 shadow-xs my-8">
            <span className="material-symbols-outlined text-6xl text-slate-300">shopping_bag</span>
            <h2 className="text-base font-bold text-slate-800">Bạn chưa có đơn hàng nào</h2>
            <p className="text-xs text-slate-400 max-w-sm mx-auto">
              Hãy chọn cho mình một cây vợt ưng ý hoặc đôi giày thi đấu đỉnh cao nhé!
            </p>
            <Link
              href="/products"
              className="inline-block px-6 py-2.5 rounded-xl bg-[#13634B] text-white text-xs font-bold hover:bg-[#0e4a38]"
            >
              Khám phá sản phẩm
            </Link>
          </div>
        ) : (
          <div className="space-y-6">
            {/* Status Tabs */}
            <div className="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none bg-white p-2 rounded-2xl border border-slate-200/90">
              {[
                { id: 'all', label: 'Tất cả' },
                { id: 'pending', label: 'Chờ xác nhận' },
                { id: 'shipping', label: 'Đang giao hàng' },
                { id: 'completed', label: 'Hoàn thành' },
                { id: 'cancelled', label: 'Đã hủy' },
              ].map((tab) => (
                <button
                  key={tab.id}
                  onClick={() => setFilterTab(tab.id as typeof filterTab)}
                  className={`px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap cursor-pointer ${
                    filterTab === tab.id
                      ? 'bg-[#13634B] text-white shadow-xs'
                      : 'text-slate-600 hover:text-[#13634B]'
                  }`}
                >
                  {tab.label}
                </button>
              ))}
            </div>

            {/* Orders Cards List */}
            <div className="space-y-4">
              {filteredOrders.length === 0 ? (
                <div className="bg-white rounded-2xl p-8 text-center text-xs text-slate-400">
                  Không có đơn hàng nào trong mục này
                </div>
              ) : (
                filteredOrders.map((order) => {
                  const isPending = order.status?.key?.toLowerCase() === 'pending' || order.status?.key?.toLowerCase() === 'cho_xac_nhan';
                  const isCancelled = order.status?.key?.toLowerCase() === 'cancelled' || order.status?.key?.toLowerCase() === 'da_huy';

                  return (
                    <div
                      key={order.id}
                      className="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs hover:border-slate-300 transition space-y-4"
                    >
                      <div className="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-slate-100">
                        <div className="flex items-center gap-3">
                          <span className="text-xs font-bold text-slate-400 uppercase">Mã đơn:</span>
                          <span className="font-mono text-sm font-black text-[#13634B]">#{order.code}</span>
                          <span className="text-xs text-slate-400">
                            • {new Date(order.created_at).toLocaleDateString('vi-VN')}
                          </span>
                        </div>

                        <span className={`px-3 py-1 rounded-full text-xs font-bold ${
                          isCancelled
                            ? 'bg-rose-50 text-rose-700 border border-rose-200'
                            : isPending
                            ? 'bg-amber-50 text-amber-700 border border-amber-200'
                            : 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                        }`}>
                          {order.status?.label || 'Chờ xác nhận'}
                        </span>
                      </div>

                      <div className="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                        <div>
                          <div className="font-bold text-slate-700 mb-0.5">Người nhận:</div>
                          <div className="text-slate-900 font-semibold">{order.receiver_info?.name} ({order.receiver_info?.phone})</div>
                          <div className="text-slate-500 truncate mt-0.5">{order.receiver_info?.address}</div>
                        </div>

                        <div>
                          <div className="font-bold text-slate-700 mb-0.5">Thanh toán:</div>
                          <div className="text-slate-900 font-semibold uppercase">{order.payment?.method}</div>
                          <div className="text-slate-500">{order.payment?.status}</div>
                        </div>

                        <div className="md:text-right">
                          <div className="font-bold text-slate-700 mb-0.5">Tổng thanh toán:</div>
                          <div className="text-base font-black text-[#13634B]">
                            {formatVnd(order.financial?.total || 0)}
                          </div>
                        </div>
                      </div>

                      <div className="pt-3 border-t border-slate-100 flex items-center justify-between">
                        <Link
                          href={`/orders/${order.code}`}
                          className="text-xs font-bold text-[#13634B] hover:underline flex items-center gap-1"
                        >
                          <span className="material-symbols-outlined text-sm">visibility</span>
                          Xem chi tiết đơn hàng
                        </Link>

                        {isPending && (
                          <button
                            onClick={() => handleCancel(order.code)}
                            disabled={cancellingCode === order.code}
                            className="px-4 py-1.5 rounded-xl border border-rose-300 text-rose-600 hover:bg-rose-50 text-xs font-bold transition disabled:opacity-50 cursor-pointer"
                          >
                            {cancellingCode === order.code ? 'Đang hủy...' : 'Hủy đơn'}
                          </button>
                        )}
                      </div>
                    </div>
                  );
                })
              )}
            </div>
          </div>
        )}
      </main>

      <Footer />
    </div>
  );
}

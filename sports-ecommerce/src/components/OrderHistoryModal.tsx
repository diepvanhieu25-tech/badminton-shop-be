"use client";

import React, { useEffect, useState, useCallback } from 'react';
import { useAuth } from '@/context/AuthContext';
import * as api from '@/services/api';
import { ApiOrder } from '@/types/api';

interface OrderHistoryModalProps {
  isOpen: boolean;
  onClose: () => void;
  onOpenAuth: () => void;
}

export default function OrderHistoryModal({
  isOpen,
  onClose,
  onOpenAuth,
}: OrderHistoryModalProps) {
  const { token } = useAuth();
  const [orders, setOrders] = useState<ApiOrder[]>([]);
  const [loading, setLoading] = useState<boolean>(false);
  const [error, setError] = useState<string | null>(null);
  const [cancellingCode, setCancellingCode] = useState<string | null>(null);

  const fetchOrders = useCallback(async () => {
    if (!token) return;
    setLoading(true);
    setError(null);
    try {
      const data = await api.getMyOrders(token);
      setOrders(data || []);
    } catch (err: unknown) {
      setError(err instanceof Error ? err.message : 'Không thể tải danh sách đơn hàng.');
    } finally {
      setLoading(false);
    }
  }, [token]);

  useEffect(() => {
    if (isOpen && token) {
      fetchOrders();
    }
  }, [isOpen, token, fetchOrders]);

  if (!isOpen) return null;

  const handleCancelOrder = async (code: string) => {
    if (!token) return;
    const confirmCancel = window.confirm(`Bạn có chắc chắn muốn hủy đơn hàng #${code}?`);
    if (!confirmCancel) return;

    setCancellingCode(code);
    try {
      await api.cancelOrder(code, token);
      await fetchOrders();
    } catch (err: unknown) {
      alert(err instanceof Error ? err.message : 'Hủy đơn hàng thất bại.');
    } finally {
      setCancellingCode(null);
    }
  };

  const formatVnd = (amount: number) => {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
  };

  const getStatusBadge = (status: { key: string; label: string; color?: string }) => {
    const key = status?.key?.toLowerCase();
    switch (key) {
      case 'completed':
      case 'hoan_thanh':
        return (
          <span className="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center gap-1">
            <span className="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
            {status.label || 'Hoàn thành'}
          </span>
        );
      case 'shipping':
      case 'dang_giao':
        return (
          <span className="px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200 flex items-center gap-1">
            <span className="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
            {status.label || 'Đang giao'}
          </span>
        );
      case 'processing':
      case 'dang_xu_ly':
        return (
          <span className="px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 flex items-center gap-1">
            <span className="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
            {status.label || 'Đang xử lý'}
          </span>
        );
      case 'cancelled':
      case 'da_huy':
        return (
          <span className="px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200 flex items-center gap-1">
            <span className="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
            {status.label || 'Đã hủy'}
          </span>
        );
      default:
        return (
          <span className="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 flex items-center gap-1">
            <span className="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
            {status.label || 'Chờ xác nhận'}
          </span>
        );
    }
  };

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs p-4 overflow-y-auto">
      <div 
        className="bg-white text-slate-900 w-full max-w-3xl rounded-3xl shadow-2xl border border-slate-200 overflow-hidden relative my-8 max-h-[90vh] flex flex-col"
        onClick={(e) => e.stopPropagation()}
      >
        {/* Header */}
        <div className="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/70">
          <div className="flex items-center gap-3">
            <div className="w-10 h-10 rounded-xl bg-[#13634B]/10 text-[#13634B] flex items-center justify-center">
              <span className="material-symbols-outlined text-2xl">receipt_long</span>
            </div>
            <div>
              <h2 className="text-xl font-black text-slate-900">Lịch sử đơn hàng</h2>
              <p className="text-xs text-slate-500">Theo dõi trạng thái và tiến độ giao hàng của bạn</p>
            </div>
          </div>
          <button
            onClick={onClose}
            className="w-9 h-9 rounded-full bg-white hover:bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-800 transition cursor-pointer"
          >
            <span className="material-symbols-outlined text-xl">close</span>
          </button>
        </div>

        {/* Content Body */}
        <div className="p-6 overflow-y-auto flex-1 space-y-4">
          {!token ? (
            <div className="py-16 text-center space-y-4">
              <span className="material-symbols-outlined text-6xl text-slate-300">lock</span>
              <h3 className="text-lg font-bold text-slate-800">Vui lòng đăng nhập</h3>
              <p className="text-xs text-slate-500 max-w-sm mx-auto">
                Bạn cần đăng nhập để xem lịch sử mua sắm và theo dõi tình trạng đơn hàng.
              </p>
              <button
                onClick={() => {
                  onClose();
                  onOpenAuth();
                }}
                className="px-6 py-2.5 rounded-xl bg-[#13634B] text-white font-bold text-sm shadow-md hover:bg-[#0e4a38] transition cursor-pointer"
              >
                Đăng nhập ngay
              </button>
            </div>
          ) : loading ? (
            <div className="py-16 flex flex-col items-center justify-center space-y-3">
              <div className="w-10 h-10 border-4 border-[#13634B] border-t-transparent rounded-full animate-spin"></div>
              <p className="text-xs font-semibold text-slate-500">Đang tải lịch sử đơn hàng...</p>
            </div>
          ) : error ? (
            <div className="p-6 text-center space-y-3 bg-rose-50 rounded-2xl border border-rose-200">
              <span className="material-symbols-outlined text-4xl text-rose-600">error</span>
              <p className="text-sm font-bold text-rose-600">{error}</p>
              <button
                onClick={fetchOrders}
                className="px-5 py-2 rounded-xl bg-[#13634B] text-white text-xs font-bold shadow-xs cursor-pointer"
              >
                Thử lại
              </button>
            </div>
          ) : orders.length === 0 ? (
            <div className="py-16 text-center space-y-4">
              <span className="material-symbols-outlined text-6xl text-slate-300">shopping_bag</span>
              <h3 className="text-base font-bold text-slate-800">Chưa có đơn hàng nào</h3>
              <p className="text-xs text-slate-500">
                Hãy lựa chọn những sản phẩm cầu lông tốt nhất cho buổi tập tiếp theo của bạn!
              </p>
              <button
                onClick={onClose}
                className="px-6 py-2.5 rounded-xl bg-[#13634B] text-white font-bold text-xs shadow-md hover:bg-[#0e4a38] transition cursor-pointer"
              >
                Khám phá cửa hàng
              </button>
            </div>
          ) : (
            orders.map((order) => {
              const canCancel = order.status?.key?.toLowerCase() === 'pending' || order.status?.key?.toLowerCase() === 'cho_xac_nhan';
              return (
                <div
                  key={order.id}
                  className="rounded-2xl border border-slate-200/90 bg-white p-5 space-y-4 shadow-xs"
                >
                  {/* Order Top Line */}
                  <div className="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-slate-100">
                    <div className="flex items-center gap-2">
                      <span className="text-xs font-bold uppercase text-slate-400">Mã đơn:</span>
                      <span className="font-mono text-sm font-black text-[#13634B]">{order.code}</span>
                    </div>
                    <div className="flex items-center gap-3">
                      <span className="text-xs text-slate-400">
                        {new Date(order.created_at).toLocaleDateString('vi-VN', {
                          day: '2-digit',
                          month: '2-digit',
                          year: 'numeric',
                          hour: '2-digit',
                          minute: '2-digit',
                        })}
                      </span>
                      {getStatusBadge(order.status)}
                    </div>
                  </div>

                  {/* Order Details */}
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <div className="space-y-1">
                      <div className="font-bold text-slate-700">Thông tin người nhận:</div>
                      <div className="text-slate-600 font-medium">
                        {order.receiver_info?.name} • {order.receiver_info?.phone}
                      </div>
                      <div className="text-slate-500 truncate" title={order.receiver_info?.address}>
                        {order.receiver_info?.address}
                      </div>
                    </div>

                    <div className="space-y-1 md:text-right">
                      <div className="font-bold text-slate-700">
                        Phương thức thanh toán: <span className="uppercase text-[#13634B] font-bold">{order.payment?.method}</span>
                      </div>
                      <div className="text-slate-500">
                        Tạm tính: {formatVnd(order.financial?.subtotal || 0)} + Phí ship: {formatVnd(order.financial?.shipping_fee || 0)}
                      </div>
                      <div className="text-sm font-black text-[#13634B]">
                        Tổng thanh toán: {formatVnd(order.financial?.total || 0)}
                      </div>
                    </div>
                  </div>

                  {/* Footer actions */}
                  {canCancel && (
                    <div className="pt-2 flex justify-end border-t border-slate-100">
                      <button
                        onClick={() => handleCancelOrder(order.code)}
                        disabled={cancellingCode === order.code}
                        className="px-4 py-1.5 rounded-lg border border-rose-300 text-rose-600 hover:bg-rose-50 text-xs font-bold transition disabled:opacity-50 cursor-pointer"
                      >
                        {cancellingCode === order.code ? 'Đang hủy...' : 'Hủy đơn hàng'}
                      </button>
                    </div>
                  )}
                </div>
              );
            })
          )}
        </div>

        {/* Modal Footer */}
        <div className="p-4 border-t border-slate-100 bg-slate-50/70 flex justify-end">
          <button
            onClick={onClose}
            className="px-6 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition cursor-pointer"
          >
            Đóng lại
          </button>
        </div>
      </div>
    </div>
  );
}

"use client";

import React, { useEffect, useState } from 'react';
import Link from 'next/link';
import Header from '@/components/Header';
import Footer from '@/components/Footer';
import Breadcrumb from '@/components/Breadcrumb';
import { useAuth } from '@/context/AuthContext';
import * as api from '@/services/api';

export default function ProfilePage() {
  const { token, user, logout } = useAuth();

  const [name, setName] = useState(user?.name || '');
  const [phone, setPhone] = useState(user?.phone || '');
  const [address, setAddress] = useState(user?.address || '');
  const [loading, setLoading] = useState(false);
  const [successMsg, setSuccessMsg] = useState<string | null>(null);
  const [errorMsg, setErrorMsg] = useState<string | null>(null);

  useEffect(() => {
    if (user) {
      setName(user.name);
      setPhone(user.phone || '');
      setAddress(user.address || '');
    }
  }, [user]);

  const handleUpdate = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!token) return;
    setLoading(true);
    setSuccessMsg(null);
    setErrorMsg(null);
    try {
      await api.updateProfile(token, {
        name,
        phone: phone || undefined,
        address: address || undefined,
      });
      setSuccessMsg('Cập nhật thông tin tài khoản thành công!');
      setTimeout(() => setSuccessMsg(null), 3500);
    } catch (err: unknown) {
      setErrorMsg(err instanceof Error ? err.message : 'Cập nhật thất bại');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="flex flex-col min-h-screen bg-[#f8fafc] text-slate-900">
      <Header />

      <main className="flex-grow max-w-[1200px] w-full mx-auto px-4 lg:px-8 py-6">
        <Breadcrumb items={[{ label: 'Tài khoản của tôi' }]} />

        <div className="mb-8">
          <h1 className="text-2xl sm:text-3xl font-black uppercase text-slate-900 tracking-tight">
            QUẢN LÝ TÀI KHOẢN
          </h1>
          <p className="text-xs text-slate-500 mt-1 font-medium">
            Xem và cập nhật thông tin nhận hàng, số điện thoại và thông tin cá nhân.
          </p>
        </div>

        {!token ? (
          <div className="max-w-md mx-auto bg-white rounded-3xl border border-slate-200/90 p-12 text-center space-y-4 shadow-xs my-8">
            <span className="material-symbols-outlined text-5xl text-slate-300">lock</span>
            <h2 className="text-base font-bold text-slate-800">Vui lòng đăng nhập để xem thông tin</h2>
            <Link
              href="/login?returnUrl=/profile"
              className="inline-block px-6 py-2.5 rounded-xl bg-[#13634B] text-white text-xs font-bold hover:bg-[#0e4a38]"
            >
              Đăng nhập ngay
            </Link>
          </div>
        ) : (
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 my-6">
            {/* Left Column: Quick Profile Info & Shortcuts (4 cols) */}
            <div className="lg:col-span-4 space-y-6">
              <div className="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs text-center space-y-4">
                <div className="w-20 h-20 rounded-full bg-[#13634B]/10 text-[#13634B] flex items-center justify-center mx-auto text-3xl font-black">
                  {user?.name ? user.name.charAt(0).toUpperCase() : 'U'}
                </div>
                <div>
                  <h3 className="font-bold text-base text-slate-900">{user?.name}</h3>
                  <p className="text-xs text-slate-400">{user?.email}</p>
                </div>
                <div className="inline-block px-3 py-1 bg-emerald-50 text-[#13634B] text-xs font-bold rounded-full border border-emerald-200">
                  Thành viên chính thức
                </div>

                <div className="pt-4 border-t border-slate-100 space-y-2 text-left">
                  <Link
                    href="/orders"
                    className="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-[#13634B]/5 text-xs font-bold text-slate-700 hover:text-[#13634B] transition"
                  >
                    <span className="flex items-center gap-2">
                      <span className="material-symbols-outlined text-base">receipt_long</span>
                      Đơn hàng của tôi
                    </span>
                    <span className="material-symbols-outlined text-sm">arrow_forward</span>
                  </Link>

                  <Link
                    href="/cart"
                    className="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-[#13634B]/5 text-xs font-bold text-slate-700 hover:text-[#13634B] transition"
                  >
                    <span className="flex items-center gap-2">
                      <span className="material-symbols-outlined text-base">shopping_cart</span>
                      Giỏ hàng hiện tại
                    </span>
                    <span className="material-symbols-outlined text-sm">arrow_forward</span>
                  </Link>

                  <button
                    onClick={logout}
                    className="w-full flex items-center justify-between p-3 rounded-xl hover:bg-rose-50 text-xs font-bold text-rose-600 transition cursor-pointer"
                  >
                    <span className="flex items-center gap-2">
                      <span className="material-symbols-outlined text-base">logout</span>
                      Đăng xuất tài khoản
                    </span>
                  </button>
                </div>
              </div>
            </div>

            {/* Right Column: Update Info Form (8 cols) */}
            <div className="lg:col-span-8">
              <div className="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-6">
                <h3 className="text-xs font-bold uppercase tracking-wider text-slate-900 pb-3 border-b border-slate-100">
                  Cập nhật thông tin nhận hàng
                </h3>

                {successMsg && (
                  <div className="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs rounded-2xl font-bold flex items-center gap-2">
                    <span className="material-symbols-outlined text-base">check_circle</span>
                    <span>{successMsg}</span>
                  </div>
                )}

                {errorMsg && (
                  <div className="p-4 bg-rose-50 border border-rose-200 text-rose-600 text-xs rounded-2xl font-bold flex items-center gap-2">
                    <span className="material-symbols-outlined text-base">error</span>
                    <span>{errorMsg}</span>
                  </div>
                )}

                <form onSubmit={handleUpdate} className="space-y-4">
                  <div>
                    <label className="block text-xs font-bold uppercase text-slate-700 mb-1">
                      Email đăng nhập
                    </label>
                    <input
                      type="email"
                      disabled
                      value={user?.email || ''}
                      className="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-100 text-slate-500 text-xs font-medium cursor-not-allowed"
                    />
                    <p className="text-[11px] text-slate-400 mt-1">Email cố định gắn với tài khoản thành viên.</p>
                  </div>

                  <div>
                    <label className="block text-xs font-bold uppercase text-slate-700 mb-1">
                      Họ và tên *
                    </label>
                    <input
                      type="text"
                      required
                      value={name}
                      onChange={(e) => setName(e.target.value)}
                      className="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium focus:outline-none focus:border-[#13634B] focus:bg-white"
                    />
                  </div>

                  <div>
                    <label className="block text-xs font-bold uppercase text-slate-700 mb-1">
                      Số điện thoại giao hàng
                    </label>
                    <input
                      type="tel"
                      value={phone}
                      onChange={(e) => setPhone(e.target.value)}
                      placeholder="0912345678"
                      className="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium focus:outline-none focus:border-[#13634B] focus:bg-white"
                    />
                  </div>

                  <div>
                    <label className="block text-xs font-bold uppercase text-slate-700 mb-1">
                      Địa chỉ nhận hàng mặc định
                    </label>
                    <textarea
                      rows={3}
                      value={address}
                      onChange={(e) => setAddress(e.target.value)}
                      placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố..."
                      className="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium focus:outline-none focus:border-[#13634B] focus:bg-white"
                    />
                  </div>

                  <div className="pt-2">
                    <button
                      type="submit"
                      disabled={loading}
                      className="px-8 py-3.5 rounded-xl bg-[#13634B] hover:bg-[#0e4a38] text-white font-bold text-xs uppercase tracking-wider transition shadow-md shadow-[#13634B]/20 disabled:opacity-50 cursor-pointer"
                    >
                      {loading ? 'Đang lưu...' : 'Lưu thay đổi'}
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        )}
      </main>

      <Footer />
    </div>
  );
}

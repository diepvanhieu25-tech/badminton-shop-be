"use client";

import React, { useState, useEffect, useRef } from 'react';
import Link from 'next/link';
import { useRouter } from 'next/navigation';
import { useAuth } from '@/context/AuthContext';
import { useCart } from '@/context/CartContext';
import * as api from '@/services/api';
import { ApiCategory } from '@/types/api';

interface HeaderProps {
  searchQuery?: string;
  onSearchChange?: (q: string) => void;
}

const Header: React.FC<HeaderProps> = ({
  searchQuery: externalSearchQuery,
  onSearchChange: externalOnSearchChange,
}) => {
  const router = useRouter();
  const { user, logout } = useAuth();
  const { cartCount, toggleMiniCart } = useCart();

  const [internalQuery, setInternalQuery] = useState('');
  const [userDropdownOpen, setUserDropdownOpen] = useState(false);
  const [categoryDropdownOpen, setCategoryDropdownOpen] = useState(false);
  const [categories, setCategories] = useState<ApiCategory[]>([]);

  const dropdownRef = useRef<HTMLDivElement>(null);

  const query = externalSearchQuery !== undefined ? externalSearchQuery : internalQuery;
  const setQuery = externalOnSearchChange || setInternalQuery;

  // Fetch dynamic categories directly from Backend API
  useEffect(() => {
    let isMounted = true;
    api.getCategories()
      .then((cats) => {
        if (isMounted) setCategories(cats || []);
      })
      .catch(() => null);
    return () => {
      isMounted = false;
    };
  }, []);

  // Close dropdown on outside click
  useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => {
      if (
        dropdownRef.current &&
        !dropdownRef.current.contains(event.target as Node)
      ) {
        setCategoryDropdownOpen(false);
      }
    };
    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, []);

  const handleSearchSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (query.trim()) {
      router.push(`/products?keyword=${encodeURIComponent(query.trim())}`);
    } else {
      router.push('/products');
    }
  };

  return (
    <header className="w-full bg-white shadow-xs sticky top-0 z-40 border-b border-slate-200">
      {/* 1. Top Utility Info Bar */}
      <div className="w-full bg-[#0a3629] text-emerald-100/90 text-xs border-b border-emerald-950">
        <div className="max-w-[1400px] mx-auto px-4 lg:px-8 py-2 flex justify-between items-center">
          <div className="flex items-center gap-6">
            <span className="flex items-center gap-1.5 hover:text-white transition-colors cursor-pointer group">
              <span className="material-symbols-outlined text-[15px] text-[#00D26A]">phone_in_talk</span>
              Hotline: <strong className="text-white font-bold">1900 6868</strong>
            </span>
            <span className="hidden sm:inline w-[1px] h-3 bg-emerald-800"></span>
            <span className="hidden sm:flex items-center gap-1.5 hover:text-white transition-colors cursor-pointer group">
              <span className="material-symbols-outlined text-[15px] text-[#00D26A]">storefront</span>
              58 Showroom toàn quốc
            </span>
            <span className="hidden md:inline w-[1px] h-3 bg-emerald-800"></span>
            <span className="hidden md:flex items-center gap-1.5 text-emerald-300">
              <span className="material-symbols-outlined text-[15px] text-[#00D26A]">verified</span>
              DSPORT NEXUS - Đại lý ủy quyền chính hãng
            </span>
          </div>

          <div className="flex items-center gap-4">
            {user ? (
              <div className="relative">
                <button
                  onClick={() => setUserDropdownOpen(!userDropdownOpen)}
                  className="flex items-center gap-2 hover:text-white transition font-bold text-xs cursor-pointer py-0.5"
                >
                  <span className="w-2 h-2 rounded-full bg-[#00D26A]"></span>
                  <span className="text-emerald-300">{user.name}</span>
                  <span className="material-symbols-outlined text-sm">expand_more</span>
                </button>

                {userDropdownOpen && (
                  <div className="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 text-slate-800">
                    <div className="px-4 py-2 border-b border-slate-100">
                      <p className="text-[11px] text-slate-400">Tài khoản</p>
                      <p className="text-xs font-bold text-slate-800 truncate">{user.email}</p>
                    </div>
                    <Link
                      href="/profile"
                      onClick={() => setUserDropdownOpen(false)}
                      className="w-full px-4 py-2 text-left text-xs font-bold hover:bg-emerald-50 text-slate-700 hover:text-[#13634B] flex items-center gap-2 transition"
                    >
                      <span className="material-symbols-outlined text-base text-[#13634B]">person</span>
                      Thông tin cá nhân
                    </Link>
                    <Link
                      href="/orders"
                      onClick={() => setUserDropdownOpen(false)}
                      className="w-full px-4 py-2 text-left text-xs font-bold hover:bg-emerald-50 text-slate-700 hover:text-[#13634B] flex items-center gap-2 transition"
                    >
                      <span className="material-symbols-outlined text-base text-[#13634B]">receipt_long</span>
                      Lịch sử đơn hàng
                    </Link>
                    <button
                      onClick={() => {
                        setUserDropdownOpen(false);
                        logout();
                      }}
                      className="w-full px-4 py-2 text-left text-xs font-bold text-rose-600 hover:bg-rose-50 flex items-center gap-2 border-t border-slate-100 transition cursor-pointer"
                    >
                      <span className="material-symbols-outlined text-base">logout</span>
                      Đăng xuất
                    </button>
                  </div>
                )}
              </div>
            ) : (
              <div className="flex items-center gap-3">
                <Link
                  href="/login"
                  className="hover:text-white transition font-bold flex items-center gap-1 text-xs text-emerald-300"
                >
                  <span className="material-symbols-outlined text-sm">login</span>
                  Đăng nhập
                </Link>
                <span className="w-[1px] h-3 bg-emerald-800"></span>
                <Link
                  href="/register"
                  className="hover:text-white transition font-bold text-xs text-emerald-200"
                >
                  Đăng ký
                </Link>
              </div>
            )}
          </div>
        </div>
      </div>

      {/* 2. Main Brand Bar with User's Logo */}
      <div className="max-w-[1400px] mx-auto px-4 lg:px-8 py-3.5 flex items-center justify-between gap-4 md:gap-8 bg-white">
        {/* Real DSPORT NEXUS Logo */}
        <Link className="flex items-center group flex-shrink-0" href="/">
          {/* eslint-disable-next-line @next/next/no-img-element */}
          <img
            src="/logo-d.png"
            alt="DSPORT NEXUS"
            className="h-12 sm:h-14 md:h-16 w-auto object-contain hover:opacity-95 transition-opacity"
          />
        </Link>

        {/* Real Form Search Box */}
        <div className="flex-1 max-w-[650px] hidden sm:block">
          <form onSubmit={handleSearchSubmit} className="flex w-full items-center rounded-xl border border-slate-200 bg-slate-50/80 focus-within:border-[#13634B] focus-within:bg-white focus-within:ring-2 focus-within:ring-[#13634B]/10 transition-all overflow-hidden h-11 px-3.5">
            <span className="material-symbols-outlined text-slate-400 text-xl mr-2">search</span>
            <input
              type="text"
              value={query}
              onChange={(e) => setQuery(e.target.value)}
              className="flex-1 bg-transparent border-none focus:outline-none text-slate-800 placeholder:text-slate-400 text-xs font-semibold"
              placeholder="Tìm kiếm vợt Yonex Astrox, Victor Ryuga, Lining, giày thi đấu..."
            />
            {query && (
              <button
                type="button"
                onClick={() => setQuery('')}
                className="text-slate-400 hover:text-slate-600 text-xs px-1 cursor-pointer"
              >
                <span className="material-symbols-outlined text-base">close</span>
              </button>
            )}
            <button
              type="submit"
              className="ml-2 px-4 py-1.5 bg-[#13634B] text-white text-[11px] font-bold rounded-lg hover:bg-[#0e4a38] transition cursor-pointer"
            >
              Tìm kiếm
            </button>
          </form>
        </div>

        {/* Action Icons */}
        <div className="flex items-center gap-3 flex-shrink-0">
          {/* Order Tracking Button */}
          <Link
            href="/orders"
            title="Tra cứu đơn hàng"
            className="p-2.5 rounded-xl border border-slate-200 bg-white hover:border-[#13634B] hover:text-[#13634B] text-slate-700 transition flex items-center gap-1.5 shadow-xs"
          >
            <span className="material-symbols-outlined text-lg">local_shipping</span>
            <span className="text-xs font-bold hidden lg:inline">Đơn hàng</span>
          </Link>

          {/* Mini Cart Toggle Button */}
          <button
            type="button"
            onClick={toggleMiniCart}
            title="Xem giỏ hàng"
            className="p-2.5 px-3.5 rounded-xl bg-[#13634B] hover:bg-[#0e4a38] text-white transition shadow-md shadow-[#13634B]/20 flex items-center gap-2 cursor-pointer"
          >
            <div className="relative flex items-center">
              <span className="material-symbols-outlined text-lg">shopping_bag</span>
              {cartCount > 0 && (
                <span className="absolute -top-2.5 -right-2.5 bg-amber-400 text-slate-950 text-[10px] font-black h-4.5 w-4.5 rounded-full flex items-center justify-center border-2 border-white shadow-xs">
                  {cartCount}
                </span>
              )}
            </div>
            <span className="text-xs font-black uppercase tracking-wider hidden sm:inline">
              Giỏ hàng
            </span>
          </button>
        </div>
      </div>

      {/* 3. Streamlined Clean Navigation Bar (No Duplicate Tabs) */}
      <nav className="w-full bg-[#13634B] text-white shadow-xs relative">
        <div className="max-w-[1400px] mx-auto px-4 lg:px-8 flex items-center justify-between">
          <div className="flex items-center gap-2">
            {/* Dynamic Backend Category Dropdown */}
            <div className="relative" ref={dropdownRef}>
              <button
                type="button"
                onClick={() => setCategoryDropdownOpen(!categoryDropdownOpen)}
                className={`flex items-center gap-2 px-4 py-2.5 text-xs font-black uppercase tracking-wider transition-colors cursor-pointer ${categoryDropdownOpen
                  ? 'bg-[#0a3629] text-[#00D26A]'
                  : 'bg-[#0e4a38] hover:bg-[#0a3629] text-white'
                  }`}
              >
                <span className="material-symbols-outlined text-base">menu</span>
                <span>DANH MỤC SẢN PHẨM</span>
                <span className={`material-symbols-outlined text-sm transition-transform duration-200 ${categoryDropdownOpen ? 'rotate-180' : ''}`}>
                  expand_more
                </span>
              </button>

              {/* Dynamic Categories Dropdown Menu - Loaded 100% from Backend */}
              {categoryDropdownOpen && (
                <div className="absolute left-0 top-full mt-1 w-64 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 text-slate-800 animate-fadeIn">
                  <div className="px-4 py-2 border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                    Danh Mục Từ Hệ Thống
                  </div>

                  <div className="py-1">
                    <Link
                      href="/products"
                      onClick={() => setCategoryDropdownOpen(false)}
                      className="px-4 py-2.5 flex items-center justify-between text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-[#13634B] transition"
                    >
                      <span>Tất cả sản phẩm</span>
                      <span className="material-symbols-outlined text-sm text-slate-400">chevron_right</span>
                    </Link>

                    {categories.map((cat) => (
                      <Link
                        key={cat.id}
                        href={`/products?category_id=${cat.id}`}
                        onClick={() => setCategoryDropdownOpen(false)}
                        className="px-4 py-2.5 flex items-center justify-between text-xs font-semibold text-slate-700 hover:bg-emerald-50/60 hover:text-[#13634B] transition"
                      >
                        <span>{cat.name}</span>
                        <span className="material-symbols-outlined text-sm text-slate-400">chevron_right</span>
                      </Link>
                    ))}
                  </div>
                </div>
              )}
            </div>

            {/* Clean Concise Main Tabs (Not Duplicating Categories) */}
            <ul className="flex items-center gap-1 text-xs font-bold uppercase tracking-wider py-1">
              <li>
                <Link
                  href="/"
                  className="block px-3 py-2 rounded-lg hover:bg-white/15 transition-colors whitespace-nowrap"
                >
                  Trang chủ
                </Link>
              </li>
              <li>
                <Link
                  href="/products"
                  className="block px-3 py-2 rounded-lg hover:bg-white/15 transition-colors whitespace-nowrap"
                >
                  Sản phẩm
                </Link>
              </li>
              <li>
                <Link
                  href="/products?sort_by=newest"
                  className="block px-3 py-2 rounded-lg hover:bg-white/15 transition-colors whitespace-nowrap"
                >
                  Hàng mới về
                </Link>
              </li>
              <li className="hidden md:inline-block">
                <Link
                  href="/orders"
                  className="block px-3 py-2 rounded-lg hover:bg-white/15 transition-colors whitespace-nowrap"
                >
                  Tra cứu đơn
                </Link>
              </li>
            </ul>
          </div>

          {/* Hot Deals Highlight Link */}
          <Link
            href="/products?is_featured=1"
            className="px-3.5 py-2 rounded-lg text-amber-300 hover:text-white hover:bg-white/15 transition-colors whitespace-nowrap flex items-center gap-1.5 text-xs font-black uppercase tracking-wider flex-shrink-0"
          >
            <span className="material-symbols-outlined text-sm text-amber-400 animate-pulse">local_fire_department</span>
            <span>Hot Deals</span>
          </Link>
        </div>
      </nav>
    </header>
  );
};

export default Header;

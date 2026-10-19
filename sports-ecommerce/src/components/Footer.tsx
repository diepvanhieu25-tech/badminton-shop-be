"use client";

import React from 'react';
import Link from 'next/link';

const Footer: React.FC = () => {
  return (
    <footer className="bg-[#0a3629] text-white pt-16 mt-auto border-t-2 border-[#13634B]">
      <div className="max-w-[1400px] mx-auto px-4 lg:px-8 pb-12">
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12">
          {/* Brand Info with Logo */}
          <div className="flex flex-col gap-4">
            <Link className="inline-block" href="/">
              <div className="bg-white p-2 rounded-xl inline-block shadow-sm">
                {/* eslint-disable-next-line @next/next/no-img-element */}
                <img
                  src="/logo-d.png"
                  alt="DSPORT NEXUS"
                  className="h-12 w-auto object-contain"
                />
              </div>
            </Link>
            <p className="text-emerald-100/70 text-xs leading-relaxed">
              Hệ thống phân phối dụng cụ cầu lông và thể thao chuyên nghiệp DSPORT NEXUS. Đại lý ủy quyền chính thức của Yonex, Lining, Victor tại Việt Nam.
            </p>
            <div className="flex items-center gap-2.5 pt-1">
              <span className="text-xs text-[#00D26A] font-bold">Hotline 24/7: 1900 6868</span>
            </div>
          </div>

          {/* Quick Links */}
          <div className="flex flex-col gap-4">
            <h3 className="text-xs font-bold uppercase tracking-wider text-white border-l-2 border-[#00D26A] pl-3">
              Danh Mục Sản Phẩm
            </h3>
            <ul className="flex flex-col gap-2.5 text-xs text-emerald-100/70 font-medium">
              <li><Link className="hover:text-white transition-colors" href="/products?category_id=1">Vợt Cầu Lông Chính Hãng</Link></li>
              <li><Link className="hover:text-white transition-colors" href="/products?category_id=2">Giày Cầu Lông Thi Đấu</Link></li>
              <li><Link className="hover:text-white transition-colors" href="/products?category_id=3">Balo & Túi Vợt Chuyên Dụng</Link></li>
              <li><Link className="hover:text-white transition-colors" href="/products?category_id=4">Phụ Kiện & Cước Đan Vợt</Link></li>
            </ul>
          </div>

          {/* Customer Service */}
          <div className="flex flex-col gap-4">
            <h3 className="text-xs font-bold uppercase tracking-wider text-white border-l-2 border-[#00D26A] pl-3">
              Chính Sách & Hỗ Trợ
            </h3>
            <ul className="flex flex-col gap-2.5 text-xs text-emerald-100/70 font-medium">
              <li><Link className="hover:text-white transition-colors" href="/products">Chính sách đổi trả 15 ngày</Link></li>
              <li><Link className="hover:text-white transition-colors" href="/products">Bảo hành gãy vợt 1 đổi 1</Link></li>
              <li><Link className="hover:text-white transition-colors" href="/orders">Tra cứu trạng thái đơn hàng</Link></li>
              <li><Link className="hover:text-white transition-colors" href="/products">Dịch vụ đan vợt điện tử 4 nút</Link></li>
            </ul>
          </div>

          {/* Contact Details */}
          <div className="flex flex-col gap-4">
            <h3 className="text-xs font-bold uppercase tracking-wider text-white border-l-2 border-[#00D26A] pl-3">
              Hệ Thống Showroom
            </h3>
            <ul className="flex flex-col gap-3 text-xs text-emerald-100/70">
              <li className="flex items-start gap-2.5">
                <span className="material-symbols-outlined text-[#00D26A] text-base mt-0.5">location_on</span>
                <span>Số 123 Đường Nguyễn Trãi, Thanh Xuân, Hà Nội & 58 chi nhánh toàn quốc</span>
              </li>
              <li className="flex items-center gap-2.5">
                <span className="material-symbols-outlined text-[#00D26A] text-base">call</span>
                <span className="text-white font-bold text-base">1900 6868</span>
              </li>
              <li className="flex items-center gap-2.5">
                <span className="material-symbols-outlined text-[#00D26A] text-base">mail</span>
                <span>support@dsportnexus.vn</span>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <div className="bg-[#07261d] border-t border-emerald-900/60 py-4">
        <div className="max-w-[1400px] mx-auto px-4 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-3 text-xs font-medium text-emerald-200/60">
          <p>© 2024 DSPORT NEXUS. Bảo lưu mọi quyền.</p>
          <div className="flex items-center gap-4">
            <span>Đại lý ủy quyền chính hãng</span>
            <span>|</span>
            <span className="text-[#00D26A] font-bold">100% Phân Phối BWF</span>
          </div>
        </div>
      </div>
    </footer>
  );
};

export default Footer;

"use client";

import React from 'react';
import Link from 'next/link';

export interface BreadcrumbItem {
  label: string;
  href?: string;
}

interface BreadcrumbProps {
  items: BreadcrumbItem[];
}

export default function Breadcrumb({ items }: BreadcrumbProps) {
  return (
    <nav className="flex items-center text-xs text-slate-500 py-3 overflow-x-auto scrollbar-none">
      <Link href="/" className="hover:text-[#13634B] flex items-center gap-1 transition">
        <span className="material-symbols-outlined text-sm">home</span>
        <span>Trang chủ</span>
      </Link>
      {items.map((item, idx) => (
        <React.Fragment key={idx}>
          <span className="mx-2 text-slate-300">/</span>
          {item.href ? (
            <Link href={item.href} className="hover:text-[#13634B] transition truncate max-w-[200px]">
              {item.label}
            </Link>
          ) : (
            <span className="text-slate-800 font-bold truncate max-w-[250px]">{item.label}</span>
          )}
        </React.Fragment>
      ))}
    </nav>
  );
}

"use client";

import React, { useState, useEffect } from 'react';
import Link from 'next/link';

interface HeroSlide {
  id: number;
  tagline: string;
  title: string;
  highlight: string;
  desc: string;
  ctaText: string;
  ctaHref: string;
  secondaryText: string;
  secondaryHref: string;
  image: string;
  badge: string;
}

const SLIDES: HeroSlide[] = [
  {
    id: 1,
    tagline: 'DSPORT NEXUS • PRO TOURNAMENT SERIES',
    title: 'YONEX NANOFLARE 1000Z',
    highlight: 'KỶ LỤC TỐC ĐỘ ĐẬP CẦU THẾ GIỚI',
    desc: 'Siêu phẩm tốc độ đỉnh cao của Yonex với công nghệ Sonic Flare System và Ultra PE Fiber, mang lại những cú phản tạt và smash chớp nhoáng với độ chính xác tuyệt đối.',
    ctaText: 'Khám phá ngay',
    ctaHref: '/products/1',
    secondaryText: 'Xem tất cả vợt',
    secondaryHref: '/products?category_id=5',
    image: 'http://localhost:8088/storage/uploads/products/48ToPynsIcEpyKMBJDbsMNfmfAMRI0Os3Zqqgc1g.jpg',
    badge: 'Chính Hãng Yonex',
  },
  {
    id: 2,
    tagline: 'DSPORT NEXUS • OLYMPIC EQUIPMENT',
    title: 'LI-NING HALBERTEC 8000',
    highlight: 'KIỂM SOÁT TỐC ĐỘ VÙNG CẦU',
    desc: 'Vũ khí thi đấu của huyền thoại Yuta Watanabe. Đột phá với sợi carbon hàng không M50 gia tăng độ đàn hồi đũa vợt, kiểm soát cầu thanh thoát và sắc bén.',
    ctaText: 'Xem Halbertec 8000',
    ctaHref: '/products/6',
    secondaryText: 'Vợt Li-Ning',
    secondaryHref: '/products?keyword=Lining',
    image: 'http://localhost:8088/storage/uploads/products/8HgybePEusOltW8V0BVcJYarj6vbZaLOKaKwO4h2.jpg',
    badge: 'Đại Lý Cấp 1',
  },
  {
    id: 3,
    tagline: 'DSPORT NEXUS • FOOTWEAR TECH',
    title: 'YONEX POWER CUSHION 65Z3',
    highlight: 'BẢO VỆ CỔ CHÂN & BÁM SÂN',
    desc: 'Được tin dùng bởi các tay vợt số 1 thế giới như Kento Momota. Đệm Power Cushion+ hấp thụ chấn động và hoàn trả lực bật nhảy linh hoạt.',
    ctaText: 'Chọn giày thi đấu',
    ctaHref: '/products/9',
    secondaryText: 'Tất cả giày',
    secondaryHref: '/products?category_id=2',
    image: 'http://localhost:8088/storage/uploads/products/YW2RlauMjywh1wlv9PB0uIZ7l2NS5KCW7rDlJ5h2.jpg',
    badge: 'Chống Lật 100%',
  },
];

export default function Hero() {
  const [activeIdx, setActiveIdx] = useState(0);
  const [isAutoPlaying, setIsAutoPlaying] = useState(true);

  useEffect(() => {
    if (!isAutoPlaying) return;
    const interval = setInterval(() => {
      setActiveIdx((prev) => (prev + 1) % SLIDES.length);
    }, 5500);
    return () => clearInterval(interval);
  }, [isAutoPlaying]);

  const slide = SLIDES[activeIdx];

  const handleNext = () => {
    setIsAutoPlaying(false);
    setActiveIdx((prev) => (prev + 1) % SLIDES.length);
  };

  const handlePrev = () => {
    setIsAutoPlaying(false);
    setActiveIdx((prev) => (prev - 1 + SLIDES.length) % SLIDES.length);
  };

  return (
    <section
      className="relative w-full bg-[#0a3629] text-white overflow-hidden"
      onMouseEnter={() => setIsAutoPlaying(false)}
      onMouseLeave={() => setIsAutoPlaying(true)}
    >
      {/* Background Subtle Ambient Lighting */}
      <div className="absolute inset-0 bg-gradient-to-r from-[#07241b] via-[#0a3629] to-[#13634B]/80 z-0"></div>

      <div className="relative max-w-[1400px] mx-auto px-4 lg:px-8 py-12 lg:py-20 z-10">
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
          {/* Left: Headline & Actions */}
          <div className="lg:col-span-7 space-y-6">
            <div className="inline-flex items-center gap-2 px-3 py-1 bg-white/10 border border-white/20 text-[#00D26A] rounded-full text-xs font-black tracking-wider uppercase backdrop-blur-xs">
              <span className="w-2 h-2 rounded-full bg-[#00D26A] animate-pulse"></span>
              {slide.tagline}
            </div>

            <div>
              <div className="text-xs sm:text-sm font-black text-emerald-300 uppercase tracking-widest mb-1.5">
                {slide.title}
              </div>
              <h1 className="text-3xl sm:text-5xl lg:text-6xl font-black uppercase text-white tracking-tight leading-[1.08]">
                {slide.highlight}
              </h1>
            </div>

            <p className="text-xs sm:text-sm text-emerald-100/80 leading-relaxed max-w-xl font-normal">
              {slide.desc}
            </p>

            <div className="flex flex-wrap items-center gap-3.5 pt-2">
              <Link
                href={slide.ctaHref}
                className="h-12 px-7 bg-[#00D26A] hover:bg-[#00ba5e] text-slate-950 text-xs sm:text-sm font-black uppercase tracking-wider rounded-xl transition shadow-lg shadow-[#00D26A]/20 flex items-center gap-2 group cursor-pointer"
              >
                <span>{slide.ctaText}</span>
                <span className="material-symbols-outlined text-base group-hover:translate-x-1 transition-transform">
                  arrow_forward
                </span>
              </Link>

              <Link
                href={slide.secondaryHref}
                className="h-12 px-6 rounded-xl border border-white/30 hover:border-white hover:bg-white/10 text-white text-xs sm:text-sm font-bold uppercase tracking-wider transition flex items-center gap-2 cursor-pointer"
              >
                <span>{slide.secondaryText}</span>
              </Link>
            </div>
          </div>

          {/* Right: Clean Hero Image Visual */}
          <div className="lg:col-span-5 flex justify-center">
            <div className="relative w-full max-w-[480px] aspect-[4/3] rounded-3xl overflow-hidden shadow-2xl border border-white/20 group">
              {/* eslint-disable-next-line @next/next/no-img-element */}
              <img
                src={slide.image}
                alt={slide.title}
                className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
              />
              <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

              <div className="absolute top-4 left-4 bg-slate-950/70 backdrop-blur-md border border-white/20 text-white text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-xl">
                {slide.badge}
              </div>

              <div className="absolute bottom-4 inset-x-4 flex items-center justify-between text-white">
                <span className="text-xs font-black uppercase tracking-wider text-emerald-300">
                  {slide.title}
                </span>
                <span className="text-[11px] text-white/80 font-medium">
                  Phân phối chính hãng DSPORT
                </span>
              </div>
            </div>
          </div>
        </div>

        {/* Slide Controls & Dots */}
        <div className="flex items-center justify-between pt-8 mt-4 border-t border-white/10">
          <div className="flex items-center gap-2">
            {SLIDES.map((s, idx) => (
              <button
                key={s.id}
                onClick={() => {
                  setIsAutoPlaying(false);
                  setActiveIdx(idx);
                }}
                className={`transition-all duration-300 cursor-pointer flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs ${
                  activeIdx === idx
                    ? 'bg-white/20 text-white font-black border border-white/30'
                    : 'text-white/60 hover:text-white hover:bg-white/10'
                }`}
              >
                <span className="font-mono">0{idx + 1}</span>
                <span className="hidden sm:inline font-bold uppercase">{s.title.split(' ')[0]}</span>
              </button>
            ))}
          </div>

          <div className="flex items-center gap-2">
            <button
              onClick={handlePrev}
              title="Slide trước"
              className="w-9 h-9 rounded-xl bg-white/10 hover:bg-white/25 border border-white/20 flex items-center justify-center text-white transition cursor-pointer"
            >
              <span className="material-symbols-outlined text-base">chevron_left</span>
            </button>
            <button
              onClick={handleNext}
              title="Slide tiếp theo"
              className="w-9 h-9 rounded-xl bg-white/10 hover:bg-white/25 border border-white/20 flex items-center justify-center text-white transition cursor-pointer"
            >
              <span className="material-symbols-outlined text-base">chevron_right</span>
            </button>
          </div>
        </div>
      </div>
    </section>
  );
}

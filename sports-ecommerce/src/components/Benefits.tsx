"use client";

import React from 'react';

const Benefits: React.FC = () => {
  const commitments = [
    {
      num: '01',
      title: 'CĂNG VỢT 4 NÚT CHUẨN BWF',
      desc: 'Hệ thống máy đan điện tử sai số < 0.1kg. Kỹ thuật viên có chứng chỉ Yonex Stringing Team, miễn phí công đan trọn đời.',
    },
    {
      num: '02',
      title: '100% CHÍNH HÃNG SUNRISE',
      desc: 'Tem cào chống hàng giả, quét mã QR kích hoạt bảo hành điện tử chính hãng từ Yonex, Lining và Victor.',
    },
    {
      num: '03',
      title: 'BẢO HÀNH GÃY VỢT 90 NGÀY',
      desc: 'Chính sách 1 đổi 1 áp dụng cho mọi lỗi kỹ thuật do nhà sản xuất, yên tâm bung sức trong mọi trận cầu đỉnh cao.',
    },
    {
      num: '04',
      title: '58 SHOWROOM & TEST SÂN',
      desc: 'Trải nghiệm đánh thử vợt trực tiếp tại sân cầu lông của showroom trước khi quyết định mua. Giao hàng hỏa tốc 2h.',
    },
  ];

  return (
    <section className="max-w-[1400px] mx-auto px-4 lg:px-8 -mt-6 relative z-20">
      <div className="bg-white rounded-2xl shadow-xl shadow-slate-200/60 border border-slate-200/90 p-6 sm:p-8">
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8 divide-y sm:divide-y-0 sm:divide-x divide-slate-100">
          {commitments.map((item, idx) => (
            <div key={idx} className={`${idx !== 0 ? 'sm:pl-6 lg:pl-8 pt-4 sm:pt-0' : ''} space-y-2`}>
              <div className="flex items-center gap-2">
                <span className="text-xs font-mono font-black text-[#13634B] bg-[#13634B]/10 px-2 py-0.5 rounded">
                  {item.num}
                </span>
                <h3 className="font-black text-xs uppercase tracking-tight text-slate-900 leading-snug">
                  {item.title}
                </h3>
              </div>
              <p className="text-[11px] text-slate-500 leading-relaxed font-normal">
                {item.desc}
              </p>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
};

export default Benefits;

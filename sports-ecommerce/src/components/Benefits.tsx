"use client";

const Benefits: React.FC = () => {
  const items = [
    { icon: 'local_shipping', title: 'Vận chuyển siêu tốc', desc: 'Giao hàng 2h nội thành', color: 'bg-red-50', text: 'text-primary' },
    { icon: 'verified', title: 'Chất lượng đảm bảo', desc: '100% Chính hãng', color: 'bg-orange-50', text: 'text-accent' },
    { icon: 'credit_card', title: 'Thanh toán an toàn', desc: 'Đa dạng hình thức', color: 'bg-red-50', text: 'text-primary' },
    { icon: 'published_with_changes', title: 'Đổi trả dễ dàng', desc: 'Trong vòng 15 ngày', color: 'bg-orange-50', text: 'text-accent' },
  ];

  return (
    <section className="bg-white dark:bg-[#1e0e0e] border-b border-gray-100 dark:border-[#2a1515] shadow-sm relative z-20 -mt-8 mx-4 lg:mx-8 rounded-xl">
      <div className="px-6 py-8">
        <div className="grid grid-cols-2 md:grid-cols-4 gap-8">
          {items.map((item, idx) => (
            <div key={idx} className="flex items-center gap-4 group">
              <div className={`w-14 h-14 rounded-full ${item.color} dark:bg-[#3d1a1a] flex items-center justify-center ${item.text} group-hover:bg-primary group-hover:text-white transition-all duration-300 shadow-sm`}>
                <span className="material-symbols-outlined text-[32px]">{item.icon}</span>
              </div>
              <div>
                <h3 className="font-bold text-sm text-[#1c0d0d] dark:text-white uppercase tracking-tight">{item.title}</h3>
                <p className="text-xs text-[#666] dark:text-[#999] mt-1">{item.desc}</p>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
};

export default Benefits;

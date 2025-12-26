"use client";

const Footer: React.FC = () => {
  return (
    <footer className="bg-[#111] text-white pt-20 mt-auto border-t-4 border-primary">
      <div className="max-w-[1400px] mx-auto px-4 lg:px-8 pb-12">
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12">
          <div className="flex flex-col gap-6">
            <div className="flex items-center gap-2">
              <div className="w-10 h-10 text-primary">
                <svg className="w-full h-full" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                  <path d="M42.4379 44C42.4379 44 36.0744 33.9038 41.1692 24C46.8624 12.9336 42.2078 4 42.2078 4L7.01134 4C7.01134 4 11.6577 12.932 5.96912 23.9969C0.876273 33.9029 7.27094 44 7.27094 44L42.4379 44Z" fill="currentColor"></path>
                </svg>
              </div>
              <h2 className="text-3xl font-black uppercase tracking-tighter italic">SportsShop</h2>
            </div>
            <p className="text-gray-400 text-sm leading-relaxed">
              Đơn vị hàng đầu Việt Nam cung cấp dụng cụ thể thao chính hãng. Cam kết chất lượng, bảo hành uy tín.
            </p>
            <div className="flex gap-4">
              {['public', 'share', 'mail'].map((icon) => (
                <a key={icon} className="w-10 h-10 rounded-full bg-[#222] flex items-center justify-center hover:bg-primary transition-all hover:scale-110" href="#">
                  <span className="material-symbols-outlined text-sm">{icon}</span>
                </a>
              ))}
            </div>
          </div>

          <div className="flex flex-col gap-5">
            <h3 className="text-lg font-bold uppercase mb-2 text-white border-l-4 border-primary pl-3">Thông Tin</h3>
            <ul className="flex flex-col gap-3 text-sm text-gray-400">
              {['Giới thiệu', 'Hệ thống cửa hàng', 'Tuyển dụng', 'Tin tức & Sự kiện'].map(item => (
                <li key={item}><a className="hover:text-primary transition-colors flex items-center gap-2" href="#"><span className="w-1 h-1 bg-gray-500 rounded-full"></span> {item}</a></li>
              ))}
            </ul>
          </div>

          <div className="flex flex-col gap-5">
            <h3 className="text-lg font-bold uppercase mb-2 text-white border-l-4 border-primary pl-3">Hỗ Trợ</h3>
            <ul className="flex flex-col gap-3 text-sm text-gray-400">
              {['Hướng dẫn mua hàng', 'Chính sách đổi trả', 'Chính sách bảo hành', 'Tra cứu đơn hàng'].map(item => (
                <li key={item}><a className="hover:text-primary transition-colors flex items-center gap-2" href="#"><span className="w-1 h-1 bg-gray-500 rounded-full"></span> {item}</a></li>
              ))}
            </ul>
          </div>

          <div className="flex flex-col gap-5">
            <h3 className="text-lg font-bold uppercase mb-2 text-white border-l-4 border-primary pl-3">Liên Hệ</h3>
            <ul className="flex flex-col gap-4 text-sm text-gray-400">
              <li className="flex items-start gap-3">
                <span className="material-symbols-outlined text-primary text-xl mt-0.5">location_on</span>
                <span>Số 123, Đường Nguyễn Trãi, Quận Thanh Xuân, TP. Hà Nội</span>
              </li>
              <li className="flex items-center gap-3">
                <span className="material-symbols-outlined text-primary text-xl">call</span>
                <span className="text-white font-bold text-lg">1900 6868</span>
              </li>
              <li className="flex items-center gap-3">
                <span className="material-symbols-outlined text-primary text-xl">mail</span>
                <span>support@sportsshop.vn</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div className="bg-primary py-4">
        <div className="max-w-[1400px] mx-auto px-4 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-3 text-xs font-medium text-white/90">
          <p>© 2024 Công ty Cổ phần SportsShop Việt Nam.</p>
          <div className="flex items-center gap-6">
            <span>MST: 0101234567</span>
            <span className="hidden sm:inline">|</span>
            <div className="bg-white text-primary px-3 py-1 rounded font-bold text-[10px] uppercase shadow-sm">
              Đã thông báo BCT
            </div>
          </div>
        </div>
      </div>
    </footer>
  );
};

export default Footer;

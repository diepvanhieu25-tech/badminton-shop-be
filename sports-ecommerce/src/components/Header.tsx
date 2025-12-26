"use client";
const Header: React.FC = () => {
  return (
    <header className="w-full bg-white dark:bg-[#1e0e0e] shadow-lg shadow-gray-100/50 dark:shadow-none sticky top-0 z-50">
      {/* Top Bar */}
      <div className="w-full bg-white border-b border-gray-100 dark:bg-[#2a1515] dark:border-[#3d2020]">
        <div className="max-w-[1400px] mx-auto px-4 lg:px-8 py-1.5 flex justify-between items-center text-xs sm:text-sm font-medium">
          <div className="flex items-center gap-6 text-[#555] dark:text-[#ccc]">
            <span className="flex items-center gap-1.5 hover:text-primary transition-colors cursor-pointer group">
              <span className="material-symbols-outlined text-[18px] text-primary group-hover:scale-110 transition-transform">phone_in_talk</span>
              Hotline: <span className="font-bold">1900 6868</span>
            </span>
            <span className="hidden sm:inline w-[1px] h-3 bg-gray-300 dark:bg-[#444]"></span>
            <span className="flex items-center gap-1.5 hover:text-primary transition-colors cursor-pointer group">
              <span className="material-symbols-outlined text-[18px] text-primary group-hover:scale-110 transition-transform">storefront</span>
              Hệ thống 58 cửa hàng
            </span>
          </div>
          <div className="flex items-center gap-4 text-[#555] dark:text-[#ccc]">
            <a className="hover:text-primary transition-colors" href="#">Tra cứu đơn hàng</a>
            <span className="w-[1px] h-3 bg-gray-300 dark:bg-[#444]"></span>
            <a className="hover:text-primary transition-colors" href="#">Đăng nhập</a>
          </div>
        </div>
      </div>

      {/* Main Header */}
      <div className="max-w-[1400px] mx-auto px-4 lg:px-8 py-4 flex flex-col md:flex-row items-center gap-4 md:gap-8">
        <a className="flex items-center gap-2 group flex-shrink-0" href="#">
          <div className="w-12 h-12 text-primary">
            <svg className="w-full h-full drop-shadow-sm" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
              <path d="M42.4379 44C42.4379 44 36.0744 33.9038 41.1692 24C46.8624 12.9336 42.2078 4 42.2078 4L7.01134 4C7.01134 4 11.6577 12.932 5.96912 23.9969C0.876273 33.9029 7.27094 44 7.27094 44L42.4379 44Z" fill="currentColor"></path>
            </svg>
          </div>
          <div className="flex flex-col">
            <h1 className="text-3xl font-black tracking-tighter leading-none text-primary uppercase italic">SportsShop</h1>
            <span className="text-[10px] font-bold tracking-[0.2em] text-[#333] dark:text-[#aaa] uppercase pl-0.5">Pro Performance</span>
          </div>
        </a>

        <div className="flex-1 w-full max-w-[700px]">
          <div className="flex w-full items-stretch rounded-full border-2 border-gray-100 dark:border-[#3d2020] bg-gray-50 dark:bg-[#2a1515] focus-within:border-primary focus-within:bg-white dark:focus-within:bg-black transition-all overflow-hidden h-12 shadow-inner">
            <input 
              className="flex-1 bg-transparent border-none focus:ring-0 px-6 text-[#1c0d0d] dark:text-white placeholder:text-gray-400 text-sm font-medium" 
              placeholder="Tìm vợt cầu lông, giày tennis, phụ kiện pickleball..."
            />
            <button className="bg-primary text-white px-6 flex items-center justify-center hover:bg-primary-dark transition-colors group">
              <span className="material-symbols-outlined group-hover:scale-110 transition-transform">search</span>
            </button>
          </div>
        </div>

        <div className="flex items-center gap-4 flex-shrink-0">
          <button className="flex flex-col items-center gap-1 group relative">
            <div className="p-2.5 rounded-full bg-gray-50 dark:bg-[#2a1515] group-hover:bg-primary group-hover:text-white transition-all duration-300 text-[#333] dark:text-white shadow-sm">
              <span className="material-symbols-outlined">person</span>
            </div>
            <span className="text-[10px] font-bold hidden lg:block uppercase tracking-wide group-hover:text-primary transition-colors">Tài khoản</span>
          </button>
          <button className="flex flex-col items-center gap-1 group relative">
            <div className="relative p-2.5 rounded-full bg-gray-50 dark:bg-[#2a1515] group-hover:bg-primary group-hover:text-white transition-all duration-300 text-[#333] dark:text-white shadow-sm">
              <span className="material-symbols-outlined">shopping_cart</span>
              <span className="absolute -top-1 -right-1 bg-accent text-white text-[10px] font-bold h-5 w-5 rounded-full flex items-center justify-center border-2 border-white dark:border-[#1e0e0e] shadow-sm">3</span>
            </div>
            <span className="text-[10px] font-bold hidden lg:block uppercase tracking-wide group-hover:text-primary transition-colors">Giỏ hàng</span>
          </button>
        </div>
      </div>

      {/* Nav Menu */}
      <nav className="w-full bg-primary text-white hidden md:block border-t border-red-800 shadow-md">
        <div className="max-w-[1400px] mx-auto px-4 lg:px-8">
          <ul className="flex items-center justify-between gap-1 text-sm font-bold uppercase tracking-wide">
            <li><a className="block px-6 py-3.5 bg-red-900 border-b-4 border-accent" href="#">Trang chủ</a></li>
            <li className="flex-1 text-center"><a className="block py-3.5 hover:bg-red-700 transition-colors hover:text-yellow-200" href="#">Vợt Cầu Lông</a></li>
            <li className="flex-1 text-center"><a className="block py-3.5 hover:bg-red-700 transition-colors hover:text-yellow-200" href="#">Giày Thể Thao</a></li>
            <li className="flex-1 text-center">
              <a className="block py-3.5 hover:bg-red-700 transition-colors hover:text-yellow-200 relative group" href="#">
                Pickleball
                <span className="absolute top-1 right-2 text-[8px] bg-yellow-400 text-red-900 px-1 rounded">HOT</span>
              </a>
            </li>
            <li className="flex-1 text-center"><a className="block py-3.5 hover:bg-red-700 transition-colors hover:text-yellow-200" href="#">Tennis</a></li>
            <li className="flex-1 text-center"><a className="block py-3.5 hover:bg-red-700 transition-colors hover:text-yellow-200" href="#">Phụ kiện</a></li>
            <li className="flex-1 text-center"><a className="block py-3.5 hover:bg-red-700 transition-colors hover:text-yellow-200" href="#">Tin tức</a></li>
            <li className="flex-1 text-center"><a className="block py-3.5 hover:bg-red-700 transition-colors hover:text-yellow-200" href="#">Khuyến mãi</a></li>
          </ul>
        </div>
      </nav>
    </header>
  );
};

export default Header;

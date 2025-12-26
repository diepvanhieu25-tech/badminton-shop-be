"use client";
const Hero: React.FC = () => {
  return (
    <section className="w-full bg-[#111] overflow-hidden relative group perspective-1000">
      <div className="relative w-full h-[500px] md:h-[600px] flex items-center">
        <div className="absolute inset-0 z-0 overflow-hidden">
          <img 
            alt="Professional badminton match background" 
            className="w-full h-full object-cover object-center opacity-40 transition-transform duration-[2000ms] ease-out group-hover:scale-110 group-hover:rotate-1" 
            src="https://lh3.googleusercontent.com/aida-public/AB6AXuA2BYEo0TwPrBi6Va_GOXyL5LRBV3c5uUA_ZAeYT5q1m0jf7q1mOrGjqgwHUwzmm6UPLi_c3Yb6vqYn0jiSeja7plqn_mBF82ikyY8-EnBBosml_tsHDyzJkVSE1zlXF-sPqLMKjF5GOj0MqOZQlBShFmCDMDh7PeA8Rp0l4GkfIk7gNnGMRE5yA1QAXGUUPbol-k1Y0-Qcd-jrfFos4YA88ofcfJLNIt8dnf37x0_P5SZswPDyMfKS6QTQ4BaYRSKvOwceJIqexQ" 
          />
          <div className="absolute inset-0 bg-gradient-to-r from-black via-black/60 to-transparent"></div>
        </div>
        
        <div className="max-w-[1400px] mx-auto px-4 lg:px-8 w-full z-20 relative flex flex-col justify-center h-full">
          <div className="max-w-[800px] transform transition-transform duration-700 ease-out group-hover:translate-x-4 group-hover:-translate-y-2">
            <div className="inline-block mb-4 overflow-hidden rounded-r-full">
              <div className="bg-gradient-to-r from-primary to-orange-600 text-white font-bold text-sm md:text-base px-6 py-2 uppercase tracking-widest shadow-lg transform -skew-x-12 origin-left">
                <span className="inline-block skew-x-12">Bộ sưu tập mới 2024</span>
              </div>
            </div>
            
            <h2 className="text-5xl md:text-7xl font-black text-white uppercase leading-[0.9] mb-6 drop-shadow-xl">
              <span className="block text-transparent bg-clip-text bg-gradient-to-br from-white to-gray-400">Chinh phục</span>
              <span className="block text-primary stroke-white" style={{ WebkitTextStroke: '1px rgba(255,255,255,0.1)' }}>Mọi Thử Thách</span>
            </h2>
            
            <p className="text-lg text-gray-300 mb-10 max-w-[600px] font-medium leading-relaxed border-l-4 border-primary pl-6">
              Trải nghiệm công nghệ đỉnh cao từ Yonex, Lining, Victor. <br/>
              Nâng tầm lối chơi của bạn ngay hôm nay.
            </p>
            
            <div className="flex flex-wrap gap-4">
              <button className="h-14 px-10 bg-primary hover:bg-white hover:text-primary text-white text-lg font-bold rounded-full transition-all shadow-[0_10px_30px_rgba(211,47,47,0.4)] flex items-center gap-3 transform hover:-translate-y-1">
                MUA NGAY
                <span className="material-symbols-outlined">arrow_forward</span>
              </button>
              <button className="h-14 px-10 bg-transparent border-2 border-white/30 text-white text-lg font-bold rounded-full hover:bg-white/10 hover:border-white transition-all backdrop-blur-sm flex items-center gap-2">
                XEM BỘ SƯU TẬP
              </button>
            </div>
          </div>
        </div>

        <div className="absolute right-0 bottom-0 z-10 hidden lg:block h-full w-1/2 pointer-events-none">
          <img 
            alt="Overlay effect" 
            className="h-full w-full object-contain object-bottom transform transition-transform duration-[1500ms] ease-out group-hover:scale-105 group-hover:-translate-x-4 opacity-80 mix-blend-overlay" 
            src="https://lh3.googleusercontent.com/aida-public/AB6AXuAYXLS-jFnYbLatr6PMGRoWa13QUgehkjGyFU_F4n7IdyV-SVJcXrcht2BTqiDTuvUBeiV-TBrJ93weK3_rjQfoDh1vwhH2TLlkLJs6w7nLpE0ffYIHsJo3GXFOES-hdfPjstuXoVYGLHFOxZW23feeJgwdUIsNxe0b03HPlXildGEZE4i6zDKX2foT9JmZY6AHy_b77k6xDDeiGJgFc9EFCu_GqJR--qotOZGjMcgNHNLYTVDqx6l0tuUaJojLIdfXNOgCUIrWsA" 
          />
        </div>
      </div>
    </section>
  );
};

export default Hero;

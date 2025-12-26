"use client";
import { CategoryItem } from '@/types/types';

interface CategoryGridProps {
  title: string;
  icon: string;
  items: CategoryItem[];
  themeColor: string;
}

const CategoryGrid: React.FC<CategoryGridProps> = ({ title, icon, items, themeColor }) => {
  return (
    <section className="py-12 bg-white dark:bg-[#221010]">
      <div className="max-w-[1400px] mx-auto px-4 lg:px-8">
        <div className="flex items-center justify-between mb-8 pb-4 border-b-2 border-gray-100 dark:border-[#333]">
          <div className="flex items-center gap-3">
            <div className={`w-10 h-10 ${themeColor} rounded text-white flex items-center justify-center`}>
              <span className="material-symbols-outlined">{icon}</span>
            </div>
            <h2 className="text-2xl md:text-3xl font-black uppercase text-[#1c0d0d] dark:text-white">{title}</h2>
          </div>
          <a className="text-sm font-bold bg-gray-100 px-4 py-2 rounded-full hover:bg-primary hover:text-white transition-all flex items-center gap-2" href="#">
            Xem tất cả <span className="material-symbols-outlined text-sm">arrow_forward</span>
          </a>
        </div>

        <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
          {items.map((item) => (
            <a key={item.id} className="relative h-60 rounded-xl overflow-hidden group shadow-lg" href="#">
              <img 
                alt={item.title} 
                className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" 
                src={item.image} 
              />
              <div className="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent opacity-90"></div>
              {item.badge && (
                <div className="absolute top-4 -right-8 w-32 bg-primary text-white text-[10px] font-bold py-1 text-center rotate-45 shadow-md border-2 border-white">
                  {item.badge}
                </div>
              )}
              <div className="absolute bottom-4 left-4 right-4">
                <p className="text-white text-lg font-black uppercase leading-tight group-hover:text-yellow-300 transition-colors">{item.title}</p>
                <span className="text-xs text-gray-300 font-medium">{item.subtitle}</span>
              </div>
            </a>
          ))}
        </div>
      </div>
    </section>
  );
};

export default CategoryGrid;

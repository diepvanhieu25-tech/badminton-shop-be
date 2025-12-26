"use client";
import { NEWS } from '@/data/constants';

const NewsSection: React.FC = () => {
  return (
    <section className="py-16 bg-gray-50 dark:bg-[#1a1a1a]">
      <div className="max-w-[1400px] mx-auto px-4 lg:px-8">
        <div className="text-center mb-12">
          <h2 className="text-3xl font-black uppercase text-[#1c0d0d] dark:text-white">Tin Tức Mới</h2>
          <div className="w-16 h-1 bg-primary mx-auto rounded-full mt-3"></div>
        </div>
        
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
          {NEWS.map((article) => (
            <article key={article.id} className="flex flex-col group cursor-pointer bg-white dark:bg-[#221010] rounded-lg overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border-b-4 border-transparent hover:border-primary">
              <div className="overflow-hidden h-48 relative">
                <img alt={article.title} className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" src={article.image} />
                {article.tag && <div className="absolute top-2 left-2 bg-primary text-white text-[10px] font-bold px-2 py-1 rounded">{article.tag}</div>}
              </div>
              <div className="p-4 flex flex-col flex-1">
                <div className="text-xs text-[#888] font-bold mb-2 uppercase flex items-center gap-1">
                  <span className="material-symbols-outlined text-sm">calendar_month</span> {article.date}
                </div>
                <h3 className="text-base font-bold text-[#1c0d0d] dark:text-white leading-snug line-clamp-2 group-hover:text-primary transition-colors mb-2">
                  {article.title}
                </h3>
                <p className="text-sm text-gray-500 line-clamp-2 mt-auto">{article.summary}</p>
              </div>
            </article>
          ))}
        </div>
      </div>
    </section>
  );
};

export default NewsSection;

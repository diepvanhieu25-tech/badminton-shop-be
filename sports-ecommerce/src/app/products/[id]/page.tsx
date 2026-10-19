"use client";

import React, { useEffect, useState, use } from 'react';
import Link from 'next/link';
import { useRouter } from 'next/navigation';
import Header from '@/components/Header';
import Footer from '@/components/Footer';
import Breadcrumb from '@/components/Breadcrumb';
import ProductCard from '@/components/ProductCard';
import * as api from '@/services/api';
import { ApiProductDetail, ApiProductVariant, ApiProduct } from '@/types/api';
import { useCart } from '@/context/CartContext';

interface ProductDetailPageProps {
  params: Promise<{ id: string }>;
}

export default function ProductDetailPage({ params }: ProductDetailPageProps) {
  const router = useRouter();
  const { id } = use(params);
  const { addItem, openMiniCart } = useCart();

  const [product, setProduct] = useState<ApiProductDetail | null>(null);
  const [selectedVariant, setSelectedVariant] = useState<ApiProductVariant | null>(null);
  const [quantity, setQuantity] = useState(1);
  const [activeTab, setActiveTab] = useState<'desc' | 'specs' | 'warranty'>('desc');
  const [relatedProducts, setRelatedProducts] = useState<ApiProduct[]>([]);

  // Stringing service state (for rackets)
  const [selectedString, setSelectedString] = useState<{
    id: string;
    name: string;
    price: number;
    thickness: string;
    tag: string;
    badge?: string;
  }>({
    id: 'none',
    name: 'Khung mộc (Chưa căng cước)',
    price: 0,
    thickness: '-',
    tag: 'Khung nguyên bản theo hãng, chưa bao gồm cước',
    badge: 'Khuyên dùng khi có cước riêng',
  });
  const [selectedTension, setSelectedTension] = useState<string>('10.5 kg (23 lbs) - Chuẩn phong trào');
  const [customTension, setCustomTension] = useState<string>('');
  const [showSizeModal, setShowSizeModal] = useState<boolean>(false);

  // UI state
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [isAdding, setIsAdding] = useState(false);
  const [toastMessage, setToastMessage] = useState<string | null>(null);

  useEffect(() => {
    setIsLoading(true);
    setError(null);
    setQuantity(1);

    api.getProductDetail(id)
      .then(async (data) => {
        setProduct(data);
        if (data.variants && data.variants.length > 0) {
          setSelectedVariant(data.variants[0]);
        }

        // Handle related products
        let related = data.related_products || [];
        if (related.length < 4 && data.category?.id) {
          try {
            const extraRes = await api.getProducts({
              category_id: data.category.id,
              limit: 6,
            });
            const filteredExtra = (extraRes.data || []).filter(
              (p) => String(p.id) !== String(id) && !related.some((r) => r.id === p.id)
            );
            related = [...related, ...filteredExtra].slice(0, 4);
          } catch {
            // Keep existing related
          }
        }
        setRelatedProducts(related);
      })
      .catch((err) => {
        setError(err instanceof Error ? err.message : 'Không tìm thấy sản phẩm');
      })
      .finally(() => {
        setIsLoading(false);
      });
  }, [id]);

  const formatVnd = (amount: number) => {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
  };

  const getAttrValue = (v: ApiProductVariant, code: string): string | null => {
    if (!v.attributes) return null;
    if (Array.isArray(v.attributes)) {
      const item = v.attributes.find((a) => a.code.toLowerCase() === code.toLowerCase());
      return item ? item.value : null;
    }
    return (v.attributes as Record<string, string>)[code] || null;
  };

  const getVariantLabel = (v: ApiProductVariant): string => {
    if (!v.attributes) return v.sku;
    if (Array.isArray(v.attributes)) {
      return v.attributes.map((a) => `${a.name}: ${a.value}`).join(' | ');
    }
    return Object.entries(v.attributes)
      .map(([k, val]) => `${k.toUpperCase()}: ${val}`)
      .join(' | ');
  };

  // Stringing presets
  const STRING_OPTIONS = [
    {
      id: 'none',
      name: 'Khung mộc (Chưa căng)',
      price: 0,
      thickness: '-',
      tag: 'Khung nguyên bản theo hãng, chưa bao gồm cước',
      badge: 'Chưa đan cước',
    },
    {
      id: 'bg65',
      name: 'Yonex BG 65',
      price: 120000,
      thickness: '0.70mm',
      tag: 'Cước quốc dân siêu bền bỉ, trợ lực cao',
      badge: 'Bền nhất BWF',
    },
    {
      id: 'bg65ti',
      name: 'Yonex BG 65 Titanium',
      price: 140000,
      thickness: '0.70mm',
      tag: 'Phủ hợp chất Titanium cho tiếng nổ vang giòn và cảm giác đanh cầu',
      badge: 'Tiếng nổ đanh',
    },
    {
      id: 'bg80p',
      name: 'Yonex BG 80 Power',
      price: 180000,
      thickness: '0.68mm',
      tag: 'Sợi Vectran cao cấp, trợ lực smash cắm sân cực mạnh',
      badge: 'Công phá đỉnh cao',
    },
    {
      id: 'nanogy98',
      name: 'Yonex Nanogy 98',
      price: 170000,
      thickness: '0.66mm',
      tag: 'Lực nẩy cực cao, tiếp xúc cầu êm ái, kỹ thuật ve cầu tinh tế',
      badge: 'Kiểm soát & Nẩy',
    },
  ];

  const TENSION_PRESETS = [
    '10.0 kg (22 lbs)',
    '10.5 kg (23 lbs) - Chuẩn phong trào',
    '11.0 kg (24 lbs) - Bán chuyên',
    '11.5 kg (25 lbs) - Chuyên nghiệp',
    '12.0 kg (26 lbs) - Tay khỏe',
  ];

  const SHOE_SIZE_MAP: Record<string, string> = {
    '38': '24.0 cm',
    '39': '24.5 cm',
    '39.5': '24.75 cm',
    '40': '25.0 cm',
    '40.5': '25.25 cm',
    '41': '25.5 cm',
    '42': '26.0 cm',
    '42.5': '26.25 cm',
    '43': '26.5 cm',
    '44': '27.0 cm',
    '45': '27.5 cm',
  };

  const isRacket = Boolean(
    product &&
    (product.category?.name?.toLowerCase().includes('vợt') ||
     product.name?.toLowerCase().includes('vợt') ||
     product.variants?.some((v) => getAttrValue(v, 'weight') || getAttrValue(v, 'grip')))
  );

  const isShoe = Boolean(
    product &&
    (product.category?.name?.toLowerCase().includes('giày') ||
     product.name?.toLowerCase().includes('giày') ||
     product.variants?.some((v) => getAttrValue(v, 'size')))
  );

  const hasColor = Boolean(
    product && product.variants?.some((v) => getAttrValue(v, 'color'))
  );

  const basePrice = selectedVariant ? selectedVariant.price : (product?.price || 0);
  const stringPrice = (isRacket && selectedString && selectedString.id !== 'none') ? selectedString.price : 0;
  const currentPrice = basePrice + stringPrice;
  const currentOriginalPrice = selectedVariant?.original_price || product?.original_price;
  const currentStock = selectedVariant ? selectedVariant.stock_qty : 0;
  const isAvailable = currentStock > 0 && selectedVariant?.is_in_stock !== false;

  const handleAddToCart = async (goToCheckout = false) => {
    if (!selectedVariant || !product) return;
    setIsAdding(true);
    try {
      const attributesToSave: { code: string; name: string; value: string }[] = [];
      if (selectedVariant.attributes) {
        if (Array.isArray(selectedVariant.attributes)) {
          attributesToSave.push(...selectedVariant.attributes);
        } else {
          Object.entries(selectedVariant.attributes).forEach(([code, val]) => {
            attributesToSave.push({ code, name: code.toUpperCase(), value: String(val) });
          });
        }
      }

      if (isRacket && selectedString && selectedString.id !== 'none') {
        const finalTension = customTension.trim() || selectedTension;
        attributesToSave.push({
          code: 'stringing',
          name: 'Dịch vụ căng cước BWF',
          value: `${selectedString.name} (${finalTension})`,
        });
      }

      await addItem(selectedVariant.id, quantity, {
        name: product.name,
        price: currentPrice,
        sku: selectedVariant.sku,
        image_url: selectedVariant.image || product.thumbnail,
        attributes: attributesToSave,
      });

      if (goToCheckout) {
        router.push('/checkout');
      } else {
        openMiniCart();
        setToastMessage(`Đã thêm "${product.name}" (${getVariantLabel(selectedVariant)}) vào giỏ hàng!`);
        setTimeout(() => setToastMessage(null), 3000);
      }
    } catch (err: unknown) {
      alert(err instanceof Error ? err.message : 'Lỗi khi thêm vào giỏ');
    } finally {
      setIsAdding(false);
    }
  };

  return (
    <div className="flex flex-col min-h-screen bg-[#f8fafc] text-slate-900">
      <Header />

      {/* Floating Toast Notification */}
      {toastMessage && (
        <div className="fixed bottom-6 right-6 z-50 bg-[#0a3629] text-white px-5 py-3.5 rounded-2xl shadow-xl flex items-center gap-3 border border-emerald-800 animate-slideUp">
          <span className="material-symbols-outlined text-emerald-400">check_circle</span>
          <span className="text-xs font-bold">{toastMessage}</span>
          <button
            onClick={openMiniCart}
            className="ml-2 text-xs font-black underline text-emerald-300 cursor-pointer"
          >
            Xem giỏ hàng
          </button>
        </div>
      )}

      <main className="flex-grow max-w-[1400px] w-full mx-auto px-4 lg:px-8 py-6">
        {/* Breadcrumb */}
        {product && (
          <Breadcrumb
            items={[
              { label: 'Sản phẩm', href: '/products' },
              {
                label: product.category?.name || 'Danh mục',
                href: `/products?category_id=${product.category?.id}`,
              },
              { label: product.name },
            ]}
          />
        )}

        {isLoading ? (
          <div className="bg-white rounded-3xl border border-slate-200/90 p-16 flex flex-col items-center justify-center space-y-4 my-6">
            <div className="w-12 h-12 border-4 border-[#13634B] border-t-transparent rounded-full animate-spin"></div>
            <p className="text-xs font-bold text-slate-500">Đang tải chi tiết sản phẩm...</p>
          </div>
        ) : error || !product ? (
          <div className="bg-white rounded-3xl border border-slate-200/90 p-16 text-center space-y-4 my-6">
            <span className="material-symbols-outlined text-5xl text-rose-500">error</span>
            <h2 className="text-lg font-bold text-slate-900">{error || 'Sản phẩm không tồn tại'}</h2>
            <Link
              href="/products"
              className="inline-block px-6 py-2.5 rounded-xl bg-[#13634B] text-white text-xs font-bold hover:bg-[#0e4a38] transition"
            >
              Quay lại danh sách sản phẩm
            </Link>
          </div>
        ) : (
          <div className="space-y-12 my-4">
            {/* Main Product Showcase Box */}
            <div className="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-10 shadow-xs">
              <div className="grid grid-cols-1 lg:grid-cols-12 gap-10">
                {/* Left: Product Images (5 cols) */}
                <div className="lg:col-span-5 space-y-4">
                  <div className="relative aspect-square rounded-2xl bg-slate-50 border border-slate-200/80 p-8 flex items-center justify-center overflow-hidden group">
                    {product.thumbnail ? (
                      /* eslint-disable-next-line @next/next/no-img-element */
                      <img
                        src={product.thumbnail}
                        alt={product.name}
                        className="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500"
                      />
                    ) : (
                      <div className="flex flex-col items-center justify-center text-slate-300">
                        <span className="material-symbols-outlined text-9xl">sports_tennis</span>
                        <span className="text-xs font-bold uppercase tracking-widest mt-3">Chính hãng</span>
                      </div>
                    )}

                    {/* Brand Pill */}
                    <span className="absolute top-4 left-4 bg-[#0a3629] text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                      {product.brand?.name || 'Yonex'}
                    </span>

                    {/* Stock status pill */}
                    <div
                      className={`absolute bottom-4 left-4 flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold ${
                        isAvailable
                          ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                          : 'bg-rose-50 text-rose-700 border border-rose-200'
                      }`}
                    >
                      <span
                        className={`w-2 h-2 rounded-full ${
                          isAvailable ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500'
                        }`}
                      ></span>
                      {isAvailable ? `Còn hàng (${currentStock} cái)` : 'Tạm hết hàng'}
                    </div>
                  </div>

                  {/* Trust Features Bar */}
                  <div className="grid grid-cols-3 gap-3 p-4 rounded-2xl bg-slate-50/70 border border-slate-100 text-center text-xs">
                    <div className="flex flex-col items-center gap-1">
                      <span className="material-symbols-outlined text-[#13634B]">verified</span>
                      <span className="font-bold text-slate-800">100% Chính Hãng</span>
                      <span className="text-[10px] text-slate-400">Đại lý ủy quyền</span>
                    </div>
                    <div className="flex flex-col items-center gap-1">
                      <span className="material-symbols-outlined text-[#13634B]">tune</span>
                      <span className="font-bold text-slate-800">Căng 4 nút BWF</span>
                      <span className="text-[10px] text-slate-400">Máy điện tử</span>
                    </div>
                    <div className="flex flex-col items-center gap-1">
                      <span className="material-symbols-outlined text-[#13634B]">local_shipping</span>
                      <span className="font-bold text-slate-800">Giao nhanh 2h</span>
                      <span className="text-[10px] text-slate-400">Freeship từ 1tr</span>
                    </div>
                  </div>
                </div>

                {/* Right: Info, Price, Variants & CTA (7 cols) */}
                <div className="lg:col-span-7 flex flex-col justify-between space-y-6">
                  <div>
                    {/* Brand & Category tags */}
                    <div className="flex items-center gap-2 mb-2">
                      <span className="text-xs font-bold text-[#13634B] uppercase tracking-wider">
                        {product.brand?.name}
                      </span>
                      <span className="text-slate-300">•</span>
                      <span className="text-xs text-slate-500 font-medium">
                        {product.category?.name}
                      </span>
                      <span className="text-slate-300">•</span>
                      <span className="text-xs text-slate-400 font-mono">
                        SKU: {selectedVariant?.sku || product.sku}
                      </span>
                    </div>

                    <h1 className="text-2xl sm:text-3xl font-black text-slate-900 leading-tight mb-3">
                      {product.name}
                    </h1>

                    {/* Star Rating preview */}
                    <div className="flex items-center gap-2 mb-6">
                      <div className="flex text-amber-400 text-sm">
                        {'★★★★★'.split('').map((star, i) => (
                          <span key={i}>{star}</span>
                        ))}
                      </div>
                      <span className="text-xs font-bold text-slate-700">5.0</span>
                      <span className="text-xs text-slate-400">(48 đánh giá chính hãng)</span>
                    </div>

                    {/* Price Block with dynamic Breakdown */}
                    <div className="p-4 rounded-2xl bg-slate-50/80 border border-slate-200/80 mb-6">
                      <div className="flex items-baseline gap-4 flex-wrap">
                        <span className="text-3xl sm:text-4xl font-black text-[#13634B]">
                          {formatVnd(currentPrice)}
                        </span>
                        {currentOriginalPrice && currentOriginalPrice > currentPrice && (
                          <span className="text-base sm:text-lg text-slate-400 line-through font-medium">
                            {formatVnd(currentOriginalPrice)}
                          </span>
                        )}
                        {currentOriginalPrice && currentOriginalPrice > currentPrice && (
                          <span className="bg-amber-100 text-amber-800 text-xs font-black px-2.5 py-1 rounded-full">
                            Tiết kiệm {formatVnd(currentOriginalPrice - currentPrice)}
                          </span>
                        )}
                      </div>
                      {isRacket && selectedString && selectedString.id !== 'none' && (
                        <div className="mt-2.5 pt-2 border-t border-slate-200/70 flex items-center gap-2 text-xs text-slate-600">
                          <span className="material-symbols-outlined text-sm text-[#13634B]">receipt_long</span>
                          <span>
                            Khung vợt: <strong className="text-slate-800">{formatVnd(basePrice)}</strong> + Cước {selectedString.name}: <strong className="text-[#13634B]">+{formatVnd(stringPrice)}</strong>
                          </span>
                        </div>
                      )}
                    </div>

                    {/* Universal Pro Trust Strip */}
                    <div className="grid grid-cols-2 sm:grid-cols-4 gap-2.5 p-3.5 bg-slate-50 border border-slate-200/80 rounded-2xl mb-6 text-xs">
                      <div>
                        <span className="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Cam kết</span>
                        <span className="font-bold text-slate-800 block">100% Chính Hãng</span>
                      </div>
                      <div>
                        <span className="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Bảo hành</span>
                        <span className="font-bold text-[#13634B] block">1 Đổi 1 (90 ngày)</span>
                      </div>
                      <div>
                        <span className="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Giao hàng</span>
                        <span className="font-bold text-slate-800 block">Hỏa Tốc 2 Giờ</span>
                      </div>
                      <div>
                        <span className="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Hỗ trợ đổi trả</span>
                        <span className="font-bold text-slate-800 block">15 Ngày Linh Hoạt</span>
                      </div>
                    </div>

                    {/* Description preview */}
                    <p className="text-xs sm:text-sm text-slate-600 leading-relaxed mb-6">
                      {product.description ||
                        'Dòng sản phẩm thể thao thi đấu đỉnh cao được kiểm định chất lượng và phân phối chính hãng.'}
                    </p>

                    {/* SPECIALIZED VARIANT SELECTOR */}
                    {product.variants && product.variants.length > 0 && (
                      <div className="mb-6 space-y-5">
                        {/* CASE 1: BADMINTON RACKET (VỢT CẦU LÔNG) */}
                        {isRacket ? (
                          <div className="space-y-5">
                            {/* 1. Weight / Grip Selection */}
                            <div>
                              <div className="flex items-center justify-between mb-2.5">
                                <label className="text-xs font-bold uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                                  <span className="material-symbols-outlined text-base text-[#13634B]">fitness_center</span>
                                  1. Chọn Phiên Bản Trọng Lượng & Chu Vi Cán (Weight / Grip):
                                </label>
                                <span className="text-[11px] text-slate-400 font-medium">Chuẩn BWF</span>
                              </div>

                              <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                {product.variants.map((v) => {
                                  const isSelected = selectedVariant?.id === v.id;
                                  const weight = getAttrValue(v, 'weight') || '';
                                  const grip = getAttrValue(v, 'grip') || '';
                                  const label = weight && grip ? `${weight} - ${grip}` : getVariantLabel(v);

                                  let weightDesc = 'Trọng lượng tiêu chuẩn BWF, điểm cân bằng đồng đều';
                                  if (weight.toUpperCase() === '4U') {
                                    weightDesc = 'Thân vợt ~83g (± 2g) • Dễ thuần, công thủ toàn diện, phản tạt tốc độ';
                                  } else if (weight.toUpperCase() === '3U') {
                                    weightDesc = 'Thân vợt ~88g (± 2g) • Đầu vợt đầm chắc, smash cắm sân uy lực, cần cổ tay khỏe';
                                  } else if (weight.toUpperCase() === '5U') {
                                    weightDesc = 'Thân vợt ~78g (± 2g) • Siêu nhẹ, xoay trở linh hoạt trên lưới';
                                  }

                                  return (
                                    <div
                                      key={v.id}
                                      onClick={() => {
                                        setSelectedVariant(v);
                                        setQuantity(1);
                                      }}
                                      className={`p-3.5 rounded-2xl border-2 transition-all cursor-pointer relative flex flex-col justify-between ${
                                        isSelected
                                          ? 'border-[#13634B] bg-emerald-50/40 ring-1 ring-[#13634B]/20 shadow-xs'
                                          : 'border-slate-200 bg-white hover:border-[#13634B]/60 hover:bg-slate-50/50'
                                      }`}
                                    >
                                      <div>
                                        <div className="flex items-center justify-between mb-1.5">
                                          <span className={`text-sm font-black ${isSelected ? 'text-[#13634B]' : 'text-slate-900'}`}>
                                            {label}
                                          </span>
                                          {isSelected ? (
                                            <span className="w-5 h-5 rounded-full bg-[#13634B] text-white flex items-center justify-center text-xs">
                                              <span className="material-symbols-outlined text-[13px]">check</span>
                                            </span>
                                          ) : (
                                            <span className="text-[11px] font-mono text-slate-400">{v.sku}</span>
                                          )}
                                        </div>
                                        <p className="text-[11px] text-slate-500 leading-snug mb-2">
                                          {weightDesc}
                                        </p>
                                      </div>

                                      <div className="flex items-center justify-between pt-2 border-t border-slate-100 text-xs">
                                        <span className="font-black text-[#13634B]">{formatVnd(v.price)}</span>
                                        <span className={`text-[10px] font-bold ${v.stock_qty > 0 ? 'text-emerald-700' : 'text-rose-600'}`}>
                                          {v.stock_qty > 0 ? `Còn ${v.stock_qty} cây` : 'Tạm hết'}
                                        </span>
                                      </div>
                                    </div>
                                  );
                                })}
                              </div>
                            </div>

                            {/* 2. BWF Stringing Service Container */}
                            <div className="p-4 sm:p-5 rounded-2xl bg-emerald-50/40 border border-emerald-200/80 space-y-4">
                              <div className="flex items-start justify-between gap-2">
                                <div>
                                  <div className="flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-[#0a3629]">
                                    <span className="material-symbols-outlined text-base text-[#13634B]">tune</span>
                                    2. Dịch vụ Đan Cước Chuẩn BWF (Máy Điện Tử 4 Nút)
                                  </div>
                                  <p className="text-[11px] text-slate-600 mt-0.5">
                                    Đan máy điện tử đo lực chuẩn xác chống xô cước. Tặng kèm quấn cán cao cấp.
                                  </p>
                                </div>
                                <span className="text-[10px] font-bold text-emerald-800 bg-emerald-100/90 border border-emerald-300/60 px-2.5 py-1 rounded-full whitespace-nowrap">
                                  Miễn phí công đan
                                </span>
                              </div>

                              {/* String Options Grid */}
                              <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
                                {STRING_OPTIONS.map((st) => {
                                  const isSelected = selectedString?.id === st.id;
                                  return (
                                    <div
                                      key={st.id}
                                      onClick={() => setSelectedString(st)}
                                      className={`p-3 rounded-xl border transition-all cursor-pointer flex flex-col justify-between ${
                                        isSelected
                                          ? 'border-[#13634B] bg-white ring-2 ring-[#13634B]/20 shadow-xs'
                                          : 'border-slate-200/90 bg-white/80 hover:border-[#13634B]/60'
                                      }`}
                                    >
                                      <div>
                                        <div className="flex items-center justify-between mb-1">
                                          <span className={`text-xs font-bold leading-tight ${isSelected ? 'text-[#13634B]' : 'text-slate-800'}`}>
                                            {st.name}
                                          </span>
                                          {isSelected && (
                                            <span className="w-4 h-4 rounded-full bg-[#13634B] text-white flex items-center justify-center text-[10px]">
                                              ✓
                                            </span>
                                          )}
                                        </div>
                                        <p className="text-[10px] text-slate-500 leading-snug line-clamp-2">
                                          {st.tag}
                                        </p>
                                      </div>
                                      <div className="flex items-center justify-between mt-2 pt-1.5 border-t border-slate-100 text-[11px]">
                                        <span className="font-bold text-[#13634B]">
                                          {st.price === 0 ? 'Khung mộc' : `+${formatVnd(st.price)}`}
                                        </span>
                                        {st.badge && (
                                          <span className="text-[9px] font-bold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded">
                                            {st.badge}
                                          </span>
                                        )}
                                      </div>
                                    </div>
                                  );
                                })}
                              </div>

                              {/* Tension Selector if string is chosen */}
                              {selectedString && selectedString.id !== 'none' && (
                                <div className="pt-3 border-t border-emerald-200/60 space-y-2">
                                  <div className="flex items-center justify-between">
                                    <label className="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                      <span className="material-symbols-outlined text-sm text-[#13634B]">speed</span>
                                      Mức căng cước (Lực căng khuyến nghị):
                                    </label>
                                    <span className="text-[11px] text-slate-500 font-medium">
                                      Đang chọn: <strong className="text-[#13634B]">{customTension.trim() || selectedTension}</strong>
                                    </span>
                                  </div>

                                  <div className="flex flex-wrap gap-2">
                                    {TENSION_PRESETS.map((tension) => {
                                      const isTensionSelected = selectedTension === tension && !customTension;
                                      return (
                                        <button
                                          key={tension}
                                          type="button"
                                          onClick={() => {
                                            setSelectedTension(tension);
                                            setCustomTension('');
                                          }}
                                          className={`px-3 py-1.5 rounded-lg text-xs font-bold transition-all border cursor-pointer ${
                                            isTensionSelected
                                              ? 'bg-[#13634B] text-white border-[#13634B] shadow-xs'
                                              : 'bg-white text-slate-700 border-slate-200 hover:border-[#13634B]'
                                          }`}
                                        >
                                          {tension}
                                        </button>
                                      );
                                    })}
                                  </div>

                                  {/* Custom tension input */}
                                  <div className="flex items-center gap-2 pt-1">
                                    <input
                                      type="text"
                                      value={customTension}
                                      onChange={(e) => setCustomTension(e.target.value)}
                                      placeholder="Hoặc nhập số kg khác (Ví dụ: 10.8 kg, 11.2 kg)..."
                                      className="flex-1 max-w-sm px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs text-slate-800 placeholder:text-slate-400 focus:outline-none focus:border-[#13634B]"
                                    />
                                  </div>
                                </div>
                              )}
                            </div>
                          </div>
                        ) : isShoe ? (
                          /* CASE 2: BADMINTON SHOES (GIÀY CẦU LÔNG) */
                          <div>
                            <div className="flex items-center justify-between mb-2.5">
                              <label className="text-xs font-bold uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                                <span className="material-symbols-outlined text-base text-[#13634B]">steps</span>
                                Chọn Size Giày Thi Đấu (Chuẩn EUR):
                              </label>
                              <button
                                type="button"
                                onClick={() => setShowSizeModal(true)}
                                className="text-xs font-bold text-[#13634B] hover:text-[#0a3629] hover:underline flex items-center gap-1 cursor-pointer"
                              >
                                <span className="material-symbols-outlined text-sm">straighten</span>
                                Bảng quy đổi Size & Hướng dẫn đo chân
                              </button>
                            </div>

                            <div className="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2.5">
                              {product.variants.map((v) => {
                                const isSelected = selectedVariant?.id === v.id;
                                const sizeVal = getAttrValue(v, 'size') || getVariantLabel(v);
                                const footCm = SHOE_SIZE_MAP[sizeVal] || 'Chuẩn';
                                const isOutOfStock = v.stock_qty <= 0;

                                return (
                                  <button
                                    key={v.id}
                                    type="button"
                                    disabled={isOutOfStock}
                                    onClick={() => {
                                      setSelectedVariant(v);
                                      setQuantity(1);
                                    }}
                                    className={`p-3 rounded-2xl border text-center transition-all cursor-pointer flex flex-col items-center justify-center gap-0.5 ${
                                      isSelected
                                        ? 'bg-[#13634B] text-white border-[#13634B] shadow-md shadow-[#13634B]/20 ring-2 ring-[#13634B]/20'
                                        : isOutOfStock
                                        ? 'bg-slate-100 text-slate-400 border-slate-200 opacity-60 cursor-not-allowed line-through'
                                        : 'bg-white border-slate-200 text-slate-800 hover:border-[#13634B]'
                                    }`}
                                  >
                                    <span className="text-base font-black leading-tight">
                                      {sizeVal}
                                    </span>
                                    <span className={`text-[10px] font-medium ${isSelected ? 'text-emerald-100' : 'text-slate-400'}`}>
                                      {footCm}
                                    </span>
                                    <span className={`text-[9px] font-bold mt-1 px-1.5 py-0.5 rounded-full ${
                                      isSelected
                                        ? 'bg-emerald-900/40 text-emerald-100'
                                        : isOutOfStock
                                        ? 'text-rose-500'
                                        : 'text-emerald-700 bg-emerald-50'
                                    }`}>
                                      {isOutOfStock ? 'Hết hàng' : `Còn ${v.stock_qty}`}
                                    </span>
                                  </button>
                                );
                              })}
                            </div>
                          </div>
                        ) : hasColor ? (
                          /* CASE 3: COLOR SELECTOR (BALO, QUẦN ÁO, PHỤ KIỆN) */
                          <div>
                            <label className="block text-xs font-bold uppercase tracking-wider text-slate-800 mb-2.5">
                              Chọn Màu Sắc / Phiên Bản:
                            </label>
                            <div className="flex flex-wrap gap-2.5">
                              {product.variants.map((v) => {
                                const isSelected = selectedVariant?.id === v.id;
                                const colorVal = getAttrValue(v, 'color') || getVariantLabel(v);

                                return (
                                  <button
                                    key={v.id}
                                    type="button"
                                    onClick={() => {
                                      setSelectedVariant(v);
                                      setQuantity(1);
                                    }}
                                    className={`px-4 py-2.5 rounded-xl text-xs font-bold transition-all border cursor-pointer flex items-center gap-2 ${
                                      isSelected
                                        ? 'bg-[#13634B] text-white border-[#13634B] shadow-sm shadow-[#13634B]/20 ring-2 ring-[#13634B]/15'
                                        : 'bg-white border-slate-200 text-slate-700 hover:border-[#13634B]'
                                    }`}
                                  >
                                    <span className="w-2.5 h-2.5 rounded-full bg-slate-400"></span>
                                    <span>{colorVal}</span>
                                    <span className={`text-[10px] font-normal ${isSelected ? 'text-emerald-200' : 'text-slate-400'}`}>
                                      (Còn {v.stock_qty})
                                    </span>
                                  </button>
                                );
                              })}
                            </div>
                          </div>
                        ) : (
                          /* CASE 4: GENERIC VARIANT SELECTOR */
                          <div>
                            <label className="block text-xs font-bold uppercase tracking-wider text-slate-800 mb-2.5">
                              Chọn Phiên Bản Sản Phẩm:
                            </label>
                            <div className="flex flex-wrap gap-2.5">
                              {product.variants.map((v) => {
                                const isSelected = selectedVariant?.id === v.id;
                                return (
                                  <button
                                    key={v.id}
                                    type="button"
                                    onClick={() => {
                                      setSelectedVariant(v);
                                      setQuantity(1);
                                    }}
                                    className={`px-4 py-2.5 rounded-xl text-xs font-bold transition-all border cursor-pointer ${
                                      isSelected
                                        ? 'bg-[#13634B] text-white border-[#13634B] shadow-sm shadow-[#13634B]/20 ring-2 ring-[#13634B]/15'
                                        : 'bg-white border-slate-200 text-slate-700 hover:border-[#13634B]'
                                    }`}
                                  >
                                    {getVariantLabel(v)} • {formatVnd(v.price)}
                                  </button>
                                );
                              })}
                            </div>
                          </div>
                        )}
                      </div>
                    )}

                    {/* Quantity Picker */}
                    <div className="mb-6">
                      <label className="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Số lượng mua:
                      </label>
                      <div className="flex items-center gap-3">
                        <div className="flex items-center rounded-xl border border-slate-200 bg-slate-50 p-1">
                          <button
                            type="button"
                            onClick={() => setQuantity(Math.max(1, quantity - 1))}
                            disabled={quantity <= 1}
                            className="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-slate-600 hover:bg-white transition disabled:opacity-30 cursor-pointer"
                          >
                            -
                          </button>
                          <span className="w-12 text-center text-sm font-black text-slate-900">
                            {quantity}
                          </span>
                          <button
                            type="button"
                            onClick={() => setQuantity(Math.min(currentStock, quantity + 1))}
                            disabled={quantity >= currentStock}
                            className="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-slate-600 hover:bg-white transition disabled:opacity-30 cursor-pointer"
                          >
                            +
                          </button>
                        </div>
                        <span className="text-xs text-slate-400">
                          Kho còn: <strong>{currentStock}</strong> sản phẩm
                        </span>
                      </div>
                    </div>
                  </div>

                  {/* Primary CTA Buttons */}
                  <div className="pt-6 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <button
                      type="button"
                      onClick={() => handleAddToCart(false)}
                      disabled={!isAvailable || isAdding}
                      className="py-4 px-6 rounded-xl border-2 border-[#13634B] text-[#13634B] hover:bg-[#13634B]/5 font-bold text-xs sm:text-sm uppercase tracking-wider transition flex items-center justify-center gap-2 disabled:opacity-50 cursor-pointer"
                    >
                      <span className="material-symbols-outlined text-lg">add_shopping_cart</span>
                      {isAdding ? 'Đang thêm...' : 'Thêm vào giỏ'}
                    </button>

                    <button
                      type="button"
                      onClick={() => handleAddToCart(true)}
                      disabled={!isAvailable || isAdding}
                      className="py-4 px-6 rounded-xl bg-[#13634B] hover:bg-[#0e4a38] text-white font-bold text-xs sm:text-sm uppercase tracking-wider transition shadow-md shadow-[#13634B]/20 flex items-center justify-center gap-2 disabled:opacity-50 cursor-pointer"
                    >
                      <span className="material-symbols-outlined text-lg">bolt</span>
                      Mua ngay
                    </button>
                  </div>
                </div>
              </div>
            </div>

            {/* Product Tabs (Description / Specs / Warranty) */}
            <div className="bg-white rounded-3xl border border-slate-200/90 overflow-hidden shadow-2xs">
              <div className="flex border-b border-slate-200 bg-slate-50/70 text-xs font-bold uppercase tracking-wider">
                <button
                  onClick={() => setActiveTab('desc')}
                  className={`px-6 py-4 transition cursor-pointer ${
                    activeTab === 'desc'
                      ? 'border-b-2 border-[#13634B] text-[#13634B] bg-white'
                      : 'text-slate-500 hover:text-slate-900'
                  }`}
                >
                  Mô tả chi tiết
                </button>
                <button
                  onClick={() => setActiveTab('specs')}
                  className={`px-6 py-4 transition cursor-pointer ${
                    activeTab === 'specs'
                      ? 'border-b-2 border-[#13634B] text-[#13634B] bg-white'
                      : 'text-slate-500 hover:text-slate-900'
                  }`}
                >
                  Thông số kỹ thuật
                </button>
                <button
                  onClick={() => setActiveTab('warranty')}
                  className={`px-6 py-4 transition cursor-pointer ${
                    activeTab === 'warranty'
                      ? 'border-b-2 border-[#13634B] text-[#13634B] bg-white'
                      : 'text-slate-500 hover:text-slate-900'
                  }`}
                >
                  Chính sách bảo hành & Đổi trả
                </button>
              </div>

              <div className="p-8 text-xs sm:text-sm text-slate-700 leading-relaxed">
                {activeTab === 'desc' && (
                  <div className="space-y-4">
                    <h3 className="text-base font-bold text-slate-900">{product.name}</h3>
                    <p>{product.description}</p>
                    <p>
                      Được thiết kế dựa trên tiêu chuẩn thi đấu quốc tế của Liên đoàn Cầu lông Thế giới (BWF), sản phẩm mang lại khả năng tối ưu hóa tốc độ vung vợt, cảm giác đập cầu chính xác và kiểm soát sân đấu tối ưu.
                    </p>
                  </div>
                )}

                {activeTab === 'specs' && (
                  <div className="max-w-xl">
                    <table className="w-full text-left border-collapse">
                      <tbody>
                        <tr className="border-b border-slate-100">
                          <td className="py-2.5 font-bold text-slate-500 w-1/3">Thương hiệu:</td>
                          <td className="py-2.5 text-slate-900 font-semibold">{product.brand?.name}</td>
                        </tr>
                        <tr className="border-b border-slate-100">
                          <td className="py-2.5 font-bold text-slate-500">Phân loại:</td>
                          <td className="py-2.5 text-slate-900 font-semibold">{product.category?.name}</td>
                        </tr>
                        <tr className="border-b border-slate-100">
                          <td className="py-2.5 font-bold text-slate-500">Mã SKU:</td>
                          <td className="py-2.5 text-slate-900 font-mono">{selectedVariant?.sku || product.sku}</td>
                        </tr>
                        <tr className="border-b border-slate-100">
                          <td className="py-2.5 font-bold text-slate-500">Trọng lượng / Cán:</td>
                          <td className="py-2.5 text-slate-900 font-semibold">
                            {selectedVariant ? getVariantLabel(selectedVariant) : 'Tiêu chuẩn'}
                          </td>
                        </tr>
                        <tr className="border-b border-slate-100">
                          <td className="py-2.5 font-bold text-slate-500">Xuất xứ:</td>
                          <td className="py-2.5 text-slate-900 font-semibold">Chính hãng 100%</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                )}

                {activeTab === 'warranty' && (
                  <div className="space-y-3">
                    <p>• <strong>Bảo hành gãy vợt:</strong> 1 đổi 1 trong vòng 90 ngày nếu do lỗi của nhà sản xuất.</p>
                    <p>• <strong>Dịch vụ đan vợt:</strong> Đan miễn phí tiền công trọn đời sản phẩm tại toàn bộ 58 showroom trên toàn quốc.</p>
                    <p>• <strong>Đổi hàng:</strong> Hỗ trợ đổi size giày hoặc đổi mẫu trong 15 ngày với điều kiện sản phẩm còn nguyên tem mác.</p>
                  </div>
                )}
              </div>
            </div>

            {/* Related Products Section */}
            {relatedProducts.length > 0 && (
              <section className="space-y-6 pt-4">
                <div className="flex items-end justify-between">
                  <div>
                    <div className="flex items-center gap-2 text-[#13634B] text-xs font-bold uppercase tracking-wider mb-1">
                      <span className="w-2.5 h-2.5 rounded-full bg-[#13634B]"></span>
                      Gợi Ý Cho Bạn
                    </div>
                    <h2 className="text-2xl sm:text-3xl font-black uppercase text-slate-900 tracking-tight">
                      SẢN PHẨM TƯƠNG TỰ CÙNG PHÂN KHÚC
                    </h2>
                    <p className="text-xs text-slate-500 font-medium mt-1">
                      Các dòng sản phẩm cùng thương hiệu và phân khúc được các vận động viên lựa chọn nhiều nhất.
                    </p>
                  </div>

                  <Link
                    href={`/products?category_id=${product.category?.id}`}
                    className="text-xs font-bold text-[#13634B] hover:underline flex items-center gap-1 hidden sm:flex"
                  >
                    Xem thêm {product.category?.name}
                    <span className="material-symbols-outlined text-sm">arrow_forward</span>
                  </Link>
                </div>

                <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                  {relatedProducts.map((rp) => (
                    <ProductCard
                      key={rp.id}
                      product={rp}
                      onOpenMiniCart={openMiniCart}
                    />
                  ))}
                </div>
              </section>
            )}
          </div>
        )}
      </main>

      {/* Interactive Shoe Size Guide Modal */}
      {showSizeModal && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs animate-fadeIn">
          <div className="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-slate-100 p-6 sm:p-8 space-y-6">
            {/* Header */}
            <div className="flex items-center justify-between pb-4 border-b border-slate-100">
              <div className="flex items-center gap-2.5">
                <div className="w-10 h-10 rounded-2xl bg-emerald-50 text-[#13634B] flex items-center justify-center">
                  <span className="material-symbols-outlined text-2xl">straighten</span>
                </div>
                <div>
                  <h3 className="text-base font-black text-slate-900 uppercase tracking-tight">
                    Bảng Quy Đổi Size Giày & Hướng Dẫn Đo Chân
                  </h3>
                  <p className="text-xs text-slate-400">Tiêu chuẩn giày thi đấu Yonex, Lining, Victor</p>
                </div>
              </div>
              <button
                onClick={() => setShowSizeModal(false)}
                className="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition cursor-pointer"
              >
                <span className="material-symbols-outlined text-lg">close</span>
              </button>
            </div>

            {/* 3 Steps Guide */}
            <div className="space-y-3">
              <h4 className="text-xs font-bold uppercase tracking-wider text-slate-800">
                3 Bước Tự Đo Chiều Dài Bàn Chân Tại Nhà:
              </h4>
              <div className="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div className="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs space-y-1.5">
                  <span className="w-6 h-6 rounded-full bg-[#13634B] text-white flex items-center justify-center font-bold text-xs mb-1">1</span>
                  <strong className="block text-slate-800 font-bold">Đặt Gót Chân</strong>
                  <p className="text-slate-500 text-[11px] leading-relaxed">
                    Đặt tờ giấy A4 dưới sàn, đặt gót chân chạm mép bờ tường thẳng đứng vuông góc 90°.
                  </p>
                </div>
                <div className="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs space-y-1.5">
                  <span className="w-6 h-6 rounded-full bg-[#13634B] text-white flex items-center justify-center font-bold text-xs mb-1">2</span>
                  <strong className="block text-slate-800 font-bold">Vạch Điểm Dài Nhất</strong>
                  <p className="text-slate-500 text-[11px] leading-relaxed">
                    Dùng bút chì dựng thẳng vạch dấu điểm đầu ngón chân dài nhất (ngón cái hoặc ngón trỏ).
                  </p>
                </div>
                <div className="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs space-y-1.5">
                  <span className="w-6 h-6 rounded-full bg-[#13634B] text-white flex items-center justify-center font-bold text-xs mb-1">3</span>
                  <strong className="block text-slate-800 font-bold">Đo Chiều Dài (cm)</strong>
                  <p className="text-slate-500 text-[11px] leading-relaxed">
                    Dùng thước đo khoảng cách từ mép gót chân đến vạch ngón chân vừa đánh dấu.
                  </p>
                </div>
              </div>
            </div>

            {/* Pro Advice Alert */}
            <div className="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-950 flex items-start gap-3">
              <span className="material-symbols-outlined text-emerald-700 text-xl shrink-0 mt-0.5">tips_and_updates</span>
              <div>
                <strong className="font-bold text-[#0a3629] block mb-1">
                  Lưu Ý Sống Còn Khi Chọn Giày Cầu Lông:
                </strong>
                <p className="leading-relaxed text-emerald-900/90 text-[11px]">
                  Khác với giày đi bộ hoặc chạy bộ, khi thi đấu cầu lông bạn thường xuyên thực hiện các bước di chuyển dậm nhảy và phanh gấp (Lunge). Vì vậy, hãy luôn <strong>cộng thêm 0.5cm - 1.0cm</strong> vào chiều dài bàn chân thực tế để mang tất dày và tạo khoảng trống tránh tức móng chân. (Ví dụ: Chiều dài bàn chân 25.0cm ➔ Chọn size 41 - 25.5cm).
                </p>
              </div>
            </div>

            {/* Conversion Table */}
            <div>
              <h4 className="text-xs font-bold uppercase tracking-wider text-slate-800 mb-2">
                Bảng Quy Đổi Size Giày Tiêu Chuẩn:
              </h4>
              <div className="overflow-x-auto rounded-2xl border border-slate-200">
                <table className="w-full text-center text-xs border-collapse">
                  <thead>
                    <tr className="bg-slate-50 text-slate-600 font-bold border-b border-slate-200">
                      <th className="py-2.5 px-3">Size EUR</th>
                      <th className="py-2.5 px-3 text-[#13634B]">Chiều dài chân (cm)</th>
                      <th className="py-2.5 px-3">Size US Men</th>
                      <th className="py-2.5 px-3">Size UK</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100 font-medium text-slate-700">
                    <tr><td className="py-2 px-3 font-bold">38</td><td className="py-2 px-3 text-[#13634B] font-bold">24.0 cm</td><td className="py-2 px-3">6.0</td><td className="py-2 px-3">5.5</td></tr>
                    <tr className="bg-slate-50/50"><td className="py-2 px-3 font-bold">39</td><td className="py-2 px-3 text-[#13634B] font-bold">24.5 cm</td><td className="py-2 px-3">6.5</td><td className="py-2 px-3">6.0</td></tr>
                    <tr><td className="py-2 px-3 font-bold">40</td><td className="py-2 px-3 text-[#13634B] font-bold">25.0 cm</td><td className="py-2 px-3">7.0</td><td className="py-2 px-3">6.5</td></tr>
                    <tr className="bg-emerald-50/40"><td className="py-2 px-3 font-bold text-[#13634B]">41</td><td className="py-2 px-3 text-[#13634B] font-bold">25.5 cm</td><td className="py-2 px-3">8.0</td><td className="py-2 px-3">7.5</td></tr>
                    <tr><td className="py-2 px-3 font-bold">42</td><td className="py-2 px-3 text-[#13634B] font-bold">26.0 cm</td><td className="py-2 px-3">8.5</td><td className="py-2 px-3">8.0</td></tr>
                    <tr className="bg-slate-50/50"><td className="py-2 px-3 font-bold">43</td><td className="py-2 px-3 text-[#13634B] font-bold">26.5 cm</td><td className="py-2 px-3">9.5</td><td className="py-2 px-3">9.0</td></tr>
                    <tr><td className="py-2 px-3 font-bold">44</td><td className="py-2 px-3 text-[#13634B] font-bold">27.0 cm</td><td className="py-2 px-3">10.0</td><td className="py-2 px-3">9.5</td></tr>
                    <tr className="bg-slate-50/50"><td className="py-2 px-3 font-bold">45</td><td className="py-2 px-3 text-[#13634B] font-bold">27.5 cm</td><td className="py-2 px-3">10.5</td><td className="py-2 px-3">10.0</td></tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div className="pt-2 flex justify-end">
              <button
                onClick={() => setShowSizeModal(false)}
                className="px-6 py-2.5 rounded-xl bg-[#13634B] hover:bg-[#0e4a38] text-white text-xs font-bold uppercase tracking-wider transition cursor-pointer"
              >
                Đã hiểu & Quay lại chọn size
              </button>
            </div>
          </div>
        </div>
      )}

      <Footer />
    </div>
  );
}

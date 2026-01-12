{{-- resources/views/admin/dashboard/partials/top-products.blade.php --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden h-fit">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">Sản phẩm bán chạy</h3>
        @if(Route::has('admin.products.index'))
            <a href="{{ route('admin.products.index') }}" class="text-xs text-emerald-600 hover:underline font-medium">Xem tất cả</a>
        @endif
    </div>
    <div class="p-4 space-y-4">
        @if(isset($topProducts) && count($topProducts) > 0)
            @foreach($topProducts as $product)
            <div class="flex items-center gap-4 group">
                <div class="w-12 h-12 rounded-lg bg-slate-100 flex-shrink-0 overflow-hidden border border-slate-100">
                    @if($product->thumbnail)
                        <img src="{{ Storage::url($product->thumbnail) }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                            <i class="fa-solid fa-image"></i>
                        </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-slate-800 truncate" title="{{ $product->name }}">{{ $product->name }}</p>
                    <p class="text-xs text-slate-500">{{ number_format($product->price) }}₫</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold text-slate-800">{{ $product->total_sold ?? 0 }}</p>
                    <p class="text-[10px] text-slate-400">Đã bán</p>
                </div>
            </div>
            @endforeach
        @else
            <div class="text-center py-8 text-slate-400 text-sm flex flex-col items-center">
                <i class="fa-solid fa-box-open text-2xl mb-2 opacity-50"></i>
                Chưa có dữ liệu bán hàng
            </div>
        @endif
    </div>
</div>
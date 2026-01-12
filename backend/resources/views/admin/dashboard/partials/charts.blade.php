{{-- resources/views/admin/dashboard/partials/charts.blade.php --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-slate-800">Biểu đồ doanh thu</h3>
            <select class="text-xs border-slate-200 rounded-lg text-slate-600 focus:ring-emerald-500 cursor-pointer">
                <option>7 ngày qua</option>
                {{-- <option>Tháng này</option> --}}
            </select>
        </div>
        <div class="h-80 w-full">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col justify-between">
        <h3 class="text-lg font-bold text-slate-800 mb-6">Tỷ lệ đơn hàng</h3>
        <div class="h-64 flex justify-center items-center">
            <canvas id="orderStatusChart"></canvas>
        </div>
        <div class="mt-4 grid grid-cols-2 gap-2 text-xs text-slate-600">
            <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-blue-500"></span> Chờ xử lý</div>
            <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-purple-500"></span> Đang xử lý</div>
            <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-yellow-500"></span> Đang giao</div>
            <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Hoàn thành</div>
            <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-red-500"></span> Đã hủy</div>
        </div>
    </div>
</div>
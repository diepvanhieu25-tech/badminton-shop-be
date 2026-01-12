{{-- resources/views/admin/dashboard/partials/recent-orders.blade.php --}}
<div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden h-fit">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">Đơn hàng gần đây</h3>
        @if(Route::has('admin.orders.index'))
            <a href="{{ route('admin.orders.index') }}" class="text-xs text-blue-600 hover:underline font-medium">Quản lý đơn hàng</a>
        @endif
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4">Mã đơn</th>
                    <th class="px-6 py-4">Khách hàng</th>
                    <th class="px-6 py-4">Tổng tiền</th>
                    <th class="px-6 py-4">Trạng thái</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($recentOrders as $order)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 font-medium text-emerald-700 whitespace-nowrap">
                            @if(Route::has('admin.orders.show'))
                                <a href="{{ route('admin.orders.show', $order) }}">#{{ $order->code }}</a>
                            @else
                                #{{ $order->code }}
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-slate-800 font-medium">{{ $order->receiver_name }}</div>
                            <div class="text-xs text-slate-500">{{ $order->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-800 whitespace-nowrap">
                            {{ number_format($order->total, 0, ',', '.') }}₫
                        </td>
                        <td class="px-6 py-4">
                            @php
                                // Map màu sắc trạng thái
                                $colors = [
                                    'pending' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'processing' => 'bg-purple-50 text-purple-700 border-purple-200',
                                    'shipping' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                    'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                                    'returned' => 'bg-gray-50 text-gray-700 border-gray-200',
                                ];
                                $val = is_object($order->status) ? $order->status->value : $order->status;
                                $label = is_object($order->status) ? $order->status->label() : ucfirst($val);
                                $class = $colors[$val] ?? 'bg-slate-50 text-slate-700';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $class }}">
                                {{ $label }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-500">Chưa có đơn hàng nào</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
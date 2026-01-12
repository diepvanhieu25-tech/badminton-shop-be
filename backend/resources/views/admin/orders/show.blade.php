@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng #' . $order->code)
@section('page_title', 'Chi tiết đơn hàng')

@section('content')

    {{-- 1. HEADER --}}
    @include('admin.orders.partials.header')

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- === LEFT COLUMN (MAIN CONTENT) === --}}
        <div class="lg:col-span-8 space-y-6">

            {{-- 2. DANH SÁCH SẢN PHẨM & TỔNG TIỀN --}}
            @include('admin.orders.partials.items-table')

            {{-- 3. THANH TOÁN & VẬN CHUYỂN --}}
            @include('admin.orders.partials.info-cards')

        </div>

        {{-- === RIGHT COLUMN (SIDEBAR) === --}}
        <div class="lg:col-span-4 space-y-6">

            {{-- 4. FORM CẬP NHẬT TRẠNG THÁI --}}
            @include('admin.orders.partials.status-form')

            {{-- 5. THÔNG TIN KHÁCH HÀNG --}}
            @include('admin.orders.partials.customer-info')

            {{-- 6. GHI CHÚ --}}
            @include('admin.orders.partials.note')

        </div>
    </div>

    {{-- ================================================= --}}
    {{-- THÊM MODAL TẠO VẬN ĐƠN Ở CUỐI FILE (NGOÀI GRID) --}}
    {{-- ================================================= --}}
    <div id="shipping-modal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        {{-- Backdrop tối màu --}}
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeShippingModal()"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">

                {{-- Form submit về route admin.orders.ship --}}
                <form action="{{ route('admin.orders.ship', $order) }}" method="POST">
                    @csrf

                    <div class="px-4 py-5 sm:p-6 text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 mb-4">
                            <i class="fa-solid fa-cloud-arrow-up text-blue-600"></i>
                        </div>
                        <h3 class="text-base font-semibold leading-6 text-slate-900">Đẩy đơn sang Viettel Post?</h3>
                        <p class="mt-2 text-sm text-slate-500">
                            Hệ thống sẽ tự động gửi thông tin đơn hàng này sang Viettel Post và nhận về Mã vận đơn.
                        </p>

                        {{-- Hiển thị thông tin tóm tắt --}}
                        <div class="mt-4 bg-slate-50 p-3 rounded-lg text-sm text-left border border-slate-200">
                            <p><strong>Khách hàng:</strong> {{ $order->receiver_name }}</p>
                            <p><strong>Thu hộ (COD):</strong>
                                {{ number_format($order->payment_status !== 'paid' ? $order->total : 0) }}₫</p>
                            <p><strong>Trọng lượng:</strong> ~1kg (Mặc định)</p>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="submit"
                            class="inline-flex w-full justify-center rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto transition">
                            <i class="fa-solid fa-paper-plane mr-2"></i> Xác nhận đẩy đơn
                        </button>
                        <button type="button" onclick="closeShippingModal()"
                            class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition">
                            Hủy
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openShippingModal() {
            document.getElementById('shipping-modal').classList.remove('hidden');
        }

        function closeShippingModal() {
            document.getElementById('shipping-modal').classList.add('hidden');
        }
    </script>
@endsection

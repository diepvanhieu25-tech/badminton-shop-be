<?php

namespace App\Services\Api;

use App\Enums\PaymentStatus;
use App\Repositories\Interfaces\Api\OrderRepositoryInterface;
use Exception;

class VnpayService
{
    public function __construct(
        protected OrderRepositoryInterface $orderRepo
    ) {}

    // 1. Helper: Hàm dùng chung để tạo chuỗi Query chuẩn VNPay
    private function buildQueryString(array $data): string
    {
        ksort($data);
        $query = "";
        $i = 0;
        foreach ($data as $key => $value) {
            if ($i == 1) {
                $query .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $query .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        return $query;
    }

    // 2. Tạo URL thanh toán
    public function createPaymentUrl(int $userId, string $orderCode)
    {
        $order = $this->orderRepo->findOrderByCode($userId, $orderCode);
        if (!$order) throw new Exception("Đơn hàng không tồn tại.", 404);

        if ($order->payment_status === PaymentStatus::PAID) {
            throw new Exception("Đơn hàng này đã được thanh toán.", 400);
        }

        $inputData = [
            "vnp_Version"    => "2.1.0",
            "vnp_TmnCode"    => env('VNP_TMN_CODE'),
            "vnp_Amount"     => $order->total * 100,
            "vnp_Command"    => "pay",
            "vnp_CreateDate" => date("YmdHis"),
            "vnp_CurrCode"   => "VND",
            "vnp_IpAddr"     => request()->ip(),
            "vnp_Locale"     => "vn",
            "vnp_OrderInfo"  => "Thanh toan don hang #" . $order->code,
            "vnp_OrderType"  => "billpayment",
            "vnp_ReturnUrl"  => env('VNP_RETURN_URL'),
            "vnp_TxnRef"     => $order->code,
        ];

        // Sử dụng hàm helper đã tách
        $query = $this->buildQueryString($inputData);

        $vnp_Url = env('VNP_URL') . "?" . $query;
        $vnp_HashSecret = env('VNP_HASH_SECRET');

        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $query, $vnp_HashSecret);
            $vnp_Url .= '&vnp_SecureHash=' . $vnpSecureHash;
        }

        return $vnp_Url;
    }

    // 3. Xử lý Callback 
    public function handlePaymentCallback(array $vnpayData)
    {
        $vnp_SecureHash = $vnpayData['vnp_SecureHash'] ?? '';

        // Lọc bỏ vnp_SecureHash và vnp_SecureHashType để tính toán lại
        $inputData = [];
        foreach ($vnpayData as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        unset($inputData['vnp_SecureHash']);

        // Tái tạo chuỗi hash bằng hàm helper
        $hashData = $this->buildQueryString($inputData);

        $secureHash = hash_hmac('sha512', $hashData, env('VNP_HASH_SECRET'));

        // Kiểm tra chữ ký
        if ($secureHash !== $vnp_SecureHash) {
            return [
                'success' => false,
                'message' => 'Chữ ký không hợp lệ (Invalid Signature)'
            ];
        }

        // Logic xử lý kết quả
        $orderCode = $inputData['vnp_TxnRef'];
        $amount = $inputData['vnp_Amount'] / 100;
        $responseCode = $inputData['vnp_ResponseCode'];

        // Logic riêng: Kiểm tra xem đơn đã Paid chưa để tránh update lại (Idempotency)
        $order = $this->orderRepo->findByCode($orderCode);

        if (!$order) {
            return [
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng (Order Not Found)',
                'order_code' => $orderCode
            ];
        }

        // Kiểm tra trạng thái hiện tại (Idempotency)
        if ($order->payment_status === PaymentStatus::SUCCESS->value || $order->payment_status === 'paid') {
            return [
                'success'    => true,
                'message'    => 'Giao dịch đã được thanh toán trước đó.',
                'order_code' => $orderCode
            ];
        }

        // 3. Kiểm tra số tiền (Security Check)
        if ((int)$order->total != (int)$amount) {
            return [
                'success' => false,
                'message' => 'Số tiền thanh toán không khớp với đơn hàng (Sai lệch dữ liệu)',
                'order_code' => $orderCode
            ];
        }

        if ($responseCode == "00") {
            $status = PaymentStatus::SUCCESS->value;
            $message = "Giao dịch thành công";
        } else {
            $status = PaymentStatus::FAILED->value;
            $message = "Giao dịch thất bại hoặc bị hủy";
        }

        // Update DB
        $this->orderRepo->updatePaymentStatus($orderCode, [
            'transaction_code' => $inputData['vnp_TransactionNo'] ?? null,
            'amount'           => $amount,
            'raw_data'         => $vnpayData
        ], $status);

        return [
            'success'    => $responseCode == "00",
            'message'    => $message,
            'order_code' => $orderCode
        ];
    }
}

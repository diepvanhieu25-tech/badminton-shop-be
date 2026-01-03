<?php

namespace App\Services\Api;

use App\Enums\PaymentStatus;
use App\Repositories\Interfaces\Api\OrderRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Exception;

class VnpayService
{
    public function __construct(
        protected OrderRepositoryInterface $orderRepo
    ) {}

    // 1. Tạo URL thanh toán
    public function createPaymentUrl(int $userId, string $orderCode)
    {
        // Lấy đơn hàng để lấy số tiền
        $order = $this->orderRepo->findOrderByCode($userId, $orderCode);
        if (!$order) throw new Exception("Đơn hàng không tồn tại.", 404);

        if ($order->payment_status === PaymentStatus::PAID) {
            throw new Exception("Đơn hàng này đã được thanh toán.", 400);
        }

        $vnp_TmnCode = env('VNP_TMN_CODE');
        $vnp_HashSecret = env('VNP_HASH_SECRET');
        $vnp_Url = env('VNP_URL');
        $vnp_ReturnUrl = env('VNP_RETURN_URL');
        
        $vnp_TxnRef = $order->code; 
        $vnp_OrderInfo = "Thanh toan don hang #" . $order->code;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $order->total * 100; // VNPAY tính đơn vị đồng (nhân 100)
        $vnp_Locale = 'vn';
        $vnp_IpAddr = request()->ip();
        $startTime = date("YmdHis");

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => $startTime,
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        // Sắp xếp tham số theo a-z (Bắt buộc)
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= '&vnp_SecureHash=' . $vnpSecureHash;
        }

        return $vnp_Url;
    }

    // 2. Xử lý Callback (Kiểm tra chữ ký bảo mật)
    public function handlePaymentCallback(array $vnpayData)
    {
        $vnp_HashSecret = env('VNP_HASH_SECRET');
        $vnp_SecureHash = $vnpayData['vnp_SecureHash'] ?? '';
        
        // Loại bỏ các trường hash để tính toán lại checksum
        $inputData = array();
        foreach ($vnpayData as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        
        unset($inputData['vnp_SecureHash']);
        
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        // Check chữ ký
        if ($secureHash === $vnp_SecureHash) {
            $orderCode = $inputData['vnp_TxnRef'];
            $amount = $inputData['vnp_Amount'] / 100;
            $transactionNo = $inputData['vnp_TransactionNo'];
            $responseCode = $inputData['vnp_ResponseCode'];

            // 00 là thành công
            if ($responseCode == "00") {
                $status = PaymentStatus::SUCCESS->value;
                $message = "Giao dịch thành công";
            } else {
                $status = PaymentStatus::FAILED->value;
                $message = "Giao dịch thất bại / Đã hủy";
            }

            // Gọi Repo update DB
            $this->orderRepo->updatePaymentStatus($orderCode, [
                'transaction_code' => $transactionNo,
                'amount' => $amount,
                'raw_data' => $vnpayData
            ], $status);

            return [
                'success' => $responseCode == "00",
                'message' => $message,
                'order_code' => $orderCode
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Chữ ký không hợp lệ (Invalid Signature)'
            ];
        }
    }
}
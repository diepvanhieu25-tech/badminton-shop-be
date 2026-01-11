<?php

namespace App\Services\Admin;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ViettelPostService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.viettelpost.url', 'https://partner.viettelpost.vn/v2');
    }

    public function createOrder(Order $order): string
    {

        if (config('services.viettelpost.sandbox', true)) {
            
            sleep(1); 

            return 'VTP_DEMO_' . rand(100000, 999999);
        }

        try {

            $token = $this->login();

            $payload = [
                'ORDER_NUMBER' => $order->code,
                'GROUPADDRESS_ID' => config('services.viettelpost.group_address_id', 12345),
                'CUS_ID' => config('services.viettelpost.cus_id', 12345),
                'DELIVERY_DATE' => now()->format('d/m/Y H:i:s'),
                'SENDER_FULLNAME' => 'Shop Demo Laravel',
                'SENDER_ADDRESS' => 'Hà Nội, Việt Nam',
                'SENDER_PHONE' => '0988888888',
                'RECEIVER_FULLNAME' => $order->receiver_name,
                'RECEIVER_ADDRESS' => $order->shipping_address,
                'RECEIVER_PHONE' => $order->receiver_phone,
                'PRODUCT_NAME' => 'Tổng hợp sản phẩm',
                'PRODUCT_PRICE' => (int) $order->total,
                'PRODUCT_WEIGHT' => 1000, 
                'ORDER_PAYMENT' => $order->payment_status === 'paid' ? 1 : 2, 
                'MONEY_COLLECTION' => $order->payment_status === 'paid' ? 0 : (int) $order->total,
            ];

            $response = Http::withToken($token)
                ->post("{$this->baseUrl}/order/create", $payload);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['data']['ORDER_NUMBER'])) {
                    return $data['data']['ORDER_NUMBER'];
                }
            }

            throw new \Exception("Viettel API Error: " . $response->body());

        } catch (\Exception $e) {
            Log::error("Viettel Post Service Error: " . $e->getMessage());
            throw $e; 
        }
    }

    private function login(): string
    {
        $username = config('services.viettelpost.username');
        $password = config('services.viettelpost.password');

        $response = Http::post("{$this->baseUrl}/user/owner/login", [
            'USERNAME' => $username,
            'PASSWORD' => $password,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['data']['token'])) {
                return $data['data']['token'];
            }
        }

        throw new \Exception("Không thể đăng nhập Viettel Post. Kiểm tra lại tài khoản/mật khẩu.");
    }
}
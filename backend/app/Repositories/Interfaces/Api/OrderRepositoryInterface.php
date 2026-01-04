<?php
namespace App\Repositories\Interfaces\Api;

interface OrderRepositoryInterface
{
    public function createOrder(array $data);
    public function createOrderItem(array $data);
    public function createPayment(array $data);
    public function getOrdersByUser(int $userId, int $limit = 10);
    public function getOrderDetail(int $userId, string $code);
    public function findOrderByCode(int $userId, string $code);
    public function updatePaymentStatus(string $orderCode, array $paymentData, string $status);
}
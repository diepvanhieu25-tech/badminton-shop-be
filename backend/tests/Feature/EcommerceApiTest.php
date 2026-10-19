<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EcommerceApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
    }

    public function test_health_check(): void
    {
        $response = $this->getJson('/api/health');
        $response->assertStatus(200)
                 ->assertJson(['status' => 'ok']);
    }

    public function test_catalog_apis(): void
    {
        $categoryResponse = $this->getJson('/api/v1/categories');
        $categoryResponse->assertStatus(200)
                         ->assertJson(['status' => true]);

        $brandResponse = $this->getJson('/api/v1/brands');
        $brandResponse->assertStatus(200)
                      ->assertJson(['status' => true]);

        $productResponse = $this->getJson('/api/v1/products');
        $productResponse->assertStatus(200)
                        ->assertJson(['status' => true]);
    }

    public function test_auth_cart_and_order_flow(): void
    {
        // 1. Register
        $uniqueEmail = 'e2e_' . uniqid() . '@example.com';
        $registerRes = $this->postJson('/api/v1/auth/register', [
            'name' => 'E2E Tester',
            'email' => $uniqueEmail,
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);
        $registerRes->assertStatus(201);

        // 2. Login
        $loginRes = $this->postJson('/api/v1/auth/login', [
            'email' => $uniqueEmail,
            'password' => 'Password123!',
        ]);
        $loginRes->assertStatus(200);
        $token = $loginRes->json('access_token');
        $this->assertNotEmpty($token);

        // 3. Profile
        $profileRes = $this->withHeader('Authorization', 'Bearer ' . $token)
                           ->getJson('/api/v1/auth/me');
        $profileRes->assertStatus(200)
                   ->assertJsonPath('data.email', $uniqueEmail);

        // 4. Cart Check & Add Item
        $variant = ProductVariant::first();
        $this->assertNotNull($variant, 'A product variant should exist in database.');

        $addCartRes = $this->withHeader('Authorization', 'Bearer ' . $token)
                           ->postJson('/api/v1/cart/items', [
                               'product_variant_id' => $variant->id,
                               'quantity' => 1,
                           ]);
        $addCartRes->assertStatus(201)
                   ->assertJson(['status' => true]);

        $getCartRes = $this->withHeader('Authorization', 'Bearer ' . $token)
                           ->getJson('/api/v1/cart');
        $getCartRes->assertStatus(200)
                   ->assertJsonPath('data.total_items', 1);

        // 5. Create Order
        $orderRes = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/v1/orders', [
                             'receiver_name' => 'E2E Tester',
                             'receiver_phone' => '0987654321',
                             'shipping_address' => 'District 1, HCMC',
                             'payment_method' => 'cod',
                             'note' => 'E2E automated order',
                         ]);
        $orderRes->assertStatus(201)
                 ->assertJson(['status' => true]);
        $orderCode = $orderRes->json('data.code');
        $this->assertNotEmpty($orderCode);

        // 6. View Order Detail
        $detailRes = $this->withHeader('Authorization', 'Bearer ' . $token)
                          ->getJson('/api/v1/orders/' . $orderCode);
        $detailRes->assertStatus(200)
                  ->assertJsonPath('data.code', $orderCode);

        // 7. Cancel Order
        $cancelRes = $this->withHeader('Authorization', 'Bearer ' . $token)
                          ->putJson('/api/v1/orders/' . $orderCode . '/cancel');
        $cancelRes->assertStatus(200)
                  ->assertJsonPath('data.status.key', 'cancelled');
    }

    public function test_order_creation_with_bank_transfer(): void
    {
        $uniqueEmail = 'vietqr_' . uniqid() . '@example.com';
        $this->postJson('/api/v1/auth/register', [
            'name' => 'VietQR Buyer',
            'email' => $uniqueEmail,
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ])->assertStatus(201);

        $loginRes = $this->postJson('/api/v1/auth/login', [
            'email' => $uniqueEmail,
            'password' => 'Password123!',
        ])->assertStatus(200);
        $token = $loginRes->json('access_token');

        $variant = ProductVariant::first();
        $this->assertNotNull($variant);

        $this->withHeader('Authorization', 'Bearer ' . $token)
             ->postJson('/api/v1/cart/items', [
                 'product_variant_id' => $variant->id,
                 'quantity' => 1,
             ])->assertStatus(201);

        $orderRes = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/v1/orders', [
                             'receiver_name' => 'VietQR Buyer',
                             'receiver_phone' => '0981234567',
                             'shipping_address' => 'District 7, HCMC',
                             'payment_method' => 'bank_transfer',
                             'note' => 'VietQR test payment',
                         ]);
        $orderRes->assertStatus(201)
                 ->assertJson(['status' => true])
                 ->assertJsonPath('data.payment_method', 'bank_transfer');

        $orderCode = $orderRes->json('data.code');
        $detailRes = $this->withHeader('Authorization', 'Bearer ' . $token)
                          ->getJson('/api/v1/orders/' . $orderCode);
        $detailRes->assertStatus(200)
                  ->assertJsonPath('data.code', $orderCode)
                  ->assertJsonPath('data.payment_method', 'bank_transfer');
    }
}

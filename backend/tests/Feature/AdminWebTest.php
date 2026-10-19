<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminWebTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $customerUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $this->adminUser = User::where('email', 'admin@badminton.vn')->first();
        $this->customerUser = User::where('email', 'test@example.com')->first();
    }

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/admin/login');
    }

    public function test_customer_cannot_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->customerUser)->get('/admin');
        $response->assertRedirect('/');
    }

    public function test_admin_can_login_via_form(): void
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@badminton.vn',
            'password' => 'AdminPassword123!',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($this->adminUser);
    }

    public function test_admin_dashboard_renders_successfully(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin');
        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard.index');
        $response->assertViewHas(['todayRevenue', 'todayOrders', 'lowStockCount']);
    }

    public function test_admin_product_management_routes(): void
    {
        // 1. List products
        $listResponse = $this->actingAs($this->adminUser)->get('/admin/products');
        $listResponse->assertStatus(200);

        // 2. Create product form
        $createResponse = $this->actingAs($this->adminUser)->get('/admin/products/create');
        $createResponse->assertStatus(200);
    }

    public function test_admin_order_management_routes(): void
    {
        // 1. List orders
        $orderListResponse = $this->actingAs($this->adminUser)->get('/admin/orders');
        $orderListResponse->assertStatus(200);

        // 2. Show order if any
        $order = Order::first();
        if ($order) {
            $showResponse = $this->actingAs($this->adminUser)->get('/admin/orders/' . $order->id);
            $showResponse->assertStatus(200);
        }
    }

    public function test_admin_category_and_brand_management_routes(): void
    {
        $catResponse = $this->actingAs($this->adminUser)->get('/admin/category');
        $catResponse->assertStatus(200);

        $brandResponse = $this->actingAs($this->adminUser)->get('/admin/brands');
        $brandResponse->assertStatus(200);
    }

    public function test_admin_user_management_routes(): void
    {
        $userResponse = $this->actingAs($this->adminUser)->get('/admin/user');
        $userResponse->assertStatus(200);
    }

    public function test_admin_can_create_product(): void
    {
        $category = \App\Models\Category::first();
        $brand = \App\Models\Brand::first();

        $productData = [
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'Admin Created Vợt Yonex Nano',
            'sku' => 'YNX-NANO-01',
            'price' => 2500000,
            'status' => \App\Enums\ProductStatus::ACTIVE->value,
            'has_variants' => 0,
            'is_featured' => 1,
            'description' => 'Mô tả vợt tạo bởi Admin test',
        ];

        $response = $this->actingAs($this->adminUser)->post('/admin/products', $productData);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('products', ['sku' => 'YNX-NANO-01']);
    }

    public function test_admin_can_update_and_ship_order(): void
    {
        $order = Order::create([
            'user_id' => $this->customerUser->id,
            'code' => 'ORD-ADMIN-TEST-UNIT',
            'receiver_name' => 'Test Customer',
            'receiver_phone' => '0901234567',
            'shipping_address' => 'District 1, HCMC',
            'subtotal' => 1000000,
            'shipping_fee' => 30000,
            'total' => 1030000,
            'payment_method' => 'cod',
            'payment_status' => 'unpaid',
            'status' => 'pending',
        ]);

        // 1. Update status to processing
        $updateRes = $this->actingAs($this->adminUser)->put('/admin/orders/' . $order->id, [
            'status' => 'processing',
        ]);
        $updateRes->assertSessionHasNoErrors();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'processing',
        ]);

        // 2. Ship order via ViettelPost
        $shipRes = $this->actingAs($this->adminUser)->post('/admin/orders/' . $order->id . '/ship');
        $shipRes->assertSessionHasNoErrors();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'shipping',
        ]);
        $this->assertDatabaseHas('shipments', [
            'order_id' => $order->id,
            'carrier' => 'Viettel Post',
        ]);

        // 3. Print invoice view
        $printRes = $this->actingAs($this->adminUser)->get('/admin/orders/' . $order->id . '/print');
        $printRes->assertStatus(200);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Shipment;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Enums\ProductStatus;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // -------------------------------------------------------------
        // 1. USERS & ADMIN ACCOUNTS
        // -------------------------------------------------------------
        $admin = User::updateOrCreate(
            ['email' => 'admin@badminton.vn'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('AdminPassword123!'),
                'role' => UserRole::ADMIN,
                'status' => UserStatus::ACTIVE,
            ]
        );

        $testUser = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('Password123!'),
                'role' => UserRole::CUSTOMER,
                'status' => UserStatus::ACTIVE,
            ]
        );

        $demoBuyer = User::updateOrCreate(
            ['email' => 'buyer@badminton.vn'],
            [
                'name' => 'Nguyễn Hoàng Nam (VĐV Bán Chuyên)',
                'password' => Hash::make('SecurePassword123!'),
                'role' => UserRole::CUSTOMER,
                'status' => UserStatus::ACTIVE,
            ]
        );

        $vipBuyer = User::updateOrCreate(
            ['email' => 'hoangnam@badminton.vn'],
            [
                'name' => 'Trần Linh Đan (VIP Member)',
                'password' => Hash::make('Password123!'),
                'role' => UserRole::CUSTOMER,
                'status' => UserStatus::ACTIVE,
            ]
        );

        // -------------------------------------------------------------
        // 2. BRANDS (Official Vector & Authentic Brand Logos)
        // -------------------------------------------------------------
        $brandYonex = Brand::updateOrCreate(
            ['name' => 'Yonex'],
            [
                'is_active' => true,
                'description' => 'Thương hiệu cầu lông số 1 thế giới đến từ Nhật Bản, đồng hành cùng Viktor Axelsen và các huyền thoại thế giới.',
                'logo_url' => 'brands/m3Ugp81bprbwT4lt7VNsNb4pkGI8oct75d4BMxpr.png',
            ]
        );

        $brandLining = Brand::updateOrCreate(
            ['name' => 'Lining'],
            [
                'is_active' => true,
                'description' => 'Thương hiệu thể thao hàng đầu thế giới đến từ Trung Quốc, tài trợ cho các tay vợt xuất sắc và đội tuyển quốc gia.',
                'logo_url' => 'brands/u3Qjr4cVAgjM2gEs6d1E0WYweeUNhoail09Ye6kE.jpg',
            ]
        );

        $brandMizuno = Brand::updateOrCreate(
            ['name' => 'Mizuno'],
            [
                'is_active' => true,
                'description' => 'Đỉnh cao cơ khí và vật liệu chính xác Nhật Bản, nổi tiếng với độ hoàn thiện cao cấp và kiểm soát tuyệt đối.',
                'logo_url' => 'brands/3QwfhJwOhkQfPbVX3kExpQNkKzob5GslmsTk7ZpM.jpg',
            ]
        );

        $brandKumpoo = Brand::updateOrCreate(
            ['name' => 'Kumpoo'],
            [
                'is_active' => true,
                'description' => 'Thương hiệu cầu lông chuyên nghiệp bền bỉ, dễ chơi, cân bằng công thủ toàn diện được giới phong trào ưa chuộng.',
                'logo_url' => 'brands/IIW5DP44m0Hq6zPu4ORxi08hUQSmfCZ9pLqnEtgI.jpg',
            ]
        );

        $brandKuno = Brand::updateOrCreate(
            ['name' => 'Kuno'],
            [
                'is_active' => true,
                'description' => 'Dòng sản phẩm cầu lông Carbon Fibre siêu nhẹ, độ trợ lực linh hoạt cao, thiết kế hiện đại bùng nổ năng lượng.',
                'logo_url' => 'brands/ULYNisyiJeaUfuh2LI3rdywJzBqOhDhTESDvjK51.jpg',
            ]
        );

        // -------------------------------------------------------------
        // 3. CATEGORIES (Authentic High-Definition Category Imagery)
        // -------------------------------------------------------------
        $catRacket = Category::updateOrCreate(
            ['name' => 'Vợt Cầu Lông'],
            [
                'parent_id' => null,
                'is_active' => true,
                'image_url' => 'categories/MznDWbzD9IZcrctV9b0GZLpUIycd9ocxvnmYE0Ze.jpg',
            ]
        );

        $catShoes = Category::updateOrCreate(
            ['name' => 'Giày Cầu Lông'],
            [
                'parent_id' => null,
                'is_active' => true,
                'image_url' => 'categories/kyFH13Jz9eHO5KZ44tUXNgfGSoW0qDfmcuse60L6.jpg',
            ]
        );

        $catBags = Category::updateOrCreate(
            ['name' => 'Balo & Túi Vợt'],
            [
                'parent_id' => null,
                'is_active' => true,
                'image_url' => 'categories/r9CdZEAedczAJgFHFvYP6O6uEnm5ik9dsb1Gpp3c.jpg',
            ]
        );

        $catApparel = Category::updateOrCreate(
            ['name' => 'Quần Áo Thể Thao'],
            [
                'parent_id' => null,
                'is_active' => true,
                'image_url' => 'categories/wtK4vYq7j3bvqw4W3BWelwS55ug3oTwvPuHvjlrd.jpg',
            ]
        );

        $catAccessories = Category::updateOrCreate(
            ['name' => 'Phụ Kiện Cầu Lông'],
            [
                'parent_id' => null,
                'is_active' => true,
                'image_url' => 'categories/tVK1amO7ABEI1WoHvgB4XwYCtIlgd2fUXHkeY7hc.jpg',
            ]
        );

        // Clean existing product images and variants before reseeding products to avoid orphan data
        Schema::disableForeignKeyConstraints();
        ProductImage::truncate();
        ProductVariant::truncate();
        Product::truncate();
        Schema::enableForeignKeyConstraints();

        // -------------------------------------------------------------
        // 4. PRODUCTS & VARIANTS (100% Authentic Equipment & Local Assets)
        // -------------------------------------------------------------

        // --- PRODUCT 1: Yonex Nanoflare 1000Z ---
        $p1 = Product::create([
            'category_id' => $catRacket->id,
            'brand_id' => $brandYonex->id,
            'name' => 'Vợt Cầu Lông Yonex Nanoflare 1000Z',
            'sku' => 'YNX-NF1000Z',
            'thumbnail' => 'uploads/products/48ToPynsIcEpyKMBJDbsMNfmfAMRI0Os3Zqqgc1g.jpg',
            'description' => 'Vợt cầu lông Yonex Nanoflare 1000Z là siêu phẩm tốc độ đỉnh cao phá vỡ kỷ lục thế giới về vận tốc đập cầu. Thiết kế thân Sonic Flare System kết hợp công nghệ Ultra PE Fiber mang lại những cú phản tạt và smash chớp nhoáng với độ chính xác tuyệt đối.',
            'price' => 4450000,
            'original_price' => 4950000,
            'status' => ProductStatus::ACTIVE,
            'has_variants' => true,
            'is_featured' => true,
        ]);
        ProductImage::create(['product_id' => $p1->id, 'image_url' => 'uploads/products/48ToPynsIcEpyKMBJDbsMNfmfAMRI0Os3Zqqgc1g.jpg', 'sort_order' => 1]);
        ProductImage::create(['product_id' => $p1->id, 'image_url' => 'uploads/products/OlwpdOvjdAX67TKBWNaVIep1FtSM1ctw2ZlrOW8x.webp', 'sort_order' => 2]);

        $v1_1 = ProductVariant::create([
            'product_id' => $p1->id,
            'sku' => 'NF-1000Z-YLW-4U',
            'attributes' => ['Màu sắc' => 'Vàng Lightning', 'Trọng lượng' => '4U', 'Cán' => 'G5'],
            'price' => 4450000,
            'original_price' => 4950000,
            'stock_qty' => 25,
            'stock_alert' => 5,
            'image' => 'uploads/products/48ToPynsIcEpyKMBJDbsMNfmfAMRI0Os3Zqqgc1g.jpg',
        ]);
        $v1_2 = ProductVariant::create([
            'product_id' => $p1->id,
            'sku' => 'NF-1000Z-YLW-3U',
            'attributes' => ['Màu sắc' => 'Vàng Lightning', 'Trọng lượng' => '3U', 'Cán' => 'G5'],
            'price' => 4450000,
            'original_price' => 4950000,
            'stock_qty' => 18,
            'stock_alert' => 5,
            'image' => 'uploads/products/48ToPynsIcEpyKMBJDbsMNfmfAMRI0Os3Zqqgc1g.jpg',
        ]);
        ProductVariant::create([
            'product_id' => $p1->id,
            'sku' => 'NF-1000Z-LTD-4U',
            'attributes' => ['Màu sắc' => 'Trắng Navy Limited', 'Trọng lượng' => '4U', 'Cán' => 'G5'],
            'price' => 4650000,
            'original_price' => 5150000,
            'stock_qty' => 12,
            'stock_alert' => 3,
            'image' => 'uploads/products/OlwpdOvjdAX67TKBWNaVIep1FtSM1ctw2ZlrOW8x.webp',
        ]);

        // --- PRODUCT 2: Li-Ning Axforce Cannon ---
        $p2 = Product::create([
            'category_id' => $catRacket->id,
            'brand_id' => $brandLining->id,
            'name' => 'Vợt Cầu Lông Li-Ning Axforce Cannon',
            'sku' => 'LN-AXCANNON',
            'thumbnail' => 'uploads/products/Pu0j7OUhTKxF5UJs58pwdJDyVLHJkbT8jqIBTt0Y.jpg',
            'description' => 'Li-Ning Axforce Cannon là cây vợt tấn công quốc dân với sức mạnh công phá cực kỳ ấn tượng trong phân khúc dưới 2 triệu. Thân vợt carbon cao cấp tạo lực đẩy mạnh mẽ, dễ thuần và trợ lực tốt cho các pha phông cầu cuối sân.',
            'price' => 1250000,
            'original_price' => 1550000,
            'status' => ProductStatus::ACTIVE,
            'has_variants' => true,
            'is_featured' => true,
        ]);
        ProductImage::create(['product_id' => $p2->id, 'image_url' => 'uploads/products/Pu0j7OUhTKxF5UJs58pwdJDyVLHJkbT8jqIBTt0Y.jpg', 'sort_order' => 1]);
        ProductImage::create(['product_id' => $p2->id, 'image_url' => 'uploads/products/CpUdzgoePMVVuYRMB2NxHkhw7RLUAHqxDlS0XgUA.jpg', 'sort_order' => 2]);
        ProductImage::create(['product_id' => $p2->id, 'image_url' => 'uploads/products/rcMvqNh66psJzlFgVsOWtIqMdW88jOEOEjPeTSx8.jpg', 'sort_order' => 3]);

        $v2_1 = ProductVariant::create([
            'product_id' => $p2->id,
            'sku' => 'AX-CANNON-BLK-4U',
            'attributes' => ['Màu sắc' => 'Đen Huyền Bí', 'Trọng lượng' => '4U', 'Cán' => 'G5'],
            'price' => 1250000,
            'original_price' => 1550000,
            'stock_qty' => 40,
            'stock_alert' => 5,
            'image' => 'uploads/products/Pu0j7OUhTKxF5UJs58pwdJDyVLHJkbT8jqIBTt0Y.jpg',
        ]);
        $v2_2 = ProductVariant::create([
            'product_id' => $p2->id,
            'sku' => 'AX-CANNON-WHT-4U',
            'attributes' => ['Màu sắc' => 'Trắng Tuyết', 'Trọng lượng' => '4U', 'Cán' => 'G5'],
            'price' => 1250000,
            'original_price' => 1550000,
            'stock_qty' => 35,
            'stock_alert' => 5,
            'image' => 'uploads/products/CpUdzgoePMVVuYRMB2NxHkhw7RLUAHqxDlS0XgUA.jpg',
        ]);
        ProductVariant::create([
            'product_id' => $p2->id,
            'sku' => 'AX-CANNON-PUR-5U',
            'attributes' => ['Màu sắc' => 'Tím Violet', 'Trọng lượng' => '5U', 'Cán' => 'G6'],
            'price' => 1290000,
            'original_price' => 1590000,
            'stock_qty' => 20,
            'stock_alert' => 4,
            'image' => 'uploads/products/rcMvqNh66psJzlFgVsOWtIqMdW88jOEOEjPeTSx8.jpg',
        ]);

        // --- PRODUCT 3: Kumpoo Power Control K520 Pro ---
        $p3 = Product::create([
            'category_id' => $catRacket->id,
            'brand_id' => $brandKumpoo->id,
            'name' => 'Vợt Cầu Lông Kumpoo Power Control K520 Pro',
            'sku' => 'KP-K520PRO',
            'thumbnail' => 'uploads/products/cyiSNuQqdCBP230McKHyaXb5Y5zZ1pzm7uigx4JB.webp',
            'description' => 'Vợt Kumpoo Power Control K520 Pro - Cây vợt toàn năng huyền thoại được hàng vạn người chơi cầu lông tin dùng. Thân dẻo trợ lực cực tốt, khung carbon gia cường chịu lực căng cao tới 28 lbs, độ bền trứ danh.',
            'price' => 980000,
            'original_price' => 1200000,
            'status' => ProductStatus::ACTIVE,
            'has_variants' => true,
            'is_featured' => true,
        ]);
        ProductImage::create(['product_id' => $p3->id, 'image_url' => 'uploads/products/cyiSNuQqdCBP230McKHyaXb5Y5zZ1pzm7uigx4JB.webp', 'sort_order' => 1]);
        ProductImage::create(['product_id' => $p3->id, 'image_url' => 'uploads/products/3Al1fvcQIl1Z1k0D2oqEAwzD0CF9J5f7YnVqBR3z.webp', 'sort_order' => 2]);
        ProductImage::create(['product_id' => $p3->id, 'image_url' => 'uploads/products/Eygs9c0yQy9w2XQNk2vM1u9I0cjxMmkCAg3NGPJ9.webp', 'sort_order' => 3]);

        $v3_1 = ProductVariant::create([
            'product_id' => $p3->id,
            'sku' => 'KP-K520PRO-BLK',
            'attributes' => ['Màu sắc' => 'Đen Xanh', 'Trọng lượng' => '4U', 'Cán' => 'G5'],
            'price' => 980000,
            'original_price' => 1200000,
            'stock_qty' => 50,
            'stock_alert' => 10,
            'image' => 'uploads/products/cyiSNuQqdCBP230McKHyaXb5Y5zZ1pzm7uigx4JB.webp',
        ]);
        $v3_2 = ProductVariant::create([
            'product_id' => $p3->id,
            'sku' => 'KP-K520PRO-PUR',
            'attributes' => ['Màu sắc' => 'Trắng Tím', 'Trọng lượng' => '4U', 'Cán' => 'G5'],
            'price' => 980000,
            'original_price' => 1200000,
            'stock_qty' => 45,
            'stock_alert' => 10,
            'image' => 'uploads/products/3Al1fvcQIl1Z1k0D2oqEAwzD0CF9J5f7YnVqBR3z.webp',
        ]);
        ProductVariant::create([
            'product_id' => $p3->id,
            'sku' => 'KP-K520PRO-WHT',
            'attributes' => ['Màu sắc' => 'Trắng Xanh Ngọc', 'Trọng lượng' => '4U', 'Cán' => 'G5'],
            'price' => 980000,
            'original_price' => 1200000,
            'stock_qty' => 30,
            'stock_alert' => 8,
            'image' => 'uploads/products/Eygs9c0yQy9w2XQNk2vM1u9I0cjxMmkCAg3NGPJ9.webp',
        ]);

        // --- PRODUCT 4: Kuno Super Light K88 ---
        $p4 = Product::create([
            'category_id' => $catRacket->id,
            'brand_id' => $brandKuno->id,
            'name' => 'Vợt Cầu Lông Kuno Super Light K88',
            'sku' => 'KUNO-K88-SL',
            'thumbnail' => 'uploads/products/H64jR92W6PVQlAxdGmouc1hoe1rRum3pCkM8ffVq.jpg',
            'description' => 'Vợt Kuno Super Light K88 chế tạo từ sợi carbon fibre cao cấp trọng lượng siêu nhẹ, điểm cân bằng 290+-5mm công thủ toàn diện, trợ lực tối đa. Tặng kèm bao vợt chống sốc, quấn cán và căng cước miễn phí.',
            'price' => 750000,
            'original_price' => 950000,
            'status' => ProductStatus::ACTIVE,
            'has_variants' => true,
            'is_featured' => true,
        ]);
        ProductImage::create(['product_id' => $p4->id, 'image_url' => 'uploads/products/H64jR92W6PVQlAxdGmouc1hoe1rRum3pCkM8ffVq.jpg', 'sort_order' => 1]);
        ProductImage::create(['product_id' => $p4->id, 'image_url' => 'uploads/products/bEPkxpgk9rNb2xgFnaSNZYEVOHdvIPl3uMXR9Cly.jpg', 'sort_order' => 2]);

        $v4_1 = ProductVariant::create([
            'product_id' => $p4->id,
            'sku' => 'KUNO-K88-RED',
            'attributes' => ['Màu sắc' => 'Khung đỏ đen', 'Trọng lượng' => '4U', 'Cán' => 'G5'],
            'price' => 750000,
            'original_price' => 950000,
            'stock_qty' => 60,
            'stock_alert' => 10,
            'image' => 'uploads/products/H64jR92W6PVQlAxdGmouc1hoe1rRum3pCkM8ffVq.jpg',
        ]);
        ProductVariant::create([
            'product_id' => $p4->id,
            'sku' => 'KUNO-K88-WHT',
            'attributes' => ['Màu sắc' => 'Khung trắng đen', 'Trọng lượng' => '4U', 'Cán' => 'G5'],
            'price' => 750000,
            'original_price' => 950000,
            'stock_qty' => 45,
            'stock_alert' => 8,
            'image' => 'uploads/products/bEPkxpgk9rNb2xgFnaSNZYEVOHdvIPl3uMXR9Cly.jpg',
        ]);

        // --- PRODUCT 5: Kuno K88 Power High Carbon ---
        $p5 = Product::create([
            'category_id' => $catRacket->id,
            'brand_id' => $brandKuno->id,
            'name' => 'Vợt Cầu Lông Kuno K88 Power High Carbon',
            'sku' => 'KUNO-K88-PWR',
            'thumbnail' => 'uploads/products/BH9H5y4xBwDPxQ9cxfJVkS7E0mUUI7l6qL2rXNCM.jpg',
            'description' => 'Vợt Kuno K88 Power High Carbon tối ưu hoá lực đập với khung isometric trợ lực, kết cấu carbon nguyên khối tăng cường độ ổn định khi vung vợt tốc độ cao.',
            'price' => 820000,
            'original_price' => 1050000,
            'status' => ProductStatus::ACTIVE,
            'has_variants' => true,
            'is_featured' => false,
        ]);
        ProductImage::create(['product_id' => $p5->id, 'image_url' => 'uploads/products/BH9H5y4xBwDPxQ9cxfJVkS7E0mUUI7l6qL2rXNCM.jpg', 'sort_order' => 1]);
        ProductImage::create(['product_id' => $p5->id, 'image_url' => 'uploads/products/vTpB2rEKzlPVaAxm9urAyFShDVHRZ78RYZsRvPuh.jpg', 'sort_order' => 2]);

        ProductVariant::create([
            'product_id' => $p5->id,
            'sku' => 'KUNO-PWR-GLD',
            'attributes' => ['Màu sắc' => 'Trắng Vàng Gold', 'Trọng lượng' => '4U', 'Cán' => 'G5'],
            'price' => 820000,
            'original_price' => 1050000,
            'stock_qty' => 30,
            'stock_alert' => 5,
            'image' => 'uploads/products/BH9H5y4xBwDPxQ9cxfJVkS7E0mUUI7l6qL2rXNCM.jpg',
        ]);
        ProductVariant::create([
            'product_id' => $p5->id,
            'sku' => 'KUNO-PWR-RED',
            'attributes' => ['Màu sắc' => 'Đỏ Đen Carbon', 'Trọng lượng' => '4U', 'Cán' => 'G5'],
            'price' => 820000,
            'original_price' => 1050000,
            'stock_qty' => 35,
            'stock_alert' => 5,
            'image' => 'uploads/products/vTpB2rEKzlPVaAxm9urAyFShDVHRZ78RYZsRvPuh.jpg',
        ]);

        // --- PRODUCT 6: Li-Ning Halbertec 8000 ---
        $p6 = Product::create([
            'category_id' => $catRacket->id,
            'brand_id' => $brandLining->id,
            'name' => 'Vợt Cầu Lông Li-Ning Halbertec 8000',
            'sku' => 'LN-HAL8000',
            'thumbnail' => 'uploads/products/8HgybePEusOltW8V0BVcJYarj6vbZaLOKaKwO4h2.jpg',
            'description' => 'Vợt Li-Ning Halbertec 8000 đồng hành cùng tay vợt hàng đầu thế giới Yuta Watanabe. Với trục vợt mỏng 6.8mm, khung hộp kiểm soát cầu chuẩn xác tuyệt đối đến từng milimet.',
            'price' => 4350000,
            'original_price' => 4800000,
            'status' => ProductStatus::ACTIVE,
            'has_variants' => true,
            'is_featured' => true,
        ]);
        ProductImage::create(['product_id' => $p6->id, 'image_url' => 'uploads/products/8HgybePEusOltW8V0BVcJYarj6vbZaLOKaKwO4h2.jpg', 'sort_order' => 1]);
        ProductImage::create(['product_id' => $p6->id, 'image_url' => 'uploads/products/TDFVV15EKcQdOUd1ZEju0KN4jxybnTN22KAUll2N.webp', 'sort_order' => 2]);
        ProductImage::create(['product_id' => $p6->id, 'image_url' => 'uploads/products/y9PEDs0ObCQ6zzcALyDZFkaqu54DAHbk9lyauDEY.jpg', 'sort_order' => 3]);

        ProductVariant::create([
            'product_id' => $p6->id,
            'sku' => 'LN-HAL8000-3U',
            'attributes' => ['Màu sắc' => 'Hồng Xanh Phối Trắng', 'Trọng lượng' => '3U', 'Cán' => 'G5'],
            'price' => 4350000,
            'original_price' => 4800000,
            'stock_qty' => 15,
            'stock_alert' => 3,
            'image' => 'uploads/products/8HgybePEusOltW8V0BVcJYarj6vbZaLOKaKwO4h2.jpg',
        ]);
        ProductVariant::create([
            'product_id' => $p6->id,
            'sku' => 'LN-HAL8000-4U',
            'attributes' => ['Màu sắc' => 'Hồng Xanh Phối Trắng', 'Trọng lượng' => '4U', 'Cán' => 'G5'],
            'price' => 4350000,
            'original_price' => 4800000,
            'stock_qty' => 22,
            'stock_alert' => 4,
            'image' => 'uploads/products/8HgybePEusOltW8V0BVcJYarj6vbZaLOKaKwO4h2.jpg',
        ]);

        // --- PRODUCT 7: Yonex Astrox 88D Pro ---
        $p7 = Product::create([
            'category_id' => $catRacket->id,
            'brand_id' => $brandYonex->id,
            'name' => 'Vợt Cầu Lông Yonex Astrox 88D Pro',
            'sku' => 'YNX-AX88DPRO',
            'thumbnail' => 'uploads/products/GJfw2jeTkhG23mrxsXPXyjgGkMWPCgWVMQ8JS2ZS.webp',
            'description' => 'Vợt cầu lông Yonex Astrox 88D Pro thế hệ thứ 3 với công nghệ Rotational Generator System cải tiến, tối ưu lực đập smash cắm sàn và áp đảo từ vị trí cầu sau sân.',
            'price' => 4100000,
            'original_price' => 4550000,
            'status' => ProductStatus::ACTIVE,
            'has_variants' => true,
            'is_featured' => true,
        ]);
        ProductImage::create(['product_id' => $p7->id, 'image_url' => 'uploads/products/GJfw2jeTkhG23mrxsXPXyjgGkMWPCgWVMQ8JS2ZS.webp', 'sort_order' => 1]);
        ProductImage::create(['product_id' => $p7->id, 'image_url' => 'uploads/products/vmYmd93PA2l0jJ7kZ6xJkOYs3bnQ5aOjLs9mmyhv.webp', 'sort_order' => 2]);
        ProductImage::create(['product_id' => $p7->id, 'image_url' => 'uploads/products/yFu1XVmgAgYxsZYS8ysVdrE6ZSx3sxaxaB0Iycoj.webp', 'sort_order' => 3]);

        ProductVariant::create([
            'product_id' => $p7->id,
            'sku' => 'AX-88D-PRO-3U',
            'attributes' => ['Màu sắc' => 'Cam Xanh Đen', 'Trọng lượng' => '3U', 'Cán' => 'G5'],
            'price' => 4100000,
            'original_price' => 4550000,
            'stock_qty' => 20,
            'stock_alert' => 4,
            'image' => 'uploads/products/GJfw2jeTkhG23mrxsXPXyjgGkMWPCgWVMQ8JS2ZS.webp',
        ]);
        ProductVariant::create([
            'product_id' => $p7->id,
            'sku' => 'AX-88S-PRO-4U',
            'attributes' => ['Màu sắc' => 'Xanh Ngọc Phối Đen', 'Trọng lượng' => '4U', 'Cán' => 'G5'],
            'price' => 4100000,
            'original_price' => 4550000,
            'stock_qty' => 18,
            'stock_alert' => 4,
            'image' => 'uploads/products/vmYmd93PA2l0jJ7kZ6xJkOYs3bnQ5aOjLs9mmyhv.webp',
        ]);

        // --- PRODUCT 8: Mizuno Luminasonic MS8 ---
        $p8 = Product::create([
            'category_id' => $catRacket->id,
            'brand_id' => $brandMizuno->id,
            'name' => 'Vợt Cầu Lông Mizuno Luminasonic MS8',
            'sku' => 'MIZ-MS8',
            'thumbnail' => 'uploads/products/649sON51NyfVE5s9U1MrlSZowxXyTuXJmTR6Fp1k.webp',
            'description' => 'Mizuno Luminasonic MS8 sở hữu khung vát khí động học xé gió thần tốc, cảm giác chạm cầu êm ái đầm tay, hỗ trợ xoay sở thoát cầu cực nhanh trên lưới.',
            'price' => 1890000,
            'original_price' => 2200000,
            'status' => ProductStatus::ACTIVE,
            'has_variants' => true,
            'is_featured' => false,
        ]);
        ProductImage::create(['product_id' => $p8->id, 'image_url' => 'uploads/products/649sON51NyfVE5s9U1MrlSZowxXyTuXJmTR6Fp1k.webp', 'sort_order' => 1]);
        ProductImage::create(['product_id' => $p8->id, 'image_url' => 'uploads/products/qTPOcYoS3sSl3znwq4Hh8B27idZrJbsN2YIaGcis.webp', 'sort_order' => 2]);
        ProductImage::create(['product_id' => $p8->id, 'image_url' => 'uploads/products/NDPl5hUH9HGVrlmc9ptvrAzbmQ3bM8FXJBJOddLL.webp', 'sort_order' => 3]);

        ProductVariant::create([
            'product_id' => $p8->id,
            'sku' => 'MIZ-MS8-GLD-4U',
            'attributes' => ['Màu sắc' => 'Đen Phối Vàng Gold', 'Trọng lượng' => '4U', 'Cán' => 'G5'],
            'price' => 1890000,
            'original_price' => 2200000,
            'stock_qty' => 25,
            'stock_alert' => 5,
            'image' => 'uploads/products/649sON51NyfVE5s9U1MrlSZowxXyTuXJmTR6Fp1k.webp',
        ]);
        ProductVariant::create([
            'product_id' => $p8->id,
            'sku' => 'MIZ-MS8-BLU-4U',
            'attributes' => ['Màu sắc' => 'Xanh Dương Phối Đen', 'Trọng lượng' => '4U', 'Cán' => 'G5'],
            'price' => 1890000,
            'original_price' => 2200000,
            'stock_qty' => 30,
            'stock_alert' => 5,
            'image' => 'uploads/products/qTPOcYoS3sSl3znwq4Hh8B27idZrJbsN2YIaGcis.webp',
        ]);

        // --- PRODUCT 9: Giày Cầu Lông Yonex Power Cushion 65Z3 ---
        $p9 = Product::create([
            'category_id' => $catShoes->id,
            'brand_id' => $brandYonex->id,
            'name' => 'Giày Cầu Lông Yonex Power Cushion 65Z3',
            'sku' => 'YNX-65Z3',
            'thumbnail' => 'uploads/products/YW2RlauMjywh1wlv9PB0uIZ7l2NS5KCW7rDlJ5h2.jpg',
            'description' => 'Mẫu giày cầu lông bán chạy nhất thế giới của Yonex với công nghệ giảm chấn Power Cushion+ hấp thụ sốc chuyển hóa thành năng lượng bật nhảy. Đế bám sân Radial Blade Sole chống trượt tối ưu.',
            'price' => 2850000,
            'original_price' => 3200000,
            'status' => ProductStatus::ACTIVE,
            'has_variants' => true,
            'is_featured' => true,
        ]);
        ProductImage::create(['product_id' => $p9->id, 'image_url' => 'uploads/products/YW2RlauMjywh1wlv9PB0uIZ7l2NS5KCW7rDlJ5h2.jpg', 'sort_order' => 1]);
        ProductImage::create(['product_id' => $p9->id, 'image_url' => 'uploads/products/s4td6Y4NBtuiy7xgwX4x30lLDPqUVOuBZjABBOsu.jpg', 'sort_order' => 2]);

        $v9_1 = ProductVariant::create([
            'product_id' => $p9->id,
            'sku' => 'SHB-65Z3-WHRD-40',
            'attributes' => ['Màu sắc' => 'Trắng Đỏ Đen', 'Size' => '40'],
            'price' => 2850000,
            'original_price' => 3200000,
            'stock_qty' => 15,
            'stock_alert' => 3,
            'image' => 'uploads/products/YW2RlauMjywh1wlv9PB0uIZ7l2NS5KCW7rDlJ5h2.jpg',
        ]);
        $v9_2 = ProductVariant::create([
            'product_id' => $p9->id,
            'sku' => 'SHB-65Z3-WHRD-41',
            'attributes' => ['Màu sắc' => 'Trắng Đỏ Đen', 'Size' => '41'],
            'price' => 2850000,
            'original_price' => 3200000,
            'stock_qty' => 25,
            'stock_alert' => 4,
            'image' => 'uploads/products/YW2RlauMjywh1wlv9PB0uIZ7l2NS5KCW7rDlJ5h2.jpg',
        ]);
        ProductVariant::create([
            'product_id' => $p9->id,
            'sku' => 'SHB-65Z3-WHRD-42',
            'attributes' => ['Màu sắc' => 'Trắng Đỏ Đen', 'Size' => '42'],
            'price' => 2850000,
            'original_price' => 3200000,
            'stock_qty' => 20,
            'stock_alert' => 4,
            'image' => 'uploads/products/YW2RlauMjywh1wlv9PB0uIZ7l2NS5KCW7rDlJ5h2.jpg',
        ]);
        ProductVariant::create([
            'product_id' => $p9->id,
            'sku' => 'SHB-65Z3-WHBL-41',
            'attributes' => ['Màu sắc' => 'Trắng Xanh Japan', 'Size' => '41'],
            'price' => 2950000,
            'original_price' => 3350000,
            'stock_qty' => 14,
            'stock_alert' => 3,
            'image' => 'uploads/products/s4td6Y4NBtuiy7xgwX4x30lLDPqUVOuBZjABBOsu.jpg',
        ]);

        // --- PRODUCT 10: Giày Cầu Lông Yonex Cascade Accel ---
        $p10 = Product::create([
            'category_id' => $catShoes->id,
            'brand_id' => $brandYonex->id,
            'name' => 'Giày Cầu Lông Yonex Cascade Accel',
            'sku' => 'YNX-CASACCEL',
            'thumbnail' => 'uploads/products/B7p8dpiOU4Ny47ml11uIFoAQYFwp5wRnx90VwQ52.jpg',
            'description' => 'Giày Yonex Cascade Accel đem đến cảm giác ôm khít bàn chân vừa vặn tự nhiên, đệm lót êm ái linh hoạt, phối màu trẻ trung năng động trên sàn đấu.',
            'price' => 1950000,
            'original_price' => 2300000,
            'status' => ProductStatus::ACTIVE,
            'has_variants' => true,
            'is_featured' => false,
        ]);
        ProductImage::create(['product_id' => $p10->id, 'image_url' => 'uploads/products/B7p8dpiOU4Ny47ml11uIFoAQYFwp5wRnx90VwQ52.jpg', 'sort_order' => 1]);
        ProductImage::create(['product_id' => $p10->id, 'image_url' => 'uploads/variants/KQiETeexuuqZMDExc622rJfw8uNR0R80R239GZGN.webp', 'sort_order' => 2]);

        ProductVariant::create([
            'product_id' => $p10->id,
            'sku' => 'SHB-CAS-WHBL-40',
            'attributes' => ['Màu sắc' => 'Trắng Xanh Pastel', 'Size' => '40'],
            'price' => 1950000,
            'original_price' => 2300000,
            'stock_qty' => 20,
            'stock_alert' => 4,
            'image' => 'uploads/products/B7p8dpiOU4Ny47ml11uIFoAQYFwp5wRnx90VwQ52.jpg',
        ]);
        ProductVariant::create([
            'product_id' => $p10->id,
            'sku' => 'SHB-CAS-WHBL-41',
            'attributes' => ['Màu sắc' => 'Trắng Xanh Pastel', 'Size' => '41'],
            'price' => 1950000,
            'original_price' => 2300000,
            'stock_qty' => 25,
            'stock_alert' => 4,
            'image' => 'uploads/products/B7p8dpiOU4Ny47ml11uIFoAQYFwp5wRnx90VwQ52.jpg',
        ]);
        ProductVariant::create([
            'product_id' => $p10->id,
            'sku' => 'SHB-CAS-BKBL-42',
            'attributes' => ['Màu sắc' => 'Đen Phối Xanh', 'Size' => '42'],
            'price' => 1950000,
            'original_price' => 2300000,
            'stock_qty' => 18,
            'stock_alert' => 3,
            'image' => 'uploads/variants/KQiETeexuuqZMDExc622rJfw8uNR0R80R239GZGN.webp',
        ]);

        // --- PRODUCT 11: Giày Cầu Lông Li-Ning DF-01 LITE ---
        $p11 = Product::create([
            'category_id' => $catShoes->id,
            'brand_id' => $brandLining->id,
            'name' => 'Giày Cầu Lông Li-Ning DF-01 LITE',
            'sku' => 'LN-DF01',
            'thumbnail' => 'uploads/products/L989gBpCsPc1V0gNE5dLwy5HzqVew1TS0Iwsr7NL.webp',
            'description' => 'Giày cầu lông Li-Ning DF-01 Lite với phần thân giày bằng da tổng hợp kết hợp lưới thoáng khí, trọng lượng nhẹ nhàng bảo vệ cổ chân an toàn khi bật nhảy.',
            'price' => 1450000,
            'original_price' => 1750000,
            'status' => ProductStatus::ACTIVE,
            'has_variants' => true,
            'is_featured' => true,
        ]);
        ProductImage::create(['product_id' => $p11->id, 'image_url' => 'uploads/products/L989gBpCsPc1V0gNE5dLwy5HzqVew1TS0Iwsr7NL.webp', 'sort_order' => 1]);
        ProductImage::create(['product_id' => $p11->id, 'image_url' => 'uploads/products/PGYyzibHxaNgpCENPrXsKlXXRAoxSBJR0AgwLD0X.webp', 'sort_order' => 2]);

        ProductVariant::create([
            'product_id' => $p11->id,
            'sku' => 'LN-DF01-WKBK-40',
            'attributes' => ['Màu sắc' => 'Trắng Đen', 'Size' => '40'],
            'price' => 1450000,
            'original_price' => 1750000,
            'stock_qty' => 30,
            'stock_alert' => 5,
            'image' => 'uploads/products/L989gBpCsPc1V0gNE5dLwy5HzqVew1TS0Iwsr7NL.webp',
        ]);
        ProductVariant::create([
            'product_id' => $p11->id,
            'sku' => 'LN-DF01-WKBK-41',
            'attributes' => ['Màu sắc' => 'Trắng Đen', 'Size' => '41'],
            'price' => 1450000,
            'original_price' => 1750000,
            'stock_qty' => 35,
            'stock_alert' => 5,
            'image' => 'uploads/products/L989gBpCsPc1V0gNE5dLwy5HzqVew1TS0Iwsr7NL.webp',
        ]);
        ProductVariant::create([
            'product_id' => $p11->id,
            'sku' => 'LN-DF01-WKRD-41',
            'attributes' => ['Màu sắc' => 'Trắng Đỏ', 'Size' => '41'],
            'price' => 1450000,
            'original_price' => 1750000,
            'stock_qty' => 28,
            'stock_alert' => 4,
            'image' => 'uploads/products/PGYyzibHxaNgpCENPrXsKlXXRAoxSBJR0AgwLD0X.webp',
        ]);

        // --- PRODUCT 12: Giày Cầu Lông Mizuno Wave Claw 3 ---
        $p12 = Product::create([
            'category_id' => $catShoes->id,
            'brand_id' => $brandMizuno->id,
            'name' => 'Giày Cầu Lông Mizuno Wave Claw 3',
            'sku' => 'MIZ-WAVECLAW3',
            'thumbnail' => 'uploads/products/l95b03GmNAg15G9rxfcd2etsOUxAKWp9kPics5wA.webp',
            'description' => 'Mizuno Wave Claw 3 là biểu tượng tốc độ của dòng giày Mizuno Badminton, đế giữa Mizuno Enerzy nxt siêu đàn hồi bật nhảy cực đỉnh, thiết kế móng vuốt hỗ trợ dạt biên thần tốc.',
            'price' => 2690000,
            'original_price' => 3100000,
            'status' => ProductStatus::ACTIVE,
            'has_variants' => true,
            'is_featured' => true,
        ]);
        ProductImage::create(['product_id' => $p12->id, 'image_url' => 'uploads/products/l95b03GmNAg15G9rxfcd2etsOUxAKWp9kPics5wA.webp', 'sort_order' => 1]);
        ProductImage::create(['product_id' => $p12->id, 'image_url' => 'uploads/products/ysGeSZY5wDnkBzIUKJcb7vckRSS7xwt25dXBsMzP.webp', 'sort_order' => 2]);

        ProductVariant::create([
            'product_id' => $p12->id,
            'sku' => 'MIZ-WC3-BKYL-41',
            'attributes' => ['Màu sắc' => 'Đen Vàng Chanh', 'Size' => '41'],
            'price' => 2690000,
            'original_price' => 3100000,
            'stock_qty' => 16,
            'stock_alert' => 3,
            'image' => 'uploads/products/l95b03GmNAg15G9rxfcd2etsOUxAKWp9kPics5wA.webp',
        ]);
        ProductVariant::create([
            'product_id' => $p12->id,
            'sku' => 'MIZ-WC3-WHBL-41',
            'attributes' => ['Màu sắc' => 'Trắng Xanh Ngọc', 'Size' => '41'],
            'price' => 2690000,
            'original_price' => 3100000,
            'stock_qty' => 20,
            'stock_alert' => 4,
            'image' => 'uploads/products/ysGeSZY5wDnkBzIUKJcb7vckRSS7xwt25dXBsMzP.webp',
        ]);

        // --- PRODUCT 13: Giày Cầu Lông Mizuno Wave Fang Pro ---
        $p13 = Product::create([
            'category_id' => $catShoes->id,
            'brand_id' => $brandMizuno->id,
            'name' => 'Giày Cầu Lông Mizuno Wave Fang Pro (Da Thật)',
            'sku' => 'MIZ-FANGPRO',
            'thumbnail' => 'uploads/products/DeLlPRncjTdFaqeQfouxACCyUE09dVFQBbT0Wj5t.jpg',
            'description' => 'Mizuno Wave Fang Pro được chế tác từ da tự nhiên cao cấp mang lại cảm giác ôm chân hoàn hảo không tì vết. Đẳng cấp giày đấu chuyên nghiệp bền bỉ theo năm tháng.',
            'price' => 3450000,
            'original_price' => 3900000,
            'status' => ProductStatus::ACTIVE,
            'has_variants' => true,
            'is_featured' => true,
        ]);
        ProductImage::create(['product_id' => $p13->id, 'image_url' => 'uploads/products/DeLlPRncjTdFaqeQfouxACCyUE09dVFQBbT0Wj5t.jpg', 'sort_order' => 1]);
        ProductImage::create(['product_id' => $p13->id, 'image_url' => 'uploads/products/ZgWNV352QZoB4ufe8lagnGWYg8BQ2RYfjNUjY8Sv.webp', 'sort_order' => 2]);

        ProductVariant::create([
            'product_id' => $p13->id,
            'sku' => 'MIZ-WFP-WHGD-41',
            'attributes' => ['Màu sắc' => 'Trắng Vàng Gold (Da Thật)', 'Size' => '41'],
            'price' => 3450000,
            'original_price' => 3900000,
            'stock_qty' => 12,
            'stock_alert' => 2,
            'image' => 'uploads/products/DeLlPRncjTdFaqeQfouxACCyUE09dVFQBbT0Wj5t.jpg',
        ]);
        ProductVariant::create([
            'product_id' => $p13->id,
            'sku' => 'MIZ-WFP-WHBL-42',
            'attributes' => ['Màu sắc' => 'Trắng Xanh Royal', 'Size' => '42'],
            'price' => 3450000,
            'original_price' => 3900000,
            'stock_qty' => 15,
            'stock_alert' => 3,
            'image' => 'uploads/products/ZgWNV352QZoB4ufe8lagnGWYg8BQ2RYfjNUjY8Sv.webp',
        ]);

        // --- PRODUCT 14: Balo Cầu Lông Yonex Pro Backpack ---
        $p14 = Product::create([
            'category_id' => $catBags->id,
            'brand_id' => $brandYonex->id,
            'name' => 'Balo Cầu Lông Yonex Pro Tournament Backpack',
            'sku' => 'YNX-BP-PRO',
            'thumbnail' => 'categories/r9CdZEAedczAJgFHFvYP6O6uEnm5ik9dsb1Gpp3c.jpg',
            'description' => 'Balo Yonex Pro chuyên dụng với ngăn chứa vợt riêng biệt chống trầy xước, ngăn để giày thông thoáng đáy balo và quai đeo êm ái trợ lực vai khi di chuyển thi đấu.',
            'price' => 1250000,
            'original_price' => 1550000,
            'status' => ProductStatus::ACTIVE,
            'has_variants' => true,
            'is_featured' => true,
        ]);
        ProductImage::create(['product_id' => $p14->id, 'image_url' => 'categories/r9CdZEAedczAJgFHFvYP6O6uEnm5ik9dsb1Gpp3c.jpg', 'sort_order' => 1]);

        $v14_1 = ProductVariant::create([
            'product_id' => $p14->id,
            'sku' => 'YNX-BP-PRO-WHT',
            'attributes' => ['Màu sắc' => 'Trắng Tím Pastel', 'Kích thước' => 'Chứa 2-3 vợt + giày'],
            'price' => 1250000,
            'original_price' => 1550000,
            'stock_qty' => 30,
            'stock_alert' => 5,
            'image' => 'categories/r9CdZEAedczAJgFHFvYP6O6uEnm5ik9dsb1Gpp3c.jpg',
        ]);

        // --- PRODUCT 15: Quần Cầu Lông Yonex Tournament ---
        $p15 = Product::create([
            'category_id' => $catApparel->id,
            'brand_id' => $brandYonex->id,
            'name' => 'Quần Cầu Lông Yonex Tournament Chuyên Dụng',
            'sku' => 'YNX-SHORT-TRN',
            'thumbnail' => 'categories/wtK4vYq7j3bvqw4W3BWelwS55ug3oTwvPuHvjlrd.jpg',
            'description' => 'Quần thể thao Yonex làm từ chất liệu vải thun lạnh co giãn 4 chiều kháng khuẩn, thấm hút mồ hôi siêu tốc VeryCool giúp duy trì nhiệt độ cơ thể luôn mát mẻ.',
            'price' => 350000,
            'original_price' => 450000,
            'status' => ProductStatus::ACTIVE,
            'has_variants' => true,
            'is_featured' => false,
        ]);
        ProductImage::create(['product_id' => $p15->id, 'image_url' => 'categories/wtK4vYq7j3bvqw4W3BWelwS55ug3oTwvPuHvjlrd.jpg', 'sort_order' => 1]);

        $v15_1 = ProductVariant::create([
            'product_id' => $p15->id,
            'sku' => 'YNX-SHORT-BLK-M',
            'attributes' => ['Màu sắc' => 'Đen Logo YY Trắng', 'Size' => 'M'],
            'price' => 350000,
            'original_price' => 450000,
            'stock_qty' => 50,
            'stock_alert' => 10,
            'image' => 'categories/wtK4vYq7j3bvqw4W3BWelwS55ug3oTwvPuHvjlrd.jpg',
        ]);
        ProductVariant::create([
            'product_id' => $p15->id,
            'sku' => 'YNX-SHORT-BLK-L',
            'attributes' => ['Màu sắc' => 'Đen Logo YY Trắng', 'Size' => 'L'],
            'price' => 350000,
            'original_price' => 450000,
            'stock_qty' => 60,
            'stock_alert' => 10,
            'image' => 'categories/wtK4vYq7j3bvqw4W3BWelwS55ug3oTwvPuHvjlrd.jpg',
        ]);
        ProductVariant::create([
            'product_id' => $p15->id,
            'sku' => 'YNX-SHORT-BLK-XL',
            'attributes' => ['Màu sắc' => 'Đen Logo YY Trắng', 'Size' => 'XL'],
            'price' => 350000,
            'original_price' => 450000,
            'stock_qty' => 40,
            'stock_alert' => 8,
            'image' => 'categories/wtK4vYq7j3bvqw4W3BWelwS55ug3oTwvPuHvjlrd.jpg',
        ]);

        // --- PRODUCT 16: Váy Thi Đấu Cầu Lông Nữ Li-Ning ---
        $p16 = Product::create([
            'category_id' => $catApparel->id,
            'brand_id' => $brandLining->id,
            'name' => 'Váy Thi Đấu Cầu Lông Nữ Thể Thao Li-Ning',
            'sku' => 'LN-SKIRT-WHT',
            'thumbnail' => 'categories/tVK1amO7ABEI1WoHvgB4XwYCtIlgd2fUXHkeY7hc.jpg',
            'description' => 'Váy thể thao cầu lông nữ thiết kế xếp ly sang trọng tôn dáng, có quần bảo hộ bên trong tiện lợi an toàn, co giãn đa chiều mang lại sự tự tin tối đa cho phái nữ trên sân đấu.',
            'price' => 380000,
            'original_price' => 490000,
            'status' => ProductStatus::ACTIVE,
            'has_variants' => true,
            'is_featured' => true,
        ]);
        ProductImage::create(['product_id' => $p16->id, 'image_url' => 'categories/tVK1amO7ABEI1WoHvgB4XwYCtIlgd2fUXHkeY7hc.jpg', 'sort_order' => 1]);

        ProductVariant::create([
            'product_id' => $p16->id,
            'sku' => 'SKIRT-WHT-S',
            'attributes' => ['Màu sắc' => 'Trắng Tinh Tế', 'Size' => 'S'],
            'price' => 380000,
            'original_price' => 490000,
            'stock_qty' => 30,
            'stock_alert' => 5,
            'image' => 'categories/tVK1amO7ABEI1WoHvgB4XwYCtIlgd2fUXHkeY7hc.jpg',
        ]);
        ProductVariant::create([
            'product_id' => $p16->id,
            'sku' => 'SKIRT-WHT-M',
            'attributes' => ['Màu sắc' => 'Trắng Tinh Tế', 'Size' => 'M'],
            'price' => 380000,
            'original_price' => 490000,
            'stock_qty' => 40,
            'stock_alert' => 6,
            'image' => 'categories/tVK1amO7ABEI1WoHvgB4XwYCtIlgd2fUXHkeY7hc.jpg',
        ]);
        ProductVariant::create([
            'product_id' => $p16->id,
            'sku' => 'SKIRT-WHT-L',
            'attributes' => ['Màu sắc' => 'Trắng Tinh Tế', 'Size' => 'L'],
            'price' => 380000,
            'original_price' => 490000,
            'stock_qty' => 25,
            'stock_alert' => 4,
            'image' => 'categories/tVK1amO7ABEI1WoHvgB4XwYCtIlgd2fUXHkeY7hc.jpg',
        ]);

        // -------------------------------------------------------------
        // 5. ORDERS & DEMO TRANSACTIONS (Realistic History)
        // -------------------------------------------------------------
        Schema::disableForeignKeyConstraints();
        Shipment::truncate();
        Payment::truncate();
        OrderItem::truncate();
        Order::truncate();
        Schema::enableForeignKeyConstraints();

        // Order 1: Completed VNPay Order for Demo Buyer
        $order1 = Order::create([
            'user_id' => $demoBuyer->id,
            'code' => 'ORD-20261019-001',
            'subtotal' => 5700000,
            'shipping_fee' => 0,
            'total' => 5700000,
            'payment_method' => PaymentMethod::VNPAY,
            'payment_status' => PaymentStatus::PAID,
            'status' => OrderStatus::COMPLETED,
            'shipping_address' => 'Số 18 Hoàng Cầu, Phường Ô Chợ Dừa, Đống Đa, Hà Nội',
            'receiver_name' => 'Nguyễn Hoàng Nam',
            'receiver_phone' => '0988776655',
            'note' => 'Giao trong giờ hành chính, gọi trước 15 phút',
        ]);
        OrderItem::create([
            'order_id' => $order1->id,
            'product_variant_id' => $v1_1->id,
            'product_name' => $p1->name,
            'variant_name' => 'Vàng Lightning - 4U/G5',
            'quantity' => 1,
            'unit_price' => 4450000,
            'total_price' => 4450000,
            'attributes' => $v1_1->attributes,
        ]);
        OrderItem::create([
            'order_id' => $order1->id,
            'product_variant_id' => $v14_1->id,
            'product_name' => $p14->name,
            'variant_name' => 'Trắng Tím Pastel',
            'quantity' => 1,
            'unit_price' => 1250000,
            'total_price' => 1250000,
            'attributes' => $v14_1->attributes,
        ]);
        Payment::create([
            'order_id' => $order1->id,
            'transaction_id' => 'VNP20261019123456',
            'provider' => PaymentMethod::VNPAY,
            'amount' => 5700000,
            'status' => PaymentStatus::SUCCESS,
            'paid_at' => now()->subDays(2),
        ]);
        Shipment::create([
            'order_id' => $order1->id,
            'tracking_code' => 'VNPOST-889922001',
            'carrier' => 'VNPost Nhanh',
            'status' => 'delivered',
            'cod_amount' => 0,
            'shipped_at' => now()->subDays(2),
            'delivered_at' => now()->subDay(),
        ]);

        // Order 2: Processing COD Order for Demo Buyer
        $order2 = Order::create([
            'user_id' => $demoBuyer->id,
            'code' => 'ORD-20261019-002',
            'subtotal' => 3830000,
            'shipping_fee' => 0,
            'total' => 3830000,
            'payment_method' => PaymentMethod::COD,
            'payment_status' => PaymentStatus::UNPAID,
            'status' => OrderStatus::PROCESSING,
            'shipping_address' => 'Sân Cầu Lông Kỳ Hòa, Quận 10, TP. Hồ Chí Minh',
            'receiver_name' => 'Nguyễn Hoàng Nam',
            'receiver_phone' => '0988776655',
            'note' => 'Kiểm tra hàng trước khi thanh toán COD',
        ]);
        OrderItem::create([
            'order_id' => $order2->id,
            'product_variant_id' => $v3_1->id,
            'product_name' => $p3->name,
            'variant_name' => 'Đen Xanh - 4U',
            'quantity' => 1,
            'unit_price' => 980000,
            'total_price' => 980000,
            'attributes' => $v3_1->attributes,
        ]);
        OrderItem::create([
            'order_id' => $order2->id,
            'product_variant_id' => $v9_2->id,
            'product_name' => $p9->name,
            'variant_name' => 'Trắng Đỏ Đen - Size 41',
            'quantity' => 1,
            'unit_price' => 2850000,
            'total_price' => 2850000,
            'attributes' => $v9_2->attributes,
        ]);
        Payment::create([
            'order_id' => $order2->id,
            'transaction_id' => 'COD-20261019-002',
            'provider' => PaymentMethod::COD,
            'amount' => 3830000,
            'status' => PaymentStatus::PENDING,
        ]);
        Shipment::create([
            'order_id' => $order2->id,
            'tracking_code' => 'GHN-SG-998811',
            'carrier' => 'Giao Hàng Nhanh',
            'status' => 'shipping',
            'cod_amount' => 3830000,
            'shipped_at' => now()->subHours(6),
        ]);

        // Order 3: Pending Order for VIP Buyer
        $order3 = Order::create([
            'user_id' => $vipBuyer->id,
            'code' => 'ORD-20261019-003',
            'subtotal' => 1600000,
            'shipping_fee' => 0,
            'total' => 1600000,
            'payment_method' => PaymentMethod::COD,
            'payment_status' => PaymentStatus::UNPAID,
            'status' => OrderStatus::PENDING,
            'shipping_address' => 'Biệt thự Palm Garden, Quận 2, TP. Thủ Đức',
            'receiver_name' => 'Trần Linh Đan',
            'receiver_phone' => '0912345678',
            'note' => 'Giao vào buổi sáng',
        ]);
        OrderItem::create([
            'order_id' => $order3->id,
            'product_variant_id' => $v2_2->id,
            'product_name' => $p2->name,
            'variant_name' => 'Trắng Tuyết - 4U',
            'quantity' => 1,
            'unit_price' => 1250000,
            'total_price' => 1250000,
            'attributes' => $v2_2->attributes,
        ]);
        OrderItem::create([
            'order_id' => $order3->id,
            'product_variant_id' => $v15_1->id,
            'product_name' => $p15->name,
            'variant_name' => 'Đen Logo YY Trắng - Size M',
            'quantity' => 1,
            'unit_price' => 350000,
            'total_price' => 350000,
            'attributes' => $v15_1->attributes,
        ]);
        Payment::create([
            'order_id' => $order3->id,
            'transaction_id' => 'COD-20261019-003',
            'provider' => PaymentMethod::COD,
            'amount' => 1600000,
            'status' => PaymentStatus::PENDING,
        ]);
    }
}

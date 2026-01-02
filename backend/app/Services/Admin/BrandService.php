<?php

namespace App\Services\Admin;

use App\Models\Brand;
use App\Repositories\Interfaces\Admin\BrandRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class BrandService
{
    public function __construct(
        private readonly BrandRepositoryInterface $repo
    ) {}

    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repo->paginate($filters, $perPage);
    }

    public function create(array $data, ?UploadedFile $file = null): Brand
    {
        return DB::transaction(function () use ($data, $file) {
            // 1. Xử lý upload ảnh nếu có
            if ($file) {
                $data['logo_url'] = $this->uploadLogo($file);
            }

            // 2. Gọi repo lưu vào DB
            return $this->repo->create($data);
        });
    }

    public function update(Brand $brand, array $data, ?UploadedFile $file = null): Brand
    {
        return DB::transaction(function () use ($brand, $data, $file) {
            // 1. Nếu có file mới được upload
            if ($file) {
                // Xóa ảnh cũ nếu tồn tại
                $this->deleteLogo($brand->logo_url);
                // Upload ảnh mới
                $data['logo_url'] = $this->uploadLogo($file);
            }

            // 2. Update dữ liệu
            return $this->repo->update($brand, $data);
        });
    }

    public function delete(Brand $brand): void
    {
        DB::transaction(function () use ($brand) {
            // 1. Xóa ảnh trong folder storage
            $this->deleteLogo($brand->logo_url);
            
            // 2. Xóa record trong DB
            $this->repo->delete($brand);
        });
    }

    // --- Helper Functions ---

    private function uploadLogo(UploadedFile $file): string
    {
        // Lưu vào storage/app/public/brands
        // Trả về đường dẫn: brands/ten_file_hash.jpg
        return $file->store('brands', 'public');
    }

    private function deleteLogo(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
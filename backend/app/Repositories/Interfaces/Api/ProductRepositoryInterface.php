<?php

namespace App\Repositories\Interfaces\Api;

interface ProductRepositoryInterface
{
    /**
     * Lấy danh sách sản phẩm (có phân trang & lọc)
     * @param array $filters Các tham số lọc: keyword, category_id, price...
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getList(array $filters);

    /**
     * Tìm chi tiết sản phẩm đầy đủ (kèm quan hệ)
     * @param int $id
     * @return \App\Models\Product
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findDetail(int $id);

    /**
     * Lấy danh sách sản phẩm liên quan
     * @param int $currentId ID sản phẩm đang xem (để loại trừ)
     * @param int $categoryId ID danh mục của sản phẩm đó
     * @param int $limit Số lượng cần lấy (Mặc định 4)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRelated(int $currentId, int $categoryId, int $limit = 4);
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Brand extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'logo_url',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
    /**
     * Hàm này sẽ tự động chạy khi Model được khởi tạo
     */
    protected static function booted()
    {
        static::saved(function ($brand) {
            Cache::forget('api_brands_list');
        });

        static::deleted(function ($brand) {
            Cache::forget('api_brands_list');
        });
    }
}

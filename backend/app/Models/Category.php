<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'parent_id',
        'name',
        'image_url',
        'is_active',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // --- TỐI ƯU: Quan hệ đệ quy để load đa cấp (Level 1 -> n) ---
    public function childrenRecursive(): HasMany
    {
        return $this->children()->with('childrenRecursive');
    }
    
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
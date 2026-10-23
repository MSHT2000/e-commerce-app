<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'subcategory_id',
        'childcategory_id',
        'warehouse_id',
        'brand_id',
        'admin_id',
        'flash_deal_id',
        'pickup_point_id',
        'name',
        'slug',
        'code',
        'unit',
        'color',
        'size',
        'tags',
        'video',
        'purchase_price',
        'selling_price',
        'discount_price',
        'stock_quantity',
        'description',
        'thumbnail',
        'images',
        'featured',
        'today_deal',
        'status',
        'cash_on_delivery',
        'product_slider',
    ];

    protected $casts = [
        'images' => 'array',
        'featured' => 'boolean',
        'today_deal' => 'boolean',
        'status' => 'boolean',
        'cash_on_delivery' => 'boolean',
        'product_slider' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class, 'subcategory_id');
    }

    public function childCategory(): BelongsTo
    {
        return $this->belongsTo(ChildCategory::class, 'childcategory_id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Wharehouse::class, 'warehouse_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
}

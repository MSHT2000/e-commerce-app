<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubCategory extends Model
{
    protected $fillable = [
        "sub_category_name",
        "sub_category_slug",
        "category_id",

    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function childCategories(): HasMany
    {
        return $this->hasMany(ChildCategory::class);
    }

    public function products(): HasMany{
        return $this->hasMany(Product::class);
    }
}

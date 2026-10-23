<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Wharehouse extends Model
{
    use HasFactory;

    protected $table = 'warehouses';

    protected $fillable = [
        'name',
        'address',
        'phone',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'warehouse_id');
    }
    
}

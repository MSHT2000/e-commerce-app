<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PickupPoint extends Model
{
    use HasFactory;

    protected $table = 'pickup_point';

    protected $fillable = [
        'name',
        'address',
        'phone',
        'phone_two',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'page_name',
        'page_title',
        'page_slug',
        'page_description',
        'page_position',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SEO extends Model
{
    protected $table = 'seos';

    protected $fillable = [
        'meta_title',
        'meta_author',
        'meta_tag',
        'meta_description',
        'meta_keyword',
        'google_verification',
        'google_analytics',
        'alexa_verification',
        'google_adsense',
    ];
}

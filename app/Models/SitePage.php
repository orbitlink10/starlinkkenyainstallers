<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SitePage extends Model
{
    protected $fillable = [
        'meta_title',
        'meta_description',
        'page_title',
        'slug',
        'image_alt_text',
        'heading_2',
        'type',
        'page_description',
        'image_path',
    ];
}

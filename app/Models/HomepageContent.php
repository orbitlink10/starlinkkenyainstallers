<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageContent extends Model
{
    protected $fillable = [
        'hero_header_title',
        'hero_header_description',
        'hero_image_path',
        'why_choose_title',
        'why_choose_description',
        'products_section_title',
        'home_page_content',
    ];
}

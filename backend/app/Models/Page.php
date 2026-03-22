<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['chapter_id', 'image_url', 'page_number'])]
class Page extends Model
{
    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function getImageUrlAttribute($value)
    {
        return asset('storage/' . $value);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['manga_id', 'title', 'chapter_number'])]
class Chapter extends Model
{
    public function manga()
    {
        return $this->belongsTo(Manga::class);
    }
    
    public function pages()
    {
        return $this->hasMany(Page::class);
    }
}

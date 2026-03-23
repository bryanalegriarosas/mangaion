<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['title', 'slug', 'description', 'cover_image', 'author', 'status', 'user_id'])]
class Manga extends Model
{
    const STATUS_ONGOING = 'ongoing';
    const STATUS_COMPLETED = 'completed';

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }
}

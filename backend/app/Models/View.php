<?php

namespace App\Models;

use App\Models\Manga;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['manga_id', 'user_id', 'ip_address'])]
class View extends Model
{
    public function manga(): BelongsTo
    {
        return $this->belongsTo(Manga::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

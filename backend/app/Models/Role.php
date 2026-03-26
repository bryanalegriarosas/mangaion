<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name'])]
class Role extends Model
{
    const SUPER_ADMIN = 'super_admin';
    const ADMIN = 'admin';
    const OWNER = 'owner';
    const UPLOADER = 'uploader';
    const EDITOR = 'editor';
    const USER = 'user';

    const AVAILABLE_ROLES = [
        self::SUPER_ADMIN,
        self::ADMIN,
        self::OWNER,
        self::UPLOADER,
        self::EDITOR,
        self::USER,
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}

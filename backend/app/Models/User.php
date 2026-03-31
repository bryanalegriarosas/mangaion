<?php

namespace App\Models;

use App\Models\Favorite;
use App\Models\Rating;
use App\Models\ReadingHistory;
use App\Models\Role;
use App\Models\ScanGroup;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'last_name', 'username', 'avatar', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    protected $appends = ['avatar_url'];
    
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->avatar
                ? asset('storage/' . $this->avatar)
                : null,
        );
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function readingHistory(): HasMany
    {
        return $this->hasMany(ReadingHistory::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole(string $role): bool
    {
        return $this->roles->contains('name', $role);
    }

    public function scanGroups(): BelongsToMany
    {
        return $this->belongsToMany(ScanGroup::class)->withPivot('role')->withTimestamps();
    }

    public function hasScanRole(int $groupId, string $role): bool
    {
        return $this->scanGroups()
            ->where('scan_group_id', $groupId)
            ->wherePivot('role', $role)
            ->exists();
    }

    public function belongsToScan(int $groupId): bool
    {
        return $this->scanGroups()
            ->where('scan_group_id', $groupId)
            ->exists();
    }

    public function canUploadToScan(int $scanGroupId): bool
    {
        // 👑 acceso total
        if ($this->hasRole(Role::SUPER_ADMIN) || $this->hasRole(Role::ADMIN)) {
            return true;
        }

        return $this->scanGroups()
            ->where('scan_group_id', $scanGroupId)
            ->wherePivotIn('role', [Role::OWNER, Role::UPLOADER])
            ->exists();
    }
}


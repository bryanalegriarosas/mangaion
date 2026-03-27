<?php

namespace App\Models;

use App\Models\Role;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'last_name', 'username', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
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


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected static function booted(): void
    {
        static::saving(function (User $user): void {
            if (! $user->isDirty('role')) {
                return;
            }

            $roleName = match (mb_strtolower(trim((string) $user->role))) {
                'admin', 'administrador' => 'Administrador',
                'cliente', 'client' => 'Cliente',
                default => trim((string) $user->role),
            };

            $user->role_id = Role::query()->where('name', $roleName)->value('id');
        });
    }

    protected $hidden = [
        'password',
    ];

    public function roleRecord(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function hasPermission(string $permissionName): bool
    {
        return $this->roleRecord?->permissions()
            ->where('name', $permissionName)
            ->exists() ?? false;
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sales::class, 'user_id');
    }
}

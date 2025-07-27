<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'permissions',
        'description'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'permissions' => 'array',
        ];
    }


    protected static function booted(): void
    {
        static::creating(function (Role $role) {
            $role->permissions = static::getFormSchema();
        });

        static::deleting(function (Role $role) {
            if (in_array($role->id, [1, 2])) {
                throw new \Exception('You cannot delete this role (Super Admin or Default Role).');
            }

            $role->users()->update(['role_id' => 2]);
        });
    }

    public function users(): HasMany
    {
        return $this->hasMany(related: User::class);
    }

    public static function can(string $permission): bool
    {
        if (!str_contains($permission, ':')) {
            throw new \InvalidArgumentException("Invalid permission format. Expected 'section:key' given: {$permission}.");
        }

        $user = auth()->user();

        if (!$user || !$user->role) {
            return false;
        }

        $permissions = $user->role->permissions;

        [$section, $key] = explode(':', $permission, 2);


        if (!isset($permissions[$section])) {
            throw new \RuntimeException("Permission section [{$section}] not found.");
        }

        if (!isset($permissions[$section][$key])) {
            throw new \RuntimeException("Permission section [{$section}:{$key}] not found.");
        }

        return !empty($permissions[$section][$key] ?? null);
    }

    public static function updatePermission(int $id, array $data): void
    {
        static::find($id)->update(['permissions' => $data]);
    }

    protected static function getFormSchema(): array
    {
        $schemaPath = database_path('PermissionsFormSchema.php');

        if (!file_exists($schemaPath)) {
            throw new \RuntimeException('Permissions form schema file does not exist');
        }

        return require $schemaPath;
    }
}

<?php

namespace App\Traits;


use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions;
use App\Models\Role;
use Closure;


trait HasPermissions
{

    public static function getActions(
        bool|Closure $view = true,
        bool|Closure $edit = true,
        bool|Closure $delete = true
    ): array {
        return [
            Actions\ViewAction::make()
                ->visible($view),

            Actions\EditAction::make()
                ->visible($edit),

            Actions\DeleteAction::make()
                ->visible($delete),
        ];
    }


    private static function checkPermission(string $key): bool
    {
        if (!isset(static::$permissionKey)) {
            throw new \RuntimeException('permissionKey must be defined in the model');
        }

        return Role::can(static::$permissionKey . ':' . $key);
    }


    public static function canAccess(): bool
    {
        return static::checkPermission('view');
    }

    public static function canView(Model $record): bool
    {
        return static::checkPermission('view');
    }

    public static function canCreate(): bool
    {
        return static::checkPermission('create');
    }

    public static function canEdit(Model $record): bool
    {
        return static::checkPermission('edit');
    }

    public static function canDelete(Model $record): bool
    {
        return static::checkPermission('delete');
    }
}

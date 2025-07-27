<?php

namespace App\Resources;

use App\Resources\RoleResource\Pages;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use App\Traits\HasPermissions;
use App\Traits\HasNotifications;


class RoleResource extends Resource
{
    use HasPermissions, HasNotifications;

    protected static ?string $model = Role::class;
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationGroup = 'settings';
    protected static ?int $navigationSort = 2;
    protected static string $permissionKey = 'roles';

    public static function getNavigationLabel(): string
    {
        return trans('navigation.roles');
    }
    public static function getPluralModelLabel(): string
    {
        return trans('navigation.roles');
    }
    public static function getModelLabel(): string
    {
        return trans('field.role');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(100)
                    ->columnSpanFull()
                    ->label(trans('field.name')),

                Forms\Components\Textarea::make('description')
                    ->nullable()
                    ->rows(5)
                    ->columnSpanFull()
                    ->maxLength(999)
                    ->label(trans('field.description')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->label(trans('field.name')),
                Tables\Columns\TextColumn::make('description')->searchable()->words(10)->label(trans('field.description')),
            ])
            ->actions([
                Tables\Actions\Action::make("permissions")
                    ->icon('heroicon-o-lock-closed')
                    ->color('info')
                    ->modalHeading('Manage Permissions')
                    ->form(fn(Role $record) => static::formPermissions($record->permissions))
                    ->action(function (array $data, Role $record) {
                        $formattedData = [];

                        foreach ($data as $key => $value) {
                            Arr::set($formattedData, str_replace(':', '.', $key), $value);
                        }

                        Role::updatePermission($record->id, $formattedData);

                        static::sendNotificationSuccess('Permissions updated', 'Permissions have been successfully updated for the role.');
                    })
                    ->label(trans('field.permissions'))
                    ->visible(static::checkPermission('edit-permissions')),

                ...static::getActions(
                    delete: fn(Role $record) => !in_array($record->id, [1, 2]),
                ),
            ]);
    }

    public static function formPermissions(array $permissionsData): array
    {
        return array_map(function ($section, $permissions) {
            return Forms\Components\Section::make(ucwords(str_replace('-', ' ', $section)))
                ->schema([
                    Forms\Components\Grid::make(4)
                        ->schema(array_map(function ($permissionKey, $isChecked) use ($section) {
                            return Forms\Components\Checkbox::make("{$section}:{$permissionKey}")
                                ->label(ucwords(str_replace('-', ' ', $permissionKey)))
                                ->default($isChecked);
                        }, array_keys($permissions), $permissions))
                ]);
        }, array_keys($permissionsData), $permissionsData);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
        ];
    }
}

<?php

namespace App\Resources;

use App\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use App\Traits\HasPermissions;

class UserResource extends Resource
{
    use HasPermissions;

    protected static ?string $model = User::class;
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'settings';
    protected static ?int $navigationSort = 3;
    protected static string $permissionKey = 'users';

    public static function getNavigationLabel(): string
    {
        return trans('navigation.users');
    }
    public static function getPluralModelLabel(): string
    {
        return trans('navigation.users');
    }
    public static function getModelLabel(): string
    {
        return trans('field.user');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('avatar')
                    ->image()
                    ->default('avatars/default.png')
                    ->disk('images')
                    ->directory('avatars')
                    ->maxSize(1024)
                    ->columnSpanFull()
                    ->label(trans('field.avatar')),

                Forms\Components\TextInput::make('username')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->label(trans('field.username')),

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(50)
                    ->label(trans('field.name')),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->label(trans('field.email')),

                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->maxLength(14)
                    ->label(trans('field.phone')),

                Forms\Components\TextInput::make('password')
                    ->password()
                    ->minLength(8)
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn($state) => filled($state) ? Hash::make($state) : null)
                    ->dehydrated(fn($state) => filled($state))
                    ->label(trans('field.password')),

                Forms\Components\TextInput::make('password_confirmation')
                    ->password()
                    ->minLength(8)
                    ->maxLength(255)
                    ->dehydrated(false)
                    ->requiredWith('password')
                    ->same('password')
                    ->label(trans('field.password_confirmation')),

                Forms\Components\Select::make('role_id')
                    ->relationship('role', 'name')
                    ->native(false)
                    ->default(2)
                    ->searchable()
                    ->required()
                    ->preload()
                    ->columnSpanFull()
                    ->label(trans('field.role')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')->disk('images')->circular()->label(trans('field.avatar')),
                Tables\Columns\TextColumn::make('name')->searchable()->label(trans('field.name')),
                Tables\Columns\TextColumn::make('username')->searchable()->label(trans('field.username')),
                Tables\Columns\TextColumn::make('email')->searchable()->icon('heroicon-o-envelope')->label(trans('field.email')),
                Tables\Columns\TextColumn::make('type')->label(trans('field.type')),
                Tables\Columns\TextColumn::make('role.name')->icon('heroicon-o-shield-check')->label(trans('field.role')),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                // return $query->where('id', '!=', 1);
            })
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->relationship('role', 'name')
                    ->native(false)
                    ->searchable()
                    ->preload()
                    ->label(trans('field.role')),
            ])
            ->actions(static::getActions());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
        ];
    }
}

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
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('username')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(50),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->maxLength(14),

                Forms\Components\TextInput::make('password')
                    ->password()
                    ->minLength(8)
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn($state) => filled($state) ? Hash::make($state) : null)
                    ->dehydrated(fn($state) => filled($state)),

                Forms\Components\TextInput::make('password_confirmation')
                    ->password()
                    ->minLength(8)
                    ->maxLength(255)
                    ->dehydrated(false)
                    ->requiredWith('password')
                    ->same('password'),

                Forms\Components\Select::make('role_id')
                    ->relationship('role', 'name')
                    ->native(false)
                    ->default(2)
                    ->searchable()
                    ->required()
                    ->preload()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')->disk('images')->circular(),
                Tables\Columns\TextColumn::make('username')->searchable(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable()->icon('heroicon-m-envelope'),
                Tables\Columns\TextColumn::make('role.name')->icon('heroicon-o-shield-check'),
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
                    ->label(trans('filter.role')),
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

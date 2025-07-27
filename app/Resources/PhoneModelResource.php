<?php

namespace App\Resources;

use App\Enums\PhoneColors;
use App\Enums\PhoneStorages;
use App\Resources\PhoneModelResource\Pages;
use App\Models\PhoneModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use App\Traits\HasPermissions;

class PhoneModelResource extends Resource
{
    use HasPermissions;

    protected static ?string $model = PhoneModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $recordTitleAttribute = 'model';
    protected static ?int $navigationSort = 3;
    protected static string $permissionKey = 'phone_models';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('model')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->columnSpanFull(),

                Forms\Components\Select::make('storages')
                    ->options(PhoneStorages::class)
                    ->native(false)
                    ->multiple()
                    ->searchable()
                    ->required()
                    ->preload(),

                Forms\Components\Select::make('colors')
                    ->options(PhoneColors::class)
                    ->native(false)
                    ->multiple()
                    ->searchable()
                    ->required()
                    ->preload(),

                Forms\Components\Textarea::make('description')
                    ->required()
                    ->rows(5)
                    ->columnSpanFull()
                    ->maxLength(999),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('model')->searchable(),
                Tables\Columns\TextColumn::make('storages')->badge(),
                Tables\Columns\TextColumn::make('colors')->badge(),

                Tables\Columns\TextColumn::make('description')->searchable()->words(10),
            ])
            ->actions(static::getActions());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPhoneModels::route('/'),
        ];
    }
}

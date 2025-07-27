<?php

namespace App\Resources;

use App\Enums\ExpensesPaymentOptions;
use App\Resources\ExpensesResource\Pages;
use App\Models\Expenses;
use App\Traits\HasPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ExpensesResource extends Resource
{
    use HasPermissions;

    protected static ?string $model = Expenses::class;

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'expenses';
    protected static string $permissionKey = 'expenses';

    public static function getNavigationLabel(): string
    {
        return trans('navigation.expenses');
    }
    public static function getPluralModelLabel(): string
    {
        return trans('navigation.expenses');
    }
    public static function getModelLabel(): string
    {
        return trans('field.expenses');
    }

    public static function getWidgets(): array
    {
        return [
            ExpensesResource\Widgets\Overview::class,
        ];
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->label(trans('field.name')),

                Forms\Components\Select::make('payment_option')
                    ->options(ExpensesPaymentOptions::class)
                    ->native(false)
                    ->searchable()
                    ->required()
                    ->preload()
                    ->label(trans('field.payment_option')),

                Forms\Components\DatePicker::make('expenses_date')
                    ->required()
                    ->native(false)
                    ->default(now())
                    ->readOnly()
                    ->label(trans('field.expenses_date')),

                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->mask(\Filament\Support\RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->numeric()
                    ->maxValue(999999.99)
                    ->prefixIcon('heroicon-o-currency-dollar')
                    ->columnSpanFull()
                    ->label(trans('field.amount')),

                Forms\Components\Textarea::make('description')
                    ->nullable()
                    ->rows(5)
                    ->columnSpanFull()
                    ->maxLength(999)
                    ->label(trans('field.note')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->label(trans('field.name')),
                Tables\Columns\TextColumn::make('amount')->money()->label(trans('field.amount')),
                Tables\Columns\TextColumn::make('expenses_date')->date()->label(trans('field.expenses_date')),
                Tables\Columns\TextColumn::make('payment_option')->label(trans('field.payment_option')),
                Tables\Columns\TextColumn::make('description')->searchable()->words(10)->label(trans('field.note')),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_option')
                    ->options(ExpensesPaymentOptions::class)
                    ->native(false)
                    ->searchable()
                    ->preload()
                    ->label(trans('field.payment_option')),
            ])
            ->actions(static::getActions());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExpenses::route('/'),
        ];
    }
}

<?php

namespace App\Resources;

use App\Enums\PaymentOptions;
use App\Enums\PaymentTypes;
use App\Resources\PaymentResource\Pages;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\HasPermissions;

class PaymentResource extends Resource
{
    use HasPermissions;

    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string $permissionKey = 'payments';

    public static function getNavigationLabel(): string
    {
        return trans('navigation.payments');
    }
    public static function getPluralModelLabel(): string
    {
        return trans('navigation.payments');
    }
    public static function getModelLabel(): string
    {
        return trans('field.payment');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->native(false)
                    ->searchable()
                    ->required()
                    ->preload()
                    ->columnSpanFull()
                    ->label(trans('field.customer')),

                Forms\Components\Select::make('payment_type')
                    ->options(PaymentTypes::class)
                    ->native(false)
                    ->required()
                    ->preload()
                    ->label(trans('field.payment_type')),

                Forms\Components\Select::make('payment_option')
                    ->options(PaymentOptions::class)
                    ->native(false)
                    ->searchable()
                    ->required()
                    ->preload()
                    ->label(trans('field.payment_option')),

                Forms\Components\DateTimePicker::make('payment_date')
                    ->required()
                    ->displayFormat('M j, Y h:i')
                    ->native(false)
                    ->default(now())
                    ->readOnly()
                    ->label(trans('field.payment_date')),

                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->mask(\Filament\Support\RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->numeric()
                    ->maxValue(999999.99)
                    ->prefixIcon('heroicon-o-currency-dollar')
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
                Tables\Columns\TextColumn::make('payment_type')->label(trans('field.payment_type')),
                Tables\Columns\TextColumn::make('customer.name')->searchable()->label(trans('field.customer')),
                Tables\Columns\TextColumn::make('amount')->prefix('EGP ')->label(trans('field.amount')),
                Tables\Columns\TextColumn::make('payment_date')->dateTime('M j, Y h:i')->label(trans('field.payment_date')),
                Tables\Columns\TextColumn::make('payment_option')->label(trans('field.payment_option')),
                Tables\Columns\TextColumn::make('description')->searchable()->words(10)->label(trans('field.note')),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('customer')
                    ->relationship('customer', 'name')
                    ->native(false)
                    ->preload()
                    ->columnSpanFull()
                    ->label(trans('field.customer')),

                Tables\Filters\SelectFilter::make('payment_type')
                    ->options(PaymentTypes::class)
                    ->native(false)
                    ->preload()
                    ->label(trans('field.payment_type')),

                Tables\Filters\SelectFilter::make('payment_option')
                    ->options(PaymentOptions::class)
                    ->native(false)
                    ->preload()
                    ->label(trans('field.payment_option')),

                Tables\Filters\SelectFilter::make('date')
                    ->columnSpanFull()
                    ->label(trans('field.date')),
            ])
            ->filtersFormColumns(2)
            ->actions(static::getActions());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
        ];
    }
}

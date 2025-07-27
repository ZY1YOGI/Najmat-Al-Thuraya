<?php

namespace App\Resources;

use App\Enums\PhoneCountries;
use App\Enums\InvoiceTypes;
use App\Enums\PhoneStorages;
use App\Enums\Problems;
use App\Enums\PhoneSim;
use App\Resources\PhoneResource\Pages;
use App\Models\Phone;
use App\Models\PhoneModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

use App\Traits\HasPermissions;

class PhoneResource extends Resource
{
    use HasPermissions;

    protected static ?string $model = Phone::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $recordTitleAttribute = 'imei';
    protected static string $permissionKey = 'phones';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('phone_model_id')
                    ->relationship(name: 'phone_model', titleAttribute: 'model', modifyQueryUsing: fn(Builder $query) => $query->orderBy('created_at'))
                    ->required()
                    ->native(false)
                    ->preload()
                    ->reactive()
                    ->columnSpan(['default' => 12, 'md' => 6])
                    ->searchable(),

                Forms\Components\TextInput::make('imei')
                    ->required()
                    ->integer()
                    ->unique(ignoreRecord: true)
                    ->minLength(14)
                    ->maxLength(15)
                    ->rule('regex:/^3[0-9]{14}$/')
                    ->prefix('IMEI')
                    ->columnSpan(['default' => 12, 'md' => 6]),

                Forms\Components\Select::make('color')
                    ->required()
                    ->disabled(fn(Get $get): bool => blank($get('phone_model_id')))
                    ->options(function (Get $get) {
                        if ($phoneId = $get('phone_model_id')) {
                            $phone = PhoneModel::find($phoneId);
                            return collect($phone->colors)
                                ->mapWithKeys(fn($color) => [$color->value => $color->getLabel()])
                                ->toArray();
                        }

                        return [];
                    })
                    ->native(false)
                    ->preload()
                    ->columnSpan(['default' => 6, 'md' => 3]),

                Forms\Components\Select::make('storage')
                    ->required()
                    ->disabled(fn(Get $get): bool => blank($get('phone_model_id')))
                    ->options(function (Get $get) {
                        if ($phoneId = $get('phone_model_id')) {
                            $phone = PhoneModel::find($phoneId);
                            return collect($phone->storages)
                                ->mapWithKeys(fn($storage) => [$storage->value => $storage->getLabel()])
                                ->toArray();
                        }

                        return [];
                    })
                    ->native(false)
                    ->preload()
                    ->columnSpan(['default' => 6, 'md' => 2,]),


                Forms\Components\Select::make('country')
                    ->required()
                    ->options(PhoneCountries::class)
                    ->native(false)
                    ->preload()
                    ->placeholder(null)
                    ->columnSpan(['default' => 6, 'md' => 3]),

                Forms\Components\TextInput::make('battery')
                    ->required()
                    ->integer()
                    ->step(1)
                    ->minValue(50)
                    ->maxValue(100)
                    ->columnSpan(['default' => 6,'md' => 2]),


                Forms\Components\Select::make('sim_card')
                    ->required()
                    ->options(PhoneSim::class)
                    ->native(false)
                    ->preload()
                    ->placeholder(null)
                    ->columnSpan(['default' => 12,'md' => 2]),


                Forms\Components\Select::make('problems')
                    ->nullable()
                    ->options(Problems::class)
                    ->native(false)
                    ->multiple()
                    ->preload()
                    ->columnSpan(12),

                Forms\Components\Select::make('customer_buy_id')
                    ->relationship(name: 'customerBuy', titleAttribute: 'name', modifyQueryUsing: fn(Builder $query) => $query->orderBy('created_at'))
                    ->required()
                    ->native(false)
                    ->preload()
                    ->reactive()
                    ->columnSpan(['default' => 12,'md' => 12])
                    ->searchable()
                    ->label('Customer'),

                Forms\Components\Hidden::make('status')->default(InvoiceTypes::BUY),


                Forms\Components\TextInput::make('price')
                    ->required()
                    ->integer()
                    ->prefix('EGP')
                    ->step(50)
                    ->minValue(3000)
                    ->maxValue(100000)
                    ->columnSpan(12),

                Forms\Components\Textarea::make('description')
                    ->nullable()
                    ->rows(5)
                    ->columnSpan(12)
                    ->maxLength(999),

            ])->columns(12);
    }


    public static function formSell(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('customer_sell_id')
                    ->relationship(name: 'customerSell', titleAttribute: 'name')
                    ->native(false)
                    ->disableOptionWhen(fn (string $value, $record): bool => $value == $record->customer_buy_id)
                    ->required()
                    ->preload()
                    ->searchable()
                    ->columnSpan(6),

                Forms\Components\TextInput::make('price')
                    ->dehydrated(false)
                    ->prefix('EGP')
                    ->disabled()
                    ->columnSpan(2),

                Forms\Components\TextInput::make('price_sell')
                    ->required()
                    ->integer()
                    ->prefix('EGP')
                    ->step(50)
                    ->minValue(fn(Get $get): int => $get('price'))
                    ->maxValue(100000)
                    ->columnSpan(4),
            ])
            ->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('phone_model.model')->searchable(),
                Tables\Columns\TextColumn::make('customerBuy.name')->label('buy'),
                Tables\Columns\TextColumn::make('imei')->searchable(),
                Tables\Columns\TextColumn::make('color'),
                Tables\Columns\TextColumn::make('storage'),
                Tables\Columns\TextColumn::make('country'),
                Tables\Columns\TextColumn::make('battery')->suffix('%'),
                Tables\Columns\TextColumn::make('sim_card'),
                Tables\Columns\TextColumn::make('all_problems')->badge(),
                Tables\Columns\TextColumn::make('price')->prefix('EGP '),
                Tables\Columns\TextColumn::make('status')->badge(),
                // Tables\Columns\TextColumn::make('description')->searchable()->words(10),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('sell')
                    ->hidden(fn($record) => $record->isSell())
                    ->icon('heroicon-m-shopping-cart')
                    ->label(InvoiceTypes::SELL->getLabel())
                    ->color(InvoiceTypes::SELL->getColor())
                    ->form(fn(Form $form): Form => static::formSell($form))
                    ->mutateFormDataUsing(function (array $data) {
                        $data['status'] = InvoiceTypes::SELL;
                        return $data;
                    })
                    ->fillForm(fn($record) => $record->toArray())
                    ->action(function (array $data, $record) {
                        $record->update($data);
                    }),

                Tables\Actions\Action::make('return')
                    ->visible(fn($record) => $record->isSell())
                    ->icon('heroicon-m-shopping-cart')
                    ->label('Return')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status' => InvoiceTypes::BUY,
                            'customer_sell_id' => null,
                            'price_sell' => null
                        ]);
                    }),


                Tables\Actions\DeleteAction::make(),
            ]);
    }



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPhones::route('/'),
        ];
    }
}

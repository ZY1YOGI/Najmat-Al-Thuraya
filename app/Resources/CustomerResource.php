<?php

namespace App\Resources;

use App\Enums\InvoiceTypes;
use App\Enums\PaymentTypes;
use App\Resources\CustomerResource\Pages;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

use Illuminate\Database\Eloquent\Builder;


use App\Traits\HasPermissions;

class CustomerResource extends Resource
{
    use HasPermissions;

    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 3;
    protected static string $permissionKey = 'customers';

    public static function getNavigationLabel(): string
    {
        return trans('navigation.customers');
    }
    public static function getPluralModelLabel(): string
    {
        return trans('navigation.customers');
    }
    public static function getModelLabel(): string
    {
        return trans('field.customer');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('store_name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->label(trans('field.store_name')),

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->label(trans('field.name')),

                Forms\Components\TagsInput::make('phones')
                    ->required()
                    ->reorderable()
                    ->rules([
                        'array',
                        'min:1',
                        'max:6',
                    ])
                    ->nestedRecursiveRules([
                        'min:9',
                        'max:14',
                    ])
                    ->columnSpanFull()
                    ->label(trans('field.phones')),

                Forms\Components\TextInput::make('address')
                    ->required()
                    ->maxLength(999)
                    ->columnSpanFull()
                    ->label(trans('field.address')),

                Forms\Components\Textarea::make('description')
                    ->required()
                    ->rows(5)
                    ->columnSpanFull()
                    ->maxLength(999)
                    ->label(trans('field.description')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn(Builder $query) => $query
                    ->withSum('invoices_sell as sell_total', 'price_sell')
                    ->withSum('invoices_buy as buy_total', 'price')
                    ->withSum('payments_take_from as take_from', 'amount')
                    ->withSum('payments_give_his as give_his', 'amount')
            )
            ->columns([
                Tables\Columns\TextColumn::make('store_name')->label(trans('field.store_name'))->searchable(),
                Tables\Columns\TextColumn::make('name')->label(trans('field.name'))->searchable(),

                // Tables\Columns\TextColumn::make('phones')->label(trans('field.phones'))->badge(),
                // Tables\Columns\TextColumn::make('address')->label(trans('field.address'))->words(4),

                Tables\Columns\TextColumn::make('invoicesBuyCountWithTotal')->label(trans('field.buy'))->sortable(),
                Tables\Columns\TextColumn::make('invoicesSellCountWithTotal')->label(trans('field.sell'))->sortable(),

                Tables\Columns\TextColumn::make('take_from')->default(0)->label(trans('field.take_from'))->prefix('EGP ')->sortable(),
                Tables\Columns\TextColumn::make('give_his')->default(0)->label(trans('field.give_his'))->prefix('EGP ')->sortable(),

                Tables\Columns\TextColumn::make('net_total')->label(trans('field.net_total'))
                    ->getStateUsing(fn($record) => ($record->sell_total ?? 0) - ($record->buy_total ?? 0) + ($record->give_his ?? 0) - ($record->take_from ?? 0))
                    ->prefix('EGP ')
                    ->sortable(),
                // Tables\Columns\TextColumn::make('description')->searchable()->words(10),

            ])
            ->actions([
                Tables\Actions\Action::make('invoices_buy')
                    ->modalWidth('6xl')
                    ->color(InvoiceTypes::BUY->getColor())
                    ->infolist(fn(Infolist $infolist): Infolist => static::infolistInvoicesBuy($infolist))
                    ->label(trans('field.invoices_buy')),

                Tables\Actions\Action::make('invoices_sell')
                    ->modalWidth('6xl')
                    ->color(InvoiceTypes::SELL->getColor())
                    ->infolist(fn(Infolist $infolist): Infolist => static::infolistInvoicesSell($infolist))
                    ->label(trans('field.invoices_sell')),

                Tables\Actions\Action::make('transactions')
                    ->modalWidth('6xl')
                    ->color('gray')
                    ->infolist(fn(Infolist $infolist): Infolist => static::infolistTransactions($infolist))
                    ->label(trans('field.transactions')),

                ...static::getActions()
            ]);
    }


    public static function infolistTransactions(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\RepeatableEntry::make('payments')
                    ->schema([
                        Infolists\Components\TextEntry::make('payment_type')->label(trans('field.payment_type'))->inlineLabel()->columnSpan(4),
                        Infolists\Components\TextEntry::make('payment_option')->label(trans('field.payment_option'))->inlineLabel()->columnSpan(4),
                        Infolists\Components\TextEntry::make('payment_date')->label(trans('field.payment_date'))->dateTime('M j, Y h:i')->inlineLabel()->columnSpan(4),
                        Infolists\Components\TextEntry::make('amount')->label(trans('field.amount'))->prefix('EGP ')->numeric()->inlineLabel()->columnSpan(4),
                        Infolists\Components\TextEntry::make('description')->label(trans('field.description'))->inlineLabel()->alignCenter()->columnSpanFull(),
                    ])
                    ->columnSpanFull()
                    ->columns([
                        'default' => 8,
                        'md' => 12
                    ]),

                Infolists\Components\TextEntry::make('total_take_from')->prefix('EGP ')
                    ->state(fn($record) => $record->payments_take_from->sum('amount'))
                    ->inlineLabel()
                    ->color(PaymentTypes::TAKE_FROM->getColor())
                    ->numeric()
                    ->columnSpanFull(),

                Infolists\Components\TextEntry::make('total_give_his')->prefix('EGP ')
                    ->state(fn($record) => $record->payments_give_his->sum('amount'))
                    ->inlineLabel()
                    ->color(PaymentTypes::GIVE_HIS->getColor())
                    ->numeric()
                    ->columnSpanFull(),

            ])->columns(12);
    }


    public static function infolistInvoicesBuy(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\RepeatableEntry::make('invoices_buy')
                    ->schema([
                        Infolists\Components\TextEntry::make('phone_model.model')->inlineLabel()->columnSpan(6),
                        Infolists\Components\TextEntry::make('imei')->inlineLabel()->columnSpan(6),
                        Infolists\Components\TextEntry::make('color')->columnSpan(2),
                        Infolists\Components\TextEntry::make('storage')->columnSpan(2),
                        Infolists\Components\TextEntry::make('country')->columnSpan(2),
                        Infolists\Components\TextEntry::make('battery')->columnSpan(2),
                        Infolists\Components\TextEntry::make('sim_card')->columnSpan(2),
                        Infolists\Components\TextEntry::make('status')->columnSpan(1),
                        Infolists\Components\TextEntry::make('price')->inlineLabel()->prefix('EGP ')->numeric()->columnSpan(6),
                        Infolists\Components\TextEntry::make('price_sell')->inlineLabel()->columnSpan(6)->state(fn($record) => $record->price_sell !== null ? 'EGP ' . number_format($record->price_sell) : 'Not sold yet'),
                    ])
                    ->columnSpanFull()
                    ->columns(12),

                Infolists\Components\TextEntry::make('total')->prefix('EGP ')
                    ->state(fn($record) => $record->invoices_buy->sum('price'))
                    ->inlineLabel()
                    ->numeric()
                    ->columnSpanFull(),
            ]);
    }

    public static function infolistInvoicesSell(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\RepeatableEntry::make('invoices_sell')
                    ->schema([
                        Infolists\Components\TextEntry::make('phone_model.model')->inlineLabel()->columnSpan(6),
                        Infolists\Components\TextEntry::make('imei')->inlineLabel()->columnSpan(6),
                        Infolists\Components\TextEntry::make('color')->columnSpan(2),
                        Infolists\Components\TextEntry::make('storage')->columnSpan(2),
                        Infolists\Components\TextEntry::make('country')->columnSpan(2),
                        Infolists\Components\TextEntry::make('battery')->columnSpan(2),
                        Infolists\Components\TextEntry::make('sim_card')->columnSpan(2),
                        Infolists\Components\TextEntry::make('status')->columnSpan(1),
                        Infolists\Components\TextEntry::make('price')->inlineLabel()->prefix('EGP ')->columnSpan(6),
                        Infolists\Components\TextEntry::make('price_sell')->inlineLabel()->prefix('EGP ')->columnSpan(6),
                    ])
                    ->columnSpanFull()
                    ->columns(12),

                Infolists\Components\TextEntry::make('total')->prefix('EGP ')
                    ->state(fn($record) => $record->invoices_sell->sum('price_sell'))
                    ->inlineLabel()
                    ->numeric()
                    ->columnSpanFull(),

            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
        ];
    }
}

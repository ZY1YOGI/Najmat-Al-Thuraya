<?php

namespace App\Resources\PhoneResource\Pages;

use App\Resources\PhoneResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

use Filament\Resources\Components\Tab;
use App\Enums\InvoiceTypes;

class ListPhones extends ListRecords
{
    protected static string $resource = PhoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            Tab::make('All'),

            ...collect(InvoiceTypes::cases())
            ->mapWithKeys(fn($status): array => [
                $status->value => Tab::make($status->getLabel())
                    ->query(fn($query) => $query->where('status', $status->value)),
            ])
            ->toArray()
        ];
    }

}

<?php

namespace App\Resources\ExpensesResource\Pages;

use App\Resources\ExpensesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExpenses extends ListRecords
{
    protected static string $resource = ExpensesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ExpensesResource\Widgets\Overview::class,
        ];
    }
}

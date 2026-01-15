<?php

namespace App\Filament\Owner\Resources\QrTables\Pages;

use App\Filament\Owner\Resources\QrTables\QrTableResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListQrTables extends ListRecords
{
    protected static string $resource = QrTableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

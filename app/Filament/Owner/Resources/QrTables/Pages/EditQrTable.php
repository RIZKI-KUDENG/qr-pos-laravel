<?php

namespace App\Filament\Owner\Resources\QrTables\Pages;

use App\Filament\Owner\Resources\QrTables\QrTableResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditQrTable extends EditRecord
{
    protected static string $resource = QrTableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

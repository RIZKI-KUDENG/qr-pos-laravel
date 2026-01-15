<?php

namespace App\Filament\Owner\Resources\QrTables\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class QrTableForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('table_number')
                    ->required(),
            ]);
    }
}

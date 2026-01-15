<?php

namespace App\Filament\Owner\Resources\QrTables;

use App\Filament\Owner\Resources\QrTables\Pages\CreateQrTable;
use App\Filament\Owner\Resources\QrTables\Pages\EditQrTable;
use App\Filament\Owner\Resources\QrTables\Pages\ListQrTables;
use App\Filament\Owner\Resources\QrTables\Schemas\QrTableForm;
use App\Filament\Owner\Resources\QrTables\Tables\QrTablesTable;
use App\Models\QrTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class QrTableResource extends Resource
{
    protected static ?string $model = QrTable::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'QrTable';

    public static function form(Schema $schema): Schema
    {
        return QrTableForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QrTablesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListQrTables::route('/'),
            'create' => CreateQrTable::route('/create'),
            'edit' => EditQrTable::route('/{record}/edit'),
        ];
    }
}

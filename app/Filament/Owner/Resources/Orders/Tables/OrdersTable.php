<?php

namespace App\Filament\Owner\Resources\Orders\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Exporters\OrderExporter;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ExportAction;





class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tenant_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('qr_table_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('order_number')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()->color(
                        fn(string $state): string => match ($state) {
                            'paid' => 'success',
                            'pending' => 'warning',
                            'cancelled' => 'danger',
                            default => 'gray',
                        }
                    ),
                TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Ganti dengan kode Native Export ini:
                ExportAction::make() 
                    ->exporter(OrderExporter::class) 
                    ->label('Export Excel')
                    ->color('success')
                    ->formats([
                        \Filament\Actions\Exports\Enums\ExportFormat::Xlsx, // Opsi Excel
                        \Filament\Actions\Exports\Enums\ExportFormat::Csv,  // Opsi CSV
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

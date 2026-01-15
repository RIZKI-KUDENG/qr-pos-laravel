<?php

namespace App\Filament\Owner\Resources\QrTables\Tables;

use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\HtmlString;
use Filament\Support\Icons\Heroicon;

class QrTablesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('table_number')
                    ->searchable(),
                TextColumn::make('qr_code_url')
                    ->searchable(),
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
            ->recordActions([
                EditAction::make(),
                Action::make('qr_code_url')
                ->label('lihat Qr')
                ->icon(Heroicon::QrCode)
                ->modalHeading(fn ($record) => 'Qr Code - ' . $record->table_number)
                ->modalContent(function ($record) {
                    $tenantSlug = $record->tenant->slug;
                    $url = url("/menu/{$tenantSlug}/{$record->id}");
                    $qrCode = QrCode::size(250)->generate($url);
                    return new HtmlString('
                        <div style="display: flex; justify-content: center; align-items: center; flex-direction: column; padding: 20px;">
                            '.$qrCode.'
                            <p style="margin-top: 15px; font-weight: bold; color: gray;">
                                Scan untuk pesan
                            </p>
                            <a href="'.$url.'" target="_blank" style="color: blue; text-decoration: underline; margin-top: 5px; font-size: 0.9em;">
                                '.$url.'
                            </a>
                        </div>
                    ');
                })
                ->modalSubmitAction(false) 
                ->modalCancelAction(fn ($action) => $action->label('Tutup')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

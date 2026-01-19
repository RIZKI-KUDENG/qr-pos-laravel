<?php

namespace App\Filament\Exporters;

use App\Models\Order;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class OrderExporter extends Exporter
{
    protected static ?string $model = Order::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('order_number')
                ->label('No. Order'),
            ExportColumn::make('customer_name')
                ->label('Pelanggan'),
            ExportColumn::make('status'),
            ExportColumn::make('total')
                ->label('Total (Rp)'),
            ExportColumn::make('created_at')
                ->label('Tanggal Transaksi'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Laporan penjualan Anda telah selesai di-export dan ' . number_format($export->successful_rows) . ' baris berhasil diproses.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal di-export.';
        }

        return $body;
    }
}
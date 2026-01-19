<?php

namespace App\Filament\Owner\Widgets;

use App\Models\Order;
use Filament\Facades\Filament;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class SalesChart extends ChartWidget
{
    protected  ?string $heading = 'Grafik Penjualan (30 Hari Terakhir)';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $tenantId = Filament::getTenant()->id;

        // Mengambil data tren penjualan per hari
        $data = Trend::query(
                Order::query()
                    ->where('tenant_id', $tenantId)
                    ->whereIn('status', ['paid', 'completed'])
            )
            ->between(
                start: now()->subDays(30),
                end: now(),
            )
            ->perDay()
            ->sum('total');

        return [
            'datasets' => [
                [
                    'label' => 'Omzet',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#10B981', // Warna hijau
                    'fill' => 'start',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
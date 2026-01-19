<?php

namespace App\Filament\Owner\Widgets;

use App\Models\Order;
use Filament\Facades\Filament;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PeakHoursChart extends ChartWidget
{
    protected  ?string $heading = 'Jam Kesibukan Kafe';
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $tenantId = Filament::getTenant()->id;
        $peakHours = Order::select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as count'))
            ->where('tenant_id', $tenantId)
            ->whereIn('status', ['paid', 'completed'])
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        $data = [];
        $labels = [];
        for ($i = 0; $i < 24; $i++) {
            $labels[] = sprintf('%02d:00', $i);
            $data[] = $peakHours[$i] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Transaksi',
                    'data' => $data,
                    'backgroundColor' => '#F59E0B',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
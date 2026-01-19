<?php

namespace App\Filament\Owner\Widgets;

use App\Models\Order;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class DashboardStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $tenantId = Filament::getTenant()->id;

        // Hitung Omzet Hari Ini (Hanya status paid/completed)
        $todayRevenue = Order::where('tenant_id', $tenantId)
            ->whereIn('status', ['paid', 'completed'])
            ->whereDate('created_at', Carbon::today())
            ->sum('total');

        // Hitung Jumlah Order Hari Ini
        $todayOrders = Order::where('tenant_id', $tenantId)
            ->whereDate('created_at', Carbon::today())
            ->count();

        return [
            Stat::make('Omzet Hari Ini', 'Rp ' . number_format($todayRevenue, 0, ',', '.'))
                ->description('Pemasukan bersih hari ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Order Hari Ini', $todayOrders)
                ->description('Total pesanan masuk')
                ->color('primary'),
        ];
    }
}
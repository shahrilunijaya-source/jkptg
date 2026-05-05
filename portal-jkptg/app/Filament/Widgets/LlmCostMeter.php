<?php

namespace App\Filament\Widgets;

use App\Models\ChatbotSetting;
use App\Models\LlmApiLog;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LlmCostMeter extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $cb = ChatbotSetting::current();
        $mtd = (float) $cb->cost_month_to_date_rm;
        $cap = (float) $cb->cost_cap_rm;
        $pct = $cap > 0 ? min(100, round(($mtd / $cap) * 100, 1)) : 0;

        if ($pct >= 100) {
            $color = 'danger';
            $stateLabel = 'Quota habis - mod ringkas aktif';
        } elseif ($pct >= $cb->alert_threshold_pct) {
            $color = 'warning';
            $stateLabel = 'Hampir mencapai had (alert >=' . $cb->alert_threshold_pct . '%)';
        } else {
            $color = 'success';
            $stateLabel = 'Dalam had';
        }

        $callsThisMonth = LlmApiLog::where('created_at', '>=', now()->startOfMonth())->count();
        $successCalls = LlmApiLog::where('created_at', '>=', now()->startOfMonth())
            ->where('status', 'success')
            ->count();
        $successRate = $callsThisMonth > 0 ? round(($successCalls / $callsThisMonth) * 100, 1) : 0;

        return [
            Stat::make('Kos LLM bulan ini', 'RM ' . number_format($mtd, 2))
                ->description('daripada had RM ' . number_format($cap, 2) . ' (' . $pct . '%)')
                ->color($color)
                ->icon('heroicon-o-currency-dollar'),

            Stat::make('Status Kos', $stateLabel)
                ->color($color)
                ->icon($cb->kill_switch_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                ->description('Driver: ' . $cb->driver . ' / ' . $cb->model),

            Stat::make('Panggilan API bulan ini', number_format($callsThisMonth))
                ->description($successRate . '% berjaya (' . $successCalls . ' / ' . $callsThisMonth . ')')
                ->color($successRate >= 95 ? 'success' : ($successRate >= 80 ? 'warning' : 'danger'))
                ->icon('heroicon-o-bolt'),
        ];
    }
}

<?php

namespace App\Filament\Widgets;

use App\Models\VisitLog;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class VisitorChart extends ChartWidget
{
    protected static ?string $heading = 'Lawatan 7 Hari';
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $labels = [];
        $values = [];

        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::now()->subDays($i)->startOfDay();
            $next = $day->copy()->addDay();
            $labels[] = $day->isoFormat('D MMM');
            $values[] = VisitLog::whereBetween('created_at', [$day, $next])->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Lawatan',
                    'data' => $values,
                    'borderColor' => '#243D57',
                    'backgroundColor' => 'rgba(36, 61, 87, 0.15)',
                    'tension' => 0.35,
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => ['legend' => ['display' => false]],
            'scales' => [
                'y' => ['beginAtZero' => true, 'ticks' => ['precision' => 0]],
            ],
        ];
    }
}

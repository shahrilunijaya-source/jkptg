<?php

namespace App\Filament\Widgets;

use App\Models\ChatbotKnowledge;
use App\Models\Form;
use App\Models\News;
use App\Models\Page;
use App\Models\Service;
use App\Models\Tender;
use App\Models\User;
use App\Models\VisitLog;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $totalDownloads = (int) Form::sum('downloads_count');
        $visits24h = VisitLog::where('created_at', '>=', now()->subDay())->count();
        $openTenders = Tender::where('status', 'open')->where('closes_at', '>', now())->count();

        return [
            Stat::make('Halaman', Page::where('published', true)->count())
                ->description(Page::count() . ' jumlah')
                ->color('primary')
                ->icon('heroicon-o-document'),

            Stat::make('Perkhidmatan', Service::where('active', true)->count())
                ->description('Aktif')
                ->color('success')
                ->icon('heroicon-o-briefcase'),

            Stat::make('Berita & Pengumuman', News::count())
                ->description(News::where('important', true)->count() . ' penting')
                ->color('warning')
                ->icon('heroicon-o-newspaper'),

            Stat::make('Tender Terbuka', $openTenders)
                ->description(Tender::count() . ' jumlah iklan')
                ->color('info')
                ->icon('heroicon-o-document-currency-dollar'),

            Stat::make('Borang Muat Turun', number_format($totalDownloads))
                ->description(Form::count() . ' borang aktif')
                ->color('primary')
                ->icon('heroicon-o-arrow-down-tray'),

            Stat::make('Pengetahuan Chatbot', ChatbotKnowledge::where('active', true)->count())
                ->description('KB entries aktif')
                ->color('info')
                ->icon('heroicon-o-academic-cap'),

            Stat::make('Pengguna Sistem', User::count())
                ->description('Akaun berdaftar')
                ->color('gray')
                ->icon('heroicon-o-user-group'),

            Stat::make('Lawatan 24 jam', number_format($visits24h))
                ->description('Trafik portal')
                ->color('success')
                ->icon('heroicon-o-eye'),
        ];
    }
}

<?php

namespace App\Filament\Pages;

use Carbon\Carbon;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Facades\Auth;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Papan Pemuka';
    protected static ?int $navigationSort = 1;

    public function getColumns(): int|array
    {
        return ['md' => 2, 'xl' => 3];
    }

    public function getHeading(): string
    {
        $user = Auth::user();
        $hour = Carbon::now()->hour;

        $greeting = match(true) {
            $hour < 12 => 'Selamat Pagi',
            $hour < 17 => 'Selamat Tengahari',
            default    => 'Selamat Petang',
        };

        return "{$greeting}, {$user?->name}";
    }

    public function getSubheading(): ?string
    {
        return Carbon::now()->translatedFormat('l, d F Y') . ' · Portal JKPTG — Jabatan Ketua Pengarah Tanah dan Galian Persekutuan';
    }
}

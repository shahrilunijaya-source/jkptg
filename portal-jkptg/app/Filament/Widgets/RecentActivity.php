<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Spatie\Activitylog\Models\Activity;

class RecentActivity extends BaseWidget
{
    protected static ?string $heading = 'Aktiviti Terkini';
    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Activity::query()->latest()->limit(10))
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->dateTime('d M Y H:i')->size('xs'),
                Tables\Columns\TextColumn::make('causer.name')->label('Pengguna')->placeholder('Sistem'),
                Tables\Columns\BadgeColumn::make('event')->label('Tindakan')->colors([
                    'success' => 'created',
                    'primary' => 'updated',
                    'danger' => 'deleted',
                ]),
                Tables\Columns\TextColumn::make('subject_type')->label('Subjek')->formatStateUsing(fn ($state) => class_basename((string) $state)),
                Tables\Columns\TextColumn::make('description')->limit(50),
            ]);
    }
}

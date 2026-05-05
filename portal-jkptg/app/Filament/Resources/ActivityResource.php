<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Activitylog\Models\Activity;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Pentadbiran';
    protected static ?string $modelLabel = 'Log Audit';
    protected static ?string $pluralModelLabel = 'Log Audit';
    protected static ?string $slug = 'log-audit';
    protected static ?int $navigationSort = 90;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('event')->disabled(),
            Forms\Components\TextInput::make('description')->disabled(),
            Forms\Components\TextInput::make('subject_type')->disabled(),
            Forms\Components\TextInput::make('causer.name')->label('Pengguna')->disabled(),
            Forms\Components\KeyValue::make('properties')->disabled(),
            Forms\Components\DateTimePicker::make('created_at')->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->dateTime('d M Y H:i:s')->sortable()->label('Tarikh'),
                Tables\Columns\TextColumn::make('causer.name')->label('Pengguna')->placeholder('Sistem')->searchable(),
                Tables\Columns\BadgeColumn::make('event')->label('Tindakan')->colors([
                    'success' => 'created',
                    'primary' => 'updated',
                    'danger' => 'deleted',
                    'gray' => 'restored',
                ]),
                Tables\Columns\TextColumn::make('subject_type')->label('Subjek')->formatStateUsing(fn ($state) => class_basename((string) $state))->badge(),
                Tables\Columns\TextColumn::make('subject_id')->label('ID')->size('xs')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('description')->limit(60)->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('log_name')->size('xs')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('event')->options([
                    'created' => 'Dicipta', 'updated' => 'Dikemas kini',
                    'deleted' => 'Dipadam', 'restored' => 'Dipulihkan',
                ]),
                Tables\Filters\Filter::make('today')
                    ->label('Hari ini')
                    ->query(fn ($q) => $q->whereDate('created_at', now()->toDateString())),
                Tables\Filters\Filter::make('this_week')
                    ->label('Minggu ini')
                    ->query(fn ($q) => $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])),
            ])
            ->actions([Tables\Actions\ViewAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
            'view' => Pages\ViewActivity::route('/{record}'),
        ];
    }
}

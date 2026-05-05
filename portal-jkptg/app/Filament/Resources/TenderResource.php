<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TenderResource\Pages;
use App\Models\Tender;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TenderResource extends Resource
{
    use Translatable;

    protected static ?string $model = Tender::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-currency-dollar';
    protected static ?string $navigationGroup = 'Kandungan';
    protected static ?string $modelLabel = 'Tender';
    protected static ?string $pluralModelLabel = 'Tender';
    protected static ?int $navigationSort = 30;

    public static function getTranslatableLocales(): array
    {
        return ['ms', 'en'];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Iklan')->schema([
                Forms\Components\TextInput::make('reference_no')->required()->placeholder('JKPTG/T/01/2026'),
                Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('title')->required()->maxLength(255)->columnSpan(2),
                Forms\Components\Textarea::make('description')->rows(3)->columnSpan(2),
                Forms\Components\TextInput::make('doc_path')->maxLength(255)->columnSpan(2),
            ])->columns(2),

            Forms\Components\Section::make('Tarikh & Status')->schema([
                Forms\Components\DatePicker::make('opens_at'),
                Forms\Components\DateTimePicker::make('closes_at')->required(),
                Forms\Components\Select::make('status')->options([
                    'draft' => 'Draf', 'open' => 'Terbuka', 'closed' => 'Ditutup', 'awarded' => 'Diberikan',
                ])->required()->default('open'),
                Forms\Components\TextInput::make('estimated_value_rm')->numeric()->prefix('RM'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reference_no')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('title')->limit(40),
                Tables\Columns\TextColumn::make('closes_at')->dateTime('d M Y H:i')->sortable(),
                Tables\Columns\BadgeColumn::make('status')->colors([
                    'gray' => 'draft',
                    'success' => 'open',
                    'danger' => 'closed',
                    'primary' => 'awarded',
                ]),
                Tables\Columns\TextColumn::make('estimated_value_rm')->money('MYR')->toggleable(),
            ])
            ->defaultSort('closes_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'draft' => 'Draf', 'open' => 'Terbuka', 'closed' => 'Ditutup', 'awarded' => 'Diberikan',
                ]),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTenders::route('/'),
            'create' => Pages\CreateTender::route('/create'),
            'edit' => Pages\EditTender::route('/{record}/edit'),
        ];
    }
}

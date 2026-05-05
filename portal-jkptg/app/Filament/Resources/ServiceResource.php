<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ServiceResource extends Resource
{
    use Translatable;

    protected static ?string $model = Service::class;
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Perkhidmatan';
    protected static ?string $modelLabel = 'Perkhidmatan';
    protected static ?string $pluralModelLabel = 'Perkhidmatan';
    protected static ?int $navigationSort = 10;

    public static function getTranslatableLocales(): array
    {
        return ['ms', 'en'];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Identiti')->schema([
                Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true)->maxLength(255),
                Forms\Components\TextInput::make('name')->required()->maxLength(255),
                Forms\Components\Select::make('category')->options([
                    'tanah' => 'Tanah',
                    'pajakan' => 'Pajakan',
                    'lesen' => 'Lesen',
                    'strata' => 'Strata',
                    'pusaka' => 'Pusaka',
                ])->required(),
                Forms\Components\TextInput::make('icon')->maxLength(64)->helperText('Heroicon name e.g. document-text'),
            ])->columns(2),

            Forms\Components\Section::make('Kandungan')->schema([
                Forms\Components\Textarea::make('summary')->required()->rows(3),
                Forms\Components\Textarea::make('eligibility')->rows(3),
                Forms\Components\TagsInput::make('process_steps')->placeholder('Langkah baru')->helperText('Senarai langkah proses'),
                Forms\Components\TagsInput::make('required_documents')->placeholder('Dokumen baru'),
            ]),

            Forms\Components\Section::make('Metadata')->schema([
                Forms\Components\TextInput::make('processing_days')->numeric()->suffix('hari'),
                Forms\Components\TextInput::make('sop_path')->maxLength(255),
                Forms\Components\TextInput::make('carta_alir_path')->maxLength(255),
                Forms\Components\TextInput::make('sort')->numeric()->default(0),
                Forms\Components\Toggle::make('active')->default(true),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sort')->sortable()->size('xs'),
                Tables\Columns\TextColumn::make('slug')->searchable()->limit(32),
                Tables\Columns\TextColumn::make('name')->searchable()->limit(40),
                Tables\Columns\BadgeColumn::make('category'),
                Tables\Columns\TextColumn::make('processing_days')->suffix(' hari')->sortable(),
                Tables\Columns\IconColumn::make('active')->boolean(),
            ])
            ->defaultSort('sort')
            ->filters([
                Tables\Filters\SelectFilter::make('category')->options([
                    'tanah' => 'Tanah', 'pajakan' => 'Pajakan', 'lesen' => 'Lesen', 'strata' => 'Strata',
                ]),
                Tables\Filters\TernaryFilter::make('active'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}

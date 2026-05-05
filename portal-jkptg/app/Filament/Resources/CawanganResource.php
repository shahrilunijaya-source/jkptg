<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CawanganResource\Pages;
use App\Models\Cawangan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CawanganResource extends Resource
{
    use Translatable;

    protected static ?string $model = Cawangan::class;
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationGroup = 'Hubungi';
    protected static ?string $modelLabel = 'Cawangan';
    protected static ?string $pluralModelLabel = 'Cawangan';

    public static function getTranslatableLocales(): array
    {
        return ['ms', 'en'];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Pejabat')->schema([
                Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('state')->required()->maxLength(64),
                Forms\Components\TextInput::make('name')->required()->maxLength(255)->columnSpan(2),
                Forms\Components\Textarea::make('address')->required()->rows(3)->columnSpan(2),
                Forms\Components\Textarea::make('opening_hours')->rows(2)->columnSpan(2),
            ])->columns(2),

            Forms\Components\Section::make('Hubungan')->schema([
                Forms\Components\TextInput::make('phone')->tel(),
                Forms\Components\TextInput::make('fax'),
                Forms\Components\TextInput::make('email')->email(),
                Forms\Components\TextInput::make('lat')->numeric()->step(0.0000001),
                Forms\Components\TextInput::make('lng')->numeric()->step(0.0000001),
                Forms\Components\TextInput::make('sort')->numeric()->default(0),
                Forms\Components\Toggle::make('is_headquarters'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('is_headquarters')->boolean()->trueIcon('heroicon-s-star')->trueColor('warning'),
                Tables\Columns\TextColumn::make('state')->searchable(),
                Tables\Columns\TextColumn::make('name')->searchable()->limit(40),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('email')->copyable(),
            ])
            ->defaultSort('sort')
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCawangans::route('/'),
            'create' => Pages\CreateCawangan::route('/create'),
            'edit' => Pages\EditCawangan::route('/{record}/edit'),
        ];
    }
}

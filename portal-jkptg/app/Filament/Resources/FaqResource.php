<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqResource\Pages;
use App\Models\Faq;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FaqResource extends Resource
{
    use Translatable;

    protected static ?string $model = Faq::class;
    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static ?string $navigationGroup = 'Kandungan';
    protected static ?string $modelLabel = 'Soalan Lazim';
    protected static ?string $pluralModelLabel = 'Soalan Lazim';
    protected static ?int $navigationSort = 40;

    public static function getTranslatableLocales(): array
    {
        return ['ms', 'en'];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('category')->options([
                'umum' => 'Umum', 'pengambilan' => 'Pengambilan', 'pusaka' => 'Pusaka',
                'pajakan' => 'Pajakan', 'lesen' => 'Lesen', 'strata' => 'Strata',
            ])->required(),
            Forms\Components\Textarea::make('question')->required()->rows(2),
            Forms\Components\Textarea::make('answer')->required()->rows(4),
            Forms\Components\TextInput::make('sort')->numeric()->default(0),
            Forms\Components\Toggle::make('active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sort')->size('xs'),
                Tables\Columns\BadgeColumn::make('category'),
                Tables\Columns\TextColumn::make('question')->searchable()->limit(60),
                Tables\Columns\IconColumn::make('active')->boolean(),
            ])
            ->defaultSort('sort')
            ->filters([
                Tables\Filters\SelectFilter::make('category')->options([
                    'umum' => 'Umum', 'pengambilan' => 'Pengambilan', 'pusaka' => 'Pusaka',
                    'pajakan' => 'Pajakan', 'lesen' => 'Lesen',
                ]),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit' => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}

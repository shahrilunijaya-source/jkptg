<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PageResource extends Resource
{
    use Translatable;

    protected static ?string $model = Page::class;
    protected static ?string $navigationIcon = 'heroicon-o-document';
    protected static ?string $navigationGroup = 'Kandungan';
    protected static ?string $modelLabel = 'Halaman';
    protected static ?string $pluralModelLabel = 'Halaman';
    protected static ?int $navigationSort = 10;

    public static function getTranslatableLocales(): array
    {
        return ['ms', 'en'];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Kandungan')->schema([
                Forms\Components\TextInput::make('slug')
                    ->required()->maxLength(255)
                    ->helperText('URL slug, e.g. mengenai-jkptg')
                    ->columnSpan(2),
                Forms\Components\TextInput::make('title')->required()->maxLength(255)->columnSpan(2),
                Forms\Components\Textarea::make('meta_description')->maxLength(500)->rows(2)->columnSpan(2),
                Forms\Components\RichEditor::make('body')->required()->columnSpan(2),
            ])->columns(2),

            Forms\Components\Section::make('Metadata')->schema([
                Forms\Components\Select::make('parent_id')
                    ->relationship('parent', 'slug')
                    ->searchable()->preload()
                    ->label('Parent'),
                Forms\Components\TextInput::make('sort')->numeric()->default(0),
                Forms\Components\Toggle::make('published')->default(true),
                Forms\Components\TextInput::make('meta_title')->maxLength(255),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('slug')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('title')->searchable()->limit(40),
                Tables\Columns\TextColumn::make('sort')->sortable(),
                Tables\Columns\IconColumn::make('published')->boolean(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime('d M Y H:i')->sortable()->toggleable(),
            ])
            ->defaultSort('sort')
            ->filters([
                Tables\Filters\TernaryFilter::make('published'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}

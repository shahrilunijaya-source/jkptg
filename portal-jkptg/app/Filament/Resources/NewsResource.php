<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsResource\Pages;
use App\Models\News;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NewsResource extends Resource
{
    use Translatable;

    protected static ?string $model = News::class;
    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationGroup = 'Kandungan';
    protected static ?string $modelLabel = 'Berita / Pengumuman';
    protected static ?string $pluralModelLabel = 'Berita & Pengumuman';
    protected static ?int $navigationSort = 20;

    public static function getTranslatableLocales(): array
    {
        return ['ms', 'en'];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Item')->schema([
                Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
                Forms\Components\Select::make('type')->options([
                    'berita' => 'Berita',
                    'pengumuman' => 'Pengumuman',
                ])->required()->default('berita'),
                Forms\Components\TextInput::make('title')->required()->maxLength(255),
                Forms\Components\Textarea::make('excerpt')->rows(2),
                Forms\Components\RichEditor::make('body')->required(),
            ])->columns(2),

            Forms\Components\Section::make('Meta')->schema([
                Forms\Components\DateTimePicker::make('published_at')->default(now()),
                Forms\Components\DateTimePicker::make('expires_at'),
                Forms\Components\Toggle::make('important'),
                Forms\Components\TextInput::make('banner_path'),
                Forms\Components\Select::make('author_id')->relationship('author', 'name')->searchable()->preload(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\BadgeColumn::make('type')->colors([
                    'primary' => 'berita',
                    'warning' => 'pengumuman',
                ]),
                Tables\Columns\TextColumn::make('title')->searchable()->limit(50),
                Tables\Columns\IconColumn::make('important')->boolean(),
                Tables\Columns\TextColumn::make('published_at')->dateTime('d M Y')->sortable(),
                Tables\Columns\TextColumn::make('author.name')->toggleable(),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')->options([
                    'berita' => 'Berita', 'pengumuman' => 'Pengumuman',
                ]),
                Tables\Filters\TernaryFilter::make('important'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'edit' => Pages\EditNews::route('/{record}/edit'),
        ];
    }
}

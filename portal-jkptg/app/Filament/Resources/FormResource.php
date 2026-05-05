<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FormResource\Pages;
use App\Models\Form as FormModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FormResource extends Resource
{
    use Translatable;

    protected static ?string $model = FormModel::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Perkhidmatan';
    protected static ?string $modelLabel = 'Borang';
    protected static ?string $pluralModelLabel = 'Borang';
    protected static ?int $navigationSort = 20;

    public static function getTranslatableLocales(): array
    {
        return ['ms', 'en'];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Borang')->schema([
                Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('name')->required()->maxLength(255),
                Forms\Components\Textarea::make('description')->rows(2),
                Forms\Components\Select::make('category')->options([
                    'pengambilan' => 'Pengambilan',
                    'pusaka' => 'Pusaka',
                    'pajakan' => 'Pajakan',
                    'lesen' => 'Lesen',
                    'aduan' => 'Aduan',
                    'umum' => 'Umum',
                ])->required(),
            ])->columns(2),

            Forms\Components\Section::make('Fail')->schema([
                Forms\Components\FileUpload::make('file_path')
                    ->disk('public')->directory('borang')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(10 * 1024)
                    ->helperText('Maks 10 MB. PDF sahaja.'),
                Forms\Components\TextInput::make('version')->default('1.0'),
                Forms\Components\TextInput::make('file_size_bytes')->numeric()->suffix('B')->disabled(),
                Forms\Components\TextInput::make('downloads_count')->numeric()->disabled(),
                Forms\Components\Toggle::make('active')->default(true),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('slug')->searchable()->limit(30),
                Tables\Columns\TextColumn::make('name')->searchable()->limit(40),
                Tables\Columns\BadgeColumn::make('category'),
                Tables\Columns\TextColumn::make('version'),
                Tables\Columns\TextColumn::make('downloads_count')->numeric()->sortable(),
                Tables\Columns\IconColumn::make('active')->boolean(),
            ])
            ->defaultSort('category')
            ->filters([
                Tables\Filters\SelectFilter::make('category')->options([
                    'pengambilan' => 'Pengambilan', 'pusaka' => 'Pusaka', 'pajakan' => 'Pajakan',
                    'lesen' => 'Lesen', 'aduan' => 'Aduan', 'umum' => 'Umum',
                ]),
                Tables\Filters\TernaryFilter::make('active'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListForms::route('/'),
            'create' => Pages\CreateForm::route('/create'),
            'edit' => Pages\EditForm::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChatbotKnowledgeResource\Pages;
use App\Models\ChatbotKnowledge;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ChatbotKnowledgeResource extends Resource
{
    use Translatable;

    protected static ?string $model = ChatbotKnowledge::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Chatbot';
    protected static ?string $modelLabel = 'Pengetahuan Chatbot';
    protected static ?string $pluralModelLabel = 'Pangkalan Pengetahuan';

    public static function getTranslatableLocales(): array
    {
        return ['ms', 'en'];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Soal-Jawab')->schema([
                Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
                Forms\Components\Select::make('category')->options([
                    'umum' => 'Umum', 'pengambilan' => 'Pengambilan', 'pusaka' => 'Pusaka',
                    'pajakan' => 'Pajakan', 'lesen' => 'Lesen',
                ])->required(),
                Forms\Components\Textarea::make('question')->required()->rows(2)->columnSpan(2),
                Forms\Components\Textarea::make('answer')->required()->rows(5)->columnSpan(2),
            ])->columns(2),

            Forms\Components\Section::make('Padanan & Sumber')->schema([
                Forms\Components\TagsInput::make('keywords')->placeholder('kata kunci')->helperText('Kata kunci untuk padanan'),
                Forms\Components\TextInput::make('source_url')->url()->maxLength(512),
                Forms\Components\Toggle::make('active')->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('slug')->limit(28)->searchable(),
                Tables\Columns\BadgeColumn::make('category'),
                Tables\Columns\TextColumn::make('question')->limit(60)->searchable(),
                Tables\Columns\IconColumn::make('active')->boolean(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime('d M Y')->toggleable(),
            ])
            ->defaultSort('category')
            ->filters([
                Tables\Filters\SelectFilter::make('category')->options([
                    'umum' => 'Umum', 'pengambilan' => 'Pengambilan', 'pusaka' => 'Pusaka',
                    'pajakan' => 'Pajakan', 'lesen' => 'Lesen',
                ]),
                Tables\Filters\TernaryFilter::make('active'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChatbotKnowledge::route('/'),
            'create' => Pages\CreateChatbotKnowledge::route('/create'),
            'edit' => Pages\EditChatbotKnowledge::route('/{record}/edit'),
        ];
    }
}

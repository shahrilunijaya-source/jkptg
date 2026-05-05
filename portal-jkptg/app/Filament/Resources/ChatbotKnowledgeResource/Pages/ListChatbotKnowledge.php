<?php

namespace App\Filament\Resources\ChatbotKnowledgeResource\Pages;

use App\Filament\Resources\ChatbotKnowledgeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Concerns\Translatable;

class ListChatbotKnowledge extends ListRecords
{
    use Translatable;
    protected static string $resource = ChatbotKnowledgeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}

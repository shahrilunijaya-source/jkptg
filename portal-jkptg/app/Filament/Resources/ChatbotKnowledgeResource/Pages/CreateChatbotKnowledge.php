<?php

namespace App\Filament\Resources\ChatbotKnowledgeResource\Pages;

use App\Filament\Resources\ChatbotKnowledgeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateChatbotKnowledge extends CreateRecord
{
    use Translatable;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\LocaleSwitcher::make(),
        ];
    }
    protected static string $resource = ChatbotKnowledgeResource::class;
}

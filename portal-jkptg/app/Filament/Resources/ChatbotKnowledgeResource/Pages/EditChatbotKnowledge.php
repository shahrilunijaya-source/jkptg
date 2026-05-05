<?php

namespace App\Filament\Resources\ChatbotKnowledgeResource\Pages;

use App\Filament\Resources\ChatbotKnowledgeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\EditRecord\Concerns\Translatable;

class EditChatbotKnowledge extends EditRecord
{
    use Translatable;
    protected static string $resource = ChatbotKnowledgeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }
}

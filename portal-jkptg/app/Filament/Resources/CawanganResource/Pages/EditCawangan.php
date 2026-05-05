<?php

namespace App\Filament\Resources\CawanganResource\Pages;

use App\Filament\Resources\CawanganResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\EditRecord\Concerns\Translatable;

class EditCawangan extends EditRecord
{
    use Translatable;
    protected static string $resource = CawanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }
}

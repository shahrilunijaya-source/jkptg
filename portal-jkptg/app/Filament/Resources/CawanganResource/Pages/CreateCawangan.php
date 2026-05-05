<?php

namespace App\Filament\Resources\CawanganResource\Pages;

use App\Filament\Resources\CawanganResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateCawangan extends CreateRecord
{
    use Translatable;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\LocaleSwitcher::make(),
        ];
    }
    protected static string $resource = CawanganResource::class;
}

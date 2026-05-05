<?php

namespace App\Filament\Resources\CawanganResource\Pages;

use App\Filament\Resources\CawanganResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Concerns\Translatable;

class ListCawangans extends ListRecords
{
    use Translatable;
    protected static string $resource = CawanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}

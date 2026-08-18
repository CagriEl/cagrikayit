<?php

namespace App\Filament\Resources\CagriKayitlari\Pages;

use App\Filament\Resources\CagriKayitlari\CagriKaydiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCagriKayitlari extends ListRecords
{
    protected static string $resource = CagriKaydiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Yeni çağrı kaydı'),
        ];
    }
}

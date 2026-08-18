<?php

namespace App\Filament\Resources\CagriKayitlari\Pages;

use App\Filament\Resources\CagriKayitlari\CagriKaydiResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCagriKaydi extends EditRecord
{
    protected static string $resource = CagriKaydiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

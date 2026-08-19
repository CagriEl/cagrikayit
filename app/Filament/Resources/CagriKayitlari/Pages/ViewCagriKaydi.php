<?php

namespace App\Filament\Resources\CagriKayitlari\Pages;

use App\Filament\Resources\CagriKayitlari\CagriKaydiResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCagriKaydi extends ViewRecord
{
    protected static string $resource = CagriKaydiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->visible(fn (): bool => CagriKaydiResource::canEdit($this->getRecord())),
        ];
    }
}

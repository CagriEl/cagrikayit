<?php

namespace App\Filament\Resources\CagriKayitlari\Pages;

use App\Filament\Resources\CagriKayitlari\CagriKaydiResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCagriKaydi extends CreateRecord
{
    protected static string $resource = CagriKaydiResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['arayan_kisi_id'] = auth()->id();

        return $data;
    }
}

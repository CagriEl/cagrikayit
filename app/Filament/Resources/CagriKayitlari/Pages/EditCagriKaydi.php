<?php

namespace App\Filament\Resources\CagriKayitlari\Pages;

use App\Filament\Resources\CagriKayitlari\CagriKaydiResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCagriKaydi extends EditRecord
{
    protected static string $resource = CagriKaydiResource::class;

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);

        if (! CagriKaydiResource::canEdit($this->getRecord()) && CagriKaydiResource::canView($this->getRecord())) {
            $this->redirect(CagriKaydiResource::getUrl('view', ['record' => $this->getRecord()]));

            return;
        }

        parent::mount($record);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

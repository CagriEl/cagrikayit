<?php

namespace App\Filament\Resources\Concerns;

use Illuminate\Database\Eloquent\Model;

trait DeniesBaskanYardimcisiWrites
{
    protected static function canModifyRecords(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        return ! $user->isBaskanYardimcisi();
    }

    public static function canCreate(): bool
    {
        return static::canModifyRecords();
    }

    public static function canEdit(Model $record): bool
    {
        if (! static::canModifyRecords()) {
            return false;
        }

        return static::canAccessRecord($record);
    }

    public static function canView(Model $record): bool
    {
        if (! auth()->check()) {
            return false;
        }

        return static::canAccessRecord($record);
    }

    protected static function canAccessRecord(Model $record): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        if ($user->canViewAllCagriKayitlari()) {
            return true;
        }

        return (int) $record->getAttribute('arayan_kisi_id') === (int) $user->id;
    }
}

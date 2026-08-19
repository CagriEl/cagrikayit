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
        return static::canModifyRecords();
    }
}

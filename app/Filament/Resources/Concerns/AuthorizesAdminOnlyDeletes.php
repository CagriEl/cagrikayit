<?php

namespace App\Filament\Resources\Concerns;

use Illuminate\Database\Eloquent\Model;

trait AuthorizesAdminOnlyDeletes
{
    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->is_admin ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()?->is_admin ?? false;
    }
}

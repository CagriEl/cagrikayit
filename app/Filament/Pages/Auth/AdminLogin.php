<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Validation\ValidationException;

class AdminLogin extends BaseLogin
{
    protected function isUserAllowedToAccessPanel(Authenticatable $user): bool
    {
        if (parent::isUserAllowedToAccessPanel($user)) {
            return true;
        }

        throw ValidationException::withMessages([
            'data.email' => 'Bu hesap yönetici değil. Yönetim paneline yalnızca admin yetkisi olan kullanıcı girebilir. Personel girişi için /personel/login adresini kullanın.',
        ]);
    }
}

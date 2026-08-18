<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum Rol: string implements HasColor, HasLabel
{
    case Personel = 'personel';
    case BaskanYardimcisi = 'baskan_yardimcisi';
    case Baskan = 'baskan';

    public function getLabel(): string
    {
        return match ($this) {
            self::Personel => 'Personel',
            self::BaskanYardimcisi => 'Başkan Yardımcısı',
            self::Baskan => 'Başkan',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Personel => 'gray',
            self::BaskanYardimcisi => 'info',
            self::Baskan => 'success',
        };
    }
}

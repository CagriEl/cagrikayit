<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum CozumDurumu: string implements HasColor, HasLabel
{
    case Beklemede = 'beklemede';
    case DevamEdiyor = 'devam_ediyor';
    case Cozuldu = 'cozuldu';
    case Cozulemedi = 'cozulemedi';

    public function getLabel(): string
    {
        return match ($this) {
            self::Beklemede => 'Beklemede',
            self::DevamEdiyor => 'Devam ediyor',
            self::Cozuldu => 'Çözüldü',
            self::Cozulemedi => 'Çözülemedi',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Beklemede => 'warning',
            self::DevamEdiyor => 'info',
            self::Cozuldu => 'success',
            self::Cozulemedi => 'danger',
        };
    }
}

<?php

namespace App\Filament\Widgets;

use App\Enums\CozumDurumu;
use App\Models\CagriKaydi;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CagriOzeti extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Toplam çağrı', CagriKaydi::query()->count())
                ->description('Tüm kayıtlar'),
            Stat::make('Bekleyen', CagriKaydi::query()->where('cozum_durumu', CozumDurumu::Beklemede)->count())
                ->description('Henüz çözülmedi')
                ->color('warning'),
            Stat::make('Çözülen', CagriKaydi::query()->where('cozum_durumu', CozumDurumu::Cozuldu)->count())
                ->description('Tamamlanan çağrılar')
                ->color('success'),
        ];
    }
}

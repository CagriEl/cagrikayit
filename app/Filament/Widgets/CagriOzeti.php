<?php

namespace App\Filament\Widgets;

use App\Enums\CozumDurumu;
use App\Models\CagriKaydi;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class CagriOzeti extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $query = $this->kayitSorgusu();

        return [
            Stat::make('Toplam çağrı', (clone $query)->count())
                ->description($this->ozetAciklamasi()),
            Stat::make('Bekleyen', (clone $query)->where('cozum_durumu', CozumDurumu::Beklemede)->count())
                ->description('Henüz çözülmedi')
                ->color('warning'),
            Stat::make('Çözülen', (clone $query)->where('cozum_durumu', CozumDurumu::Cozuldu)->count())
                ->description('Tamamlanan çağrılar')
                ->color('success'),
        ];
    }

    protected function kayitSorgusu(): Builder
    {
        $query = CagriKaydi::query();
        $user = auth()->user();

        if ($user && ! $user->canViewAllCagriKayitlari()) {
            $query->where('arayan_kisi_id', $user->id);
        }

        return $query;
    }

    protected function ozetAciklamasi(): string
    {
        return auth()->user()?->canViewAllCagriKayitlari()
            ? 'Tüm kayıtlar'
            : 'Kendi kayıtlarınız';
    }
}

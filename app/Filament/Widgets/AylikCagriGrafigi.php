<?php

namespace App\Filament\Widgets;

use App\Enums\CozumDurumu;
use App\Models\CagriKaydi;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class AylikCagriGrafigi extends ChartWidget
{
    protected ?string $heading = 'Aylık çağrı kaydı';

    protected ?string $description = 'Seçilen yıldaki aylık çağrı sayıları';

    protected ?string $maxHeight = '320px';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function mount(): void
    {
        $this->filter ??= (string) now()->year;

        parent::mount();
    }

    public static function canView(): bool
    {
        return auth()->user()?->isBaskanYardimcisi() ?? false;
    }

    protected function getFilters(): ?array
    {
        $yil = now()->year;

        return [
            (string) $yil => (string) $yil,
            (string) ($yil - 1) => (string) ($yil - 1),
            (string) ($yil - 2) => (string) ($yil - 2),
        ];
    }

    protected function getData(): array
    {
        $yil = $this->secilenYil();
        $aylar = [
            1 => 'Ocak',
            2 => 'Şubat',
            3 => 'Mart',
            4 => 'Nisan',
            5 => 'Mayıs',
            6 => 'Haziran',
            7 => 'Temmuz',
            8 => 'Ağustos',
            9 => 'Eylül',
            10 => 'Ekim',
            11 => 'Kasım',
            12 => 'Aralık',
        ];

        $kayitlar = CagriKaydi::query()
            ->whereBetween('aranan_saat', [
                Carbon::create($yil, 1, 1)->startOfDay(),
                Carbon::create($yil, 12, 31)->endOfDay(),
            ])
            ->get(['aranan_saat', 'cozum_durumu']);

        $datasets = [];

        foreach (CozumDurumu::cases() as $durum) {
            $aylik = array_fill(1, 12, 0);

            foreach ($kayitlar as $kayit) {
                if ($kayit->cozum_durumu !== $durum) {
                    continue;
                }

                $aylik[$kayit->aranan_saat->month]++;
            }

            $datasets[] = [
                'label' => $durum->getLabel(),
                'data' => array_values($aylik),
                'backgroundColor' => match ($durum) {
                    CozumDurumu::Beklemede => '#f59e0b',
                    CozumDurumu::DevamEdiyor => '#3b82f6',
                    CozumDurumu::Cozuldu => '#10b981',
                    CozumDurumu::Cozulemedi => '#ef4444',
                },
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => array_values($aylar),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    /**
     * @return array<string, mixed>
     */
    protected function getOptions(): array
    {
        return [
            'scales' => [
                'x' => [
                    'stacked' => true,
                ],
                'y' => [
                    'stacked' => true,
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
        ];
    }

    protected function secilenYil(): int
    {
        $filtreler = $this->getFilters() ?? [];

        if (is_string($this->filter) && array_key_exists($this->filter, $filtreler)) {
            return (int) $this->filter;
        }

        return now()->year;
    }
}

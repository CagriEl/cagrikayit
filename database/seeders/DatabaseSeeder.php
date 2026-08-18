<?php

namespace Database\Seeders;

use App\Enums\CozumDurumu;
use App\Enums\Rol;
use App\Models\CagriKaydi;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@cagrikayit.test'],
            [
                'name' => 'Admin',
                'password' => 'password',
                'is_admin' => true,
                'rol' => Rol::Personel,
            ],
        );

        $personel = User::query()->updateOrCreate(
            ['email' => 'ayse@cagrikayit.test'],
            [
                'name' => 'Ayşe Demir',
                'password' => 'password',
                'is_admin' => false,
                'rol' => Rol::Personel,
            ],
        );

        User::query()->updateOrCreate(
            ['email' => 'baskan.yardimcisi@cagrikayit.test'],
            [
                'name' => 'Başkan Yardımcısı',
                'password' => 'password',
                'is_admin' => false,
                'rol' => Rol::BaskanYardimcisi,
            ],
        );

        if (CagriKaydi::query()->exists()) {
            return;
        }

        CagriKaydi::query()->create([
            'arayan_kisi_id' => $personel->id,
            'aranan_saat' => now()->subHours(2),
            'gorusulen_kisi' => 'Mehmet Yılmaz',
            'konu' => 'Fatura bilgisi sordu, ödeme tarihi netleştirildi.',
            'jira_talep_kodu' => 'DESTEK-142',
            'cozum_durumu' => CozumDurumu::Cozuldu,
        ]);

        CagriKaydi::query()->create([
            'arayan_kisi_id' => $admin->id,
            'aranan_saat' => now()->subMinutes(40),
            'gorusulen_kisi' => 'Elif Kaya',
            'konu' => 'Randevu değişikliği talebi. Geri dönüş bekleniyor.',
            'jira_talep_kodu' => null,
            'cozum_durumu' => CozumDurumu::Beklemede,
        ]);

        CagriKaydi::query()->create([
            'arayan_kisi_id' => $personel->id,
            'aranan_saat' => now()->subMonths(1)->setDay(12),
            'gorusulen_kisi' => 'Can Öztürk',
            'konu' => 'Sistem erişim sorunu aktarıldı.',
            'jira_talep_kodu' => 'DESTEK-118',
            'cozum_durumu' => CozumDurumu::Cozuldu,
        ]);

        CagriKaydi::query()->create([
            'arayan_kisi_id' => $admin->id,
            'aranan_saat' => now()->subMonths(2)->setDay(8),
            'gorusulen_kisi' => 'Zeynep Arslan',
            'konu' => 'Evrak eksikliği bildirildi.',
            'jira_talep_kodu' => null,
            'cozum_durumu' => CozumDurumu::DevamEdiyor,
        ]);
    }
}

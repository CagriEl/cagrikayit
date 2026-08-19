<?php

namespace Tests\Feature;

use App\Enums\CozumDurumu;
use App\Enums\Rol;
use App\Filament\Resources\CagriKayitlari\CagriKaydiResource;
use App\Filament\Widgets\AylikCagriGrafigi;
use App\Models\CagriKaydi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CagriKaydiPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_personel_login(): void
    {
        $this->get('/personel/cagri-kayitlari')->assertRedirect('/personel/login');
    }

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $this->get('/admin/personeller')->assertRedirect('/admin/login');
    }

    public function test_user_can_view_call_records(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/personel/cagri-kayitlari')
            ->assertOk()
            ->assertSee('Arayan kişi')
            ->assertSee('Jira talep kodu');
    }

    public function test_regular_user_cannot_access_admin_panel(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get('/admin/personeller')
            ->assertForbidden();
    }

    public function test_admin_can_manage_personnel(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get('/admin/personeller')
            ->assertOk()
            ->assertSee('Personeller')
            ->assertSee('Rol');
    }

    public function test_vice_president_can_see_monthly_chart(): void
    {
        $user = User::factory()->baskanYardimcisi()->create();

        $this->actingAs($user);

        $this->assertTrue(AylikCagriGrafigi::canView());

        Livewire::test(AylikCagriGrafigi::class)
            ->assertSuccessful()
            ->assertSee('Aylık çağrı kaydı');
    }

    public function test_regular_user_cannot_see_monthly_chart(): void
    {
        $user = User::factory()->create(['rol' => Rol::Personel]);

        $this->actingAs($user);

        $this->assertFalse(AylikCagriGrafigi::canView());
    }

    public function test_only_admin_can_delete_call_records(): void
    {
        $kayit = CagriKaydi::query()->create([
            'arayan_kisi_id' => User::factory()->create()->id,
            'aranan_saat' => now(),
            'gorusulen_kisi' => 'Test Kişi',
            'konu' => 'Test konu',
            'cozum_durumu' => CozumDurumu::Beklemede,
        ]);

        $personel = User::factory()->create(['is_admin' => false]);
        $this->actingAs($personel);
        $this->assertFalse(CagriKaydiResource::canDelete($kayit));
        $this->assertFalse(CagriKaydiResource::canDeleteAny());

        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);
        $this->assertTrue(CagriKaydiResource::canDelete($kayit));
        $this->assertTrue(CagriKaydiResource::canDeleteAny());
    }

    public function test_vice_president_can_only_read_call_records(): void
    {
        $kayit = CagriKaydi::query()->create([
            'arayan_kisi_id' => User::factory()->create()->id,
            'aranan_saat' => now(),
            'gorusulen_kisi' => 'Test Kişi',
            'konu' => 'Test konu',
            'cozum_durumu' => CozumDurumu::Beklemede,
        ]);

        $baskanYardimcisi = User::factory()->baskanYardimcisi()->create();
        $this->actingAs($baskanYardimcisi);

        $this->get('/personel/cagri-kayitlari')->assertOk();
        $this->assertTrue(CagriKaydiResource::canViewAny());
        $this->assertFalse(CagriKaydiResource::canCreate());
        $this->assertFalse(CagriKaydiResource::canEdit($kayit));
        $this->assertFalse(CagriKaydiResource::canDelete($kayit));

        $personel = User::factory()->create(['rol' => Rol::Personel]);
        $this->actingAs($personel);

        $this->assertTrue(CagriKaydiResource::canCreate());
        $this->assertTrue(CagriKaydiResource::canEdit($kayit));
    }
}

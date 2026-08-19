<?php

namespace App\Models;

use App\Enums\CozumDurumu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CagriKaydi extends Model
{
    protected $table = 'cagri_kayitlari';

    protected $fillable = [
        'arayan_kisi_id',
        'aranan_saat',
        'arama_bitis_saat',
        'gorusulen_kisi',
        'konu',
        'jira_talep_kodu',
        'cozum_durumu',
    ];

    protected function casts(): array
    {
        return [
            'aranan_saat' => 'datetime',
            'arama_bitis_saat' => 'datetime',
            'cozum_durumu' => CozumDurumu::class,
        ];
    }

    public function arayanKisi(): BelongsTo
    {
        return $this->belongsTo(User::class, 'arayan_kisi_id');
    }
}

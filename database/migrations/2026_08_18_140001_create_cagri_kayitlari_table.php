<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cagri_kayitlari', function (Blueprint $table) {
            $table->id();
            $table->string('arayan_kisi');
            $table->dateTime('aranan_saat');
            $table->foreignId('gorusulen_kisi_id')->constrained('users')->restrictOnDelete();
            $table->text('konu');
            $table->string('cozum_durumu');
            $table->foreignId('kaydeden_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cagri_kayitlari');
    }
};

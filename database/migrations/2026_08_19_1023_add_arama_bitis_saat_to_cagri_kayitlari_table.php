<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cagri_kayitlari', function (Blueprint $table) {
            $table->dateTime('arama_bitis_saat')->nullable()->after('aranan_saat');
        });
    }

    public function down(): void
    {
        Schema::table('cagri_kayitlari', function (Blueprint $table) {
            $table->dropColumn('arama_bitis_saat');
        });
    }
};

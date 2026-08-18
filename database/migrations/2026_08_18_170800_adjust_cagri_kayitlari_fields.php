<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cagri_kayitlari', function (Blueprint $table) {
            $table->foreignId('arayan_kisi_id')->nullable()->after('id')->constrained('users')->restrictOnDelete();
            $table->string('gorusulen_kisi')->nullable()->after('aranan_saat');
            $table->string('jira_talep_kodu')->nullable()->after('konu');
        });

        $kayitlar = DB::table('cagri_kayitlari')->get();

        foreach ($kayitlar as $kayit) {
            $gorusulenAd = DB::table('users')->where('id', $kayit->gorusulen_kisi_id)->value('name');

            DB::table('cagri_kayitlari')->where('id', $kayit->id)->update([
                'arayan_kisi_id' => $kayit->kaydeden_id,
                'gorusulen_kisi' => $gorusulenAd ?: $kayit->arayan_kisi,
            ]);
        }

        Schema::table('cagri_kayitlari', function (Blueprint $table) {
            $table->dropConstrainedForeignId('gorusulen_kisi_id');
            $table->dropConstrainedForeignId('kaydeden_id');
            $table->dropColumn('arayan_kisi');
        });
    }

    public function down(): void
    {
        Schema::table('cagri_kayitlari', function (Blueprint $table) {
            $table->string('arayan_kisi')->nullable();
            $table->foreignId('gorusulen_kisi_id')->nullable()->constrained('users')->restrictOnDelete();
            $table->foreignId('kaydeden_id')->nullable()->constrained('users')->nullOnDelete();
        });

        Schema::table('cagri_kayitlari', function (Blueprint $table) {
            $table->dropConstrainedForeignId('arayan_kisi_id');
            $table->dropColumn(['gorusulen_kisi', 'jira_talep_kodu']);
        });
    }
};

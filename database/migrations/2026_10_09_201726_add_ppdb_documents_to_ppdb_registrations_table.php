<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ppdb_registrations', function (Blueprint $table) {
            $table->string('kk_file')->nullable()->after('ppdb_wave_id');
            $table->string('akta_file')->nullable()->after('kk_file');
            $table->string('rapor_file')->nullable()->after('akta_file');
            $table->string('photo')->nullable()->after('rapor_file');
            $table->boolean('kk_verified')->default(false)->after('photo');
            $table->boolean('akta_verified')->default(false)->after('kk_verified');
            $table->boolean('rapor_verified')->default(false)->after('akta_verified');
            $table->boolean('photo_verified')->default(false)->after('rapor_verified');
            $table->text('verification_note')->nullable()->after('photo_verified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppdb_registrations', function (Blueprint $table) {
            $table->dropColumn([
                'kk_file',
                'akta_file',
                'rapor_file',
                'photo',
                'kk_verified',
                'akta_verified',
                'rapor_verified',
                'photo_verified',
                'verification_note',
            ]);
        });
    }
};

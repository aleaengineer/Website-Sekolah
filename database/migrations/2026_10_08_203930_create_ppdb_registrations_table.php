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
        Schema::create('ppdb_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique();
            $table->string('student_name');
            $table->string('birth_place');
            $table->date('birth_date');
            $table->string('gender', 1);
            $table->string('previous_school');
            $table->string('parent_name');
            $table->string('parent_phone');
            $table->text('address');
            $table->string('jalur')->default('zonasi');
            $table->string('status')->default('menunggu');
            $table->timestamps();

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppdb_registrations');
    }
};

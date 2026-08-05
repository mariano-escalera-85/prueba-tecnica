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
        Schema::create('estados', function (Blueprint $table) {
            $table->id();
            $table->string('cvegeo');
            $table->string('cve_ent')->unique();
            $table->string('nomgeo');
            $table->string('nom_abrev');
            $table->integer('pob_total');
            $table->integer('pob_femenina');
            $table->integer('pob_masculina');
            $table->integer('total_viviendas_habitadas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estados');
    }
};

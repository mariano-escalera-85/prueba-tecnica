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
        Schema::create('municipios', function (Blueprint $table) {
            $table->id();

            $table->string('cvegeo')->unique();
            $table->string('cve_ent');
            $table->string('cve_mun');
            $table->string('nomgeo');
            $table->string('cve_cab');
            $table->string('nom_cab')->nullable()->default(null);
            $table->integer('pob_total')->nullable()->default(null);
            $table->integer('pob_femenina')->nullable()->default(null);
            $table->integer('pob_masculina')->nullable()->default(null);
            $table->integer('total_viviendas_habitadas')->nullable()->default(null);

            $table->unique(['cve_ent', 'cve_mun'], 'cvegeo_compound_unique');

            $table->foreign('cve_ent')->references('cve_ent')->on('estados')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('municipios');
    }
};

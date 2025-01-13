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
        Schema::create('association_modele_parametres', function (Blueprint $table) {
            $table->id();
            $table->integer('valeur');
            $table->foreignId('id_modele')->constrained('modele_abonnements')->onDelete('cascade');
            $table->foreignId('id_parametre')->constrained('parametre_modeles')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('association_modele_parametres');
    }
};

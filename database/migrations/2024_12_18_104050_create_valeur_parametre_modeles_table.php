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
        Schema::create('valeur_parametre_modeles', function (Blueprint $table) {
            $table->id();
            $table->integer('valeur');
            $table->foreignId('id_association_modele')->constrained('association_modele_parametres')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valeur_parametre_modeles');
    }
};

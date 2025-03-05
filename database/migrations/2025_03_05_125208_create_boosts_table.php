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
        Schema::create('boosts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_bien')->constrained('biens')->onDelete('cascade');
            $table->enum('type_boost', ['top', 'mise_en_avant', 'auto-remontee']);
            $table->enum('unite_duree', ['jour', 'semaine', 'mois', 'annee']);
            $table->integer('duree');
            $table->dateTime('date_debut')->default(now());
            $table->dateTime('date_fin')->nullable();
            $table->enum('statut', ['actif', 'expiré'])->default('actif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boosts');
    }
};

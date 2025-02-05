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
        Schema::create('biens', function (Blueprint $table) {
            $table->id();
            $table->string('titre')->nullable(); // Titre obligatoire
            $table->text('description')->nullable(); // Peut être nul
            $table->decimal('prix', 12, 2)->nullable(); // Peut être nul
            $table->string('lieu')->nullable(); // Peut être nul
            $table->datetime('datePublication')->nullable(); // Peut être nul
            $table->string('statut');
            $table->string('type_offre')->nullable(); // Peut être nul
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade'); // Obligatoire
            $table->foreignId('id_categorie_bien')->constrained('categorie_biens')->onDelete('cascade'); // Obligatoire
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biens');
    }
};

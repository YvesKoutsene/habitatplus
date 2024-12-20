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
        Schema::create('alerte_recherches', function (Blueprint $table) {
            $table->id();
            $table->string('lieu');
            $table->decimal('budget', 10, 2);
            $table->string('statut');
            $table->string('type_offre');
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_categorie_bien')->constrained('categorie_biens')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerte_recherches');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('voitures', function (Blueprint $table) {
            $table->id();
            $table->string('marque', 50);
            $table->string('modele', 100);
            $table->year('annee_debut');
            $table->string('motorisation', 50);
            $table->unsignedInteger('puissance');
            $table->string('immatriculation_type', 20)->nullable();
            $table->timestamps();
            $table->index(['marque', 'modele']);
      
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voitures');
    }
};

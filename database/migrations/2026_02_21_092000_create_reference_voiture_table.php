<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reference_voiture', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reference_id')->constrained('references')->onDelete('cascade');
            $table->foreignId('voiture_id')->constrained('voitures')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['reference_id', 'voiture_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reference_voiture');
    }
};

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('commande_references', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained()->onDelete('cascade');
            $table->foreignId('reference_id')->constrained('references')->onDelete('cascade');
            $table->unsignedInteger('quantite');
            $table->decimal('prix_unitaire', 10, 2);
            $table->decimal('total_ligne', 10, 2);
            $table->timestamps();
            
            $table->unique(['commande_id', 'reference_id']);
        });
    }
    public function down(): void
    {
        Schema::table('voitures', function (Blueprint $table) {
            $table->dropForeign(['serie_id']);
        });

        Schema::dropIfExists('series');
    }
};

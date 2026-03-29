<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pieces', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 150);
            $table->string('slug', 150)->unique();
            $table->string('reference_fournisseur', 50)->nullable();
            $table->decimal('prix', 10, 2);
            $table->text('description')->nullable();
            $table->json('caracteristiques')->nullable();
            $table->json('images')->nullable();

            $table->foreignId('categorie_id')->constrained()->onDelete('cascade');
            $table->foreignId('marque_id')->constrained()->onDelete('cascade');

            $table->boolean('is_visible')->default(true);
            $table->integer('position')->default(0);
            $table->timestamps();

            // Index performance critique
            $table->index(['marque_id', 'categorie_id']);
            $table->index('is_visible');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pieces');
    }
};

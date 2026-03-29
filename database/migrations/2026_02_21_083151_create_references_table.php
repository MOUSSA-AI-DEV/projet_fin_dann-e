<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('references', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 50)->unique();  
            $table->string('nom', 100);              
            $table->string('slug', 100)->unique();
            $table->foreignId('piece_id')->constrained()->onDelete('cascade');
            $table->text('description')->nullable();  
            $table->string('garantie', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('position')->default(0);
            $table->integer('stock')->default(0);
            $table->decimal('prix', 8, 2)->nullable();
            $table->timestamps();

            $table->index(['piece_id', 'is_active']);
            $table->index('reference');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('references');
    }
};

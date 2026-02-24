<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('marques', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 50);
            $table->string('logo_url', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('nom');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marques');
    }
};

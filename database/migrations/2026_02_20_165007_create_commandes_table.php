<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('numero_commande', 20)->unique();
            $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending');
            $table->enum('payment_method', ['card', 'paypal', 'transfer'])->nullable();
            $table->string('payment_id', 100)->nullable();
            $table->string('facture_pdf', 255)->nullable();
            $table->text('notes_client')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'payment_status']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};

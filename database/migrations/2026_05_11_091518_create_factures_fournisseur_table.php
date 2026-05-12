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
        Schema::create('factures_fournisseur', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 50)->unique();
            $table->string('fournisseur', 150);
            $table->date('date_facture');
            $table->decimal('taux_conversion', 8, 4)->default(11);
            $table->decimal('coefficient_charges', 5, 4)->default(0.10);
            $table->decimal('coefficient_beneficiaire', 5, 4)->default(0.20);
            $table->decimal('total_achat_euro', 12, 2)->default(0);
            $table->decimal('total_achat_mad', 12, 2)->default(0);
            $table->decimal('total_vente', 12, 2)->default(0);
            $table->string('fichier_original')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factures_fournisseur');
    }
};

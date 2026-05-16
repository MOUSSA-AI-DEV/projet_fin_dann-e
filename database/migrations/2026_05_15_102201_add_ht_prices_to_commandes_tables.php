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
        Schema::table('commandes', function (Blueprint $table) {
            $table->decimal('total_ht', 15, 2)->default(0)->after('total');
            $table->decimal('tva', 15, 2)->default(0)->after('total_ht');
        });

        Schema::table('commande_references', function (Blueprint $table) {
            $table->decimal('prix_unitaire_ht', 15, 2)->default(0)->after('prix_unitaire');
            $table->decimal('total_ligne_ht', 15, 2)->default(0)->after('total_ligne');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn(['total_ht', 'tva']);
        });

        Schema::table('commande_references', function (Blueprint $table) {
            $table->dropColumn(['prix_unitaire_ht', 'total_ligne_ht']);
        });
    }
};

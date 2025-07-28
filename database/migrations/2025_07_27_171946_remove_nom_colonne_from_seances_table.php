<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('seances', function (Blueprint $table) {
            // 1. Supprimer la clé étrangère
            $table->dropForeign(['classe_id']);

            // 2. Supprimer la colonne elle-même
            $table->dropColumn('classe_id');
        });
    }

    public function down(): void
    {
        Schema::table('seances', function (Blueprint $table) {
            // Restaurer la colonne si besoin (exemple)
            $table->unsignedBigInteger('classe_id')->nullable();

            // Restaurer la contrainte
            $table->foreign('classe_id')->references('id')->on('classes')->nullOnDelete();
        });
    }
};

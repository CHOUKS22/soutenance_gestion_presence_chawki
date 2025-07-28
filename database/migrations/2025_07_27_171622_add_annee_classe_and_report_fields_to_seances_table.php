<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('seances', function (Blueprint $table) {
            $table->unsignedBigInteger('annee_classe_id')->nullable()->after('classe_id');
            $table->date('date_reportee')->nullable()->after('date_fin');
            $table->time('heure_debut_report')->nullable()->after('date_reportee');
            $table->time('heure_fin_report')->nullable()->after('heure_debut_report');
            $table->text('commentaire_report')->nullable()->after('heure_fin_report');

            $table->foreign('annee_classe_id')->references('id')->on('annee_classe')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('seances', function (Blueprint $table) {
            $table->dropForeign(['annee_classe_id']);
            $table->dropColumn([
                'annee_classe_id',
                'date_reportee',
                'heure_debut_report',
                'heure_fin_report',
                'commentaire_report'
            ]);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('seances', function (Blueprint $table) {
            if (!Schema::hasColumn('seances', 'annee_classe_id')) {
                $table->foreignId('annee_classe_id')->nullable()->after('matiere_id');
                $table->foreign('annee_classe_id')->references('id')->on('annee_classe')->nullOnDelete();
            }

            if (!Schema::hasColumn('seances', 'date_reportee')) {
                $table->date('date_reportee')->nullable()->after('date_fin');
            }

            if (!Schema::hasColumn('seances', 'heure_debut_report')) {
                $table->time('heure_debut_report')->nullable()->after('date_reportee');
            }

            if (!Schema::hasColumn('seances', 'heure_fin_report')) {
                $table->time('heure_fin_report')->nullable()->after('heure_debut_report');
            }

            if (!Schema::hasColumn('seances', 'commentaire_report')) {
                $table->text('commentaire_report')->nullable()->after('heure_fin_report');
            }
        });
    }

    public function down(): void
    {
        Schema::table('seances', function (Blueprint $table) {
            if (Schema::hasColumn('seances', 'annee_classe_id')) {
                $table->dropForeign(['annee_classe_id']);
                $table->dropColumn('annee_classe_id');
            }

            $colonnes = [
                'date_reportee',
                'heure_debut_report',
                'heure_fin_report',
                'commentaire_report'
            ];

            foreach ($colonnes as $col) {
                if (Schema::hasColumn('seances', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

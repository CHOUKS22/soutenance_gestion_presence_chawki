<?php

namespace App\Http\Controllers\Coordinateur;

use App\Http\Controllers\Controller;
use App\Models\AnneeClasse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClasseCoordinateur extends Controller
{
    public function classe(Request $request)
    {
        // ID du coordinateur connecte
        $coordinateurId = Auth::user()->coordinateur->id;

        // Classes liees au coordinateur, groupees par annee
        $classes = AnneeClasse::with(['classe', 'anneeAcademique'])
            ->where('coordinateur_id', $coordinateurId)
            ->get()
            ->groupBy('annee_academique_id');

        // Filtres choisis (annee et classe)
        $anneeId = $request->input('annee_id');
        $classeId = $request->input('classe_id');

        // Classe selectionnee (si filtres valides)
        $selectedClasse = null;

        if ($anneeId && $classeId) {
            // Recuperer la classe correspondante
            $selectedClasse = AnneeClasse::with(['classe', 'anneeAcademique', 'etudiants.user'])
                ->where('coordinateur_id', $coordinateurId)
                ->where('annee_academique_id', $anneeId)
                ->where('classe_id', $classeId)
                ->first();
        }

        // Envoyer les donnees a la vue
        return view('coordinateur.classe', [
            'classes' => $classes,
            'annees' => $classes,
            'anneeId' => $anneeId,
            'classeId' => $classeId,
            'selectedClasse' => $selectedClasse,
        ]);
    }
}

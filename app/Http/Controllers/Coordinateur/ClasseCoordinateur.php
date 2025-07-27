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
        // On récupère l'ID du coordinateur connecté
        $coordinateurId = Auth::user()->coordinateur->id;

        // On récupère toutes les classes associées à ce coordinateur, avec la classe et l'année
        $classes = AnneeClasse::with(['classe', 'anneeAcademique'])
            ->where('coordinateur_id', $coordinateurId)
            ->get()
            ->groupBy('annee_academique_id'); // On groupe par année pour l'affichage des filtres

        // Récupération des filtres sélectionnés dans la requête
        $anneeId = $request->input('annee_id');
        $classeId = $request->input('classe_id');

        // Variable pour contenir la classe sélectionnée
        $selectedClasse = null;

        // Si une année et une classe ont été sélectionnées
        if ($anneeId && $classeId) {
            // On essaie de retrouver la classe associée dans les classes du coordinateur
            $selectedClasse = AnneeClasse::with(['classe', 'anneeAcademique', 'etudiants.user'])
                ->where('coordinateur_id', $coordinateurId)
                ->where('annee_academique_id', $anneeId)
                ->where('classe_id', $classeId)
                ->first();
        }

        // On envoie les données à la vue
        return view('coordinateur.classe', [
            'classes' => $classes,
            'annees' => $classes,
            'anneeId' => $anneeId,
            'classeId' => $classeId,
            'selectedClasse' => $selectedClasse,
        ]);
    }
}

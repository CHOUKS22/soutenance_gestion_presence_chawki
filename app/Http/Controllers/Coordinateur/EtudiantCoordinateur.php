<?php

namespace App\Http\Controllers\Coordinateur;

use App\Http\Controllers\Controller;
use App\Models\AnneeClasse;
use App\Models\AnneeClasseEtudiant;
use App\Models\Etudiant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EtudiantCoordinateur extends Controller
{
   public function etudiant()
{
    $coordinateurId = Auth::user()->coordinateur->id;

        $anneesClasses = AnneeClasse::with([
            'etudiants',
            'classe',
            'anneeAcademique'
        ])
        ->where('coordinateur_id', $coordinateurId)
        ->get();

        return view('coordinateur.etudiants', compact('anneesClasses'));
    }
}


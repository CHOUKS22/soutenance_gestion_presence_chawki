<?php

namespace App\Http\Controllers\Coordinateur;

use App\Http\Controllers\Controller;
use App\Models\AnneeClasse;
use App\Models\AnneeClasseEtudiant;
use App\Models\Etudiant;
use Illuminate\Http\Request;

class GestionEtudiantClasseController extends Controller
{
    public function index()
    {
        // Liste des inscriptions avec les infos liees
        $inscriptions = AnneeClasseEtudiant::with(['anneeClasse.classe', 'anneeClasse.anneeAcademique', 'etudiant.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('coordinateur.etudiants-classes.index', compact('inscriptions'));
    }

    public function create()
    {
        // Donnees pour le formulaire d'ajout
        $anneeClasses = AnneeClasse::with(['classe', 'anneeAcademique'])->get();
        $etudiants = Etudiant::with('user')->get();

        return view('coordinateur.etudiants-classes.create', compact('anneeClasses', 'etudiants'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'annee_classe_id' => 'required|exists:annee_classes,id',
            'etudiant_id' => 'required|exists:etudiants,id',
        ]);

        // On recupere l'annee pour verifier les doublons
        $anneeClasse = AnneeClasse::with('anneeAcademique')->findOrFail($request->annee_classe_id);

        // Verifie si l'etudiant est deja inscrit dans une autre classe la meme annee
        $existe = AnneeClasseEtudiant::whereHas('anneeClasse', function ($query) use ($anneeClasse) {
            $query->where('annee_academique_id', $anneeClasse->annee_academique_id);
        })->where('etudiant_id', $request->etudiant_id)->exists();

        if ($existe) {
            return back()->withErrors(['etudiant_id' => 'Cet etudiant est deja inscrit dans une autre classe pour cette annee.'])->withInput();
        }

        // Creation de l'inscription
        AnneeClasseEtudiant::create([
            'annee_classe_id' => $request->annee_classe_id,
            'etudiant_id' => $request->etudiant_id,
        ]);

        return redirect()->route('etudiants-classes.index')->with('success', 'Inscription enregistree avec succes.');
    }

    public function show($id)
    {
        // Affichage d'une inscription
        $inscription = AnneeClasseEtudiant::with(['anneeClasse.classe', 'anneeClasse.anneeAcademique', 'etudiant.user'])
            ->findOrFail($id);

        return view('coordinateur.etudiants-classes.show', compact('inscription'));
    }

    public function edit($id)
    {
        // Edition d'une inscription
        $inscription = AnneeClasseEtudiant::findOrFail($id);
        $anneeClasses = AnneeClasse::with(['classe', 'anneeAcademique'])->get();
        $etudiants = Etudiant::with('user')->get();

        return view('coordinateur.etudiants-classes.edit', compact('inscription', 'anneeClasses', 'etudiants'));
    }

    public function update(Request $request, $id)
    {
        $inscription = AnneeClasseEtudiant::findOrFail($id);

        $request->validate([
            'annee_classe_id' => 'required|exists:annees_classes,id',
            'etudiant_id' => 'required|exists:etudiants,id',
        ]);

        // Verifie si la nouvelle inscription existe deja (hors celle en cours)
        $existingInscription = AnneeClasseEtudiant::where('annee_classe_id', $request->annee_classe_id)
            ->where('etudiant_id', $request->etudiant_id)
            ->where('id', '!=', $id)
            ->first();

        if ($existingInscription) {
            return back()->withErrors(['error' => 'Cet etudiant est deja inscrit dans cette classe.']);
        }

        // Mise a jour
        $inscription->update([
            'annee_classe_id' => $request->annee_classe_id,
            'etudiant_id' => $request->etudiant_id,
        ]);

        return redirect()->route('etudiants-classes.index')
            ->with('success', 'Inscription modifiee avec succes.');
    }

    public function destroy($id)
    {
        // Suppression d'une inscription
        $inscription = AnneeClasseEtudiant::findOrFail($id);
        $inscription->delete();

        return redirect()->route('etudiants-classes.index')
            ->with('success', 'Inscription supprimee avec succes.');
    }

    // Formulaire pour ajouter plusieurs etudiants
    public function inscrirePlusieurs()
    {
        $anneeClasses = AnneeClasse::with(['classe', 'anneeAcademique'])->get();
        $etudiants = Etudiant::with('user')->get();

        return view('coordinateur.etudiants-classes.inscrire-plusieurs', compact('anneeClasses', 'etudiants'));
    }

    // Enregistrement de plusieurs inscriptions
    public function enregistrerPlusieurs(Request $request)
    {
        $request->validate([
            'annee_classe_id' => 'required|exists:annee_classes,id',
            'etudiant_ids' => 'required|array',
            'etudiant_ids.*' => 'exists:etudiants,id',
        ]);

        $successCount = 0;
        $errorCount = 0;

        // Pour chaque etudiant, on verifie et on inscrit s'il n'est pas deja present
        foreach ($request->etudiant_ids as $etudiantId) {
            $existingInscription = AnneeClasseEtudiant::where('annee_classe_id', $request->annee_classe_id)
                ->where('etudiant_id', $etudiantId)
                ->first();

            if (!$existingInscription) {
                AnneeClasseEtudiant::create([
                    'annee_classe_id' => $request->annee_classe_id,
                    'etudiant_id' => $etudiantId,
                ]);
                $successCount++;
            } else {
                $errorCount++;
            }
        }

        // Message recapitulatif
        $message = "Inscriptions traitees: {$successCount} creees";
        if ($errorCount > 0) {
            $message .= ", {$errorCount} deja existantes";
        }

        return redirect()->route('etudiants-classes.index')
            ->with('success', $message);
    }
}

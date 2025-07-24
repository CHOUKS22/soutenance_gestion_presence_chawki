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
        $inscriptions = AnneeClasseEtudiant::with(['anneeClasse.classe', 'anneeClasse.anneeAcademique', 'etudiant.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('coordinateur.etudiants-classes.index', compact('inscriptions'));
    }

    public function create()
    {
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

        // Vérifier si l'inscription existe déjà
        $existingInscription = AnneeClasseEtudiant::where('annee_classe_id', $request->annee_classe_id)
            ->where('etudiant_id', $request->etudiant_id)
            ->first();

        if ($existingInscription) {
            return back()->withErrors(['error' => 'Cet étudiant est déjà inscrit dans cette classe pour cette année.']);
        }

        AnneeClasseEtudiant::create([
            'annee_classe_id' => $request->annee_classe_id,
            'etudiant_id' => $request->etudiant_id,
        ]);

        return redirect()->route('gestion-etudiants-classes.index')
            ->with('success', 'Inscription créée avec succès.');
    }

    public function show($id)
    {
        $inscription = AnneeClasseEtudiant::with(['anneeClasse.classe', 'anneeClasse.anneeAcademique', 'etudiant.user'])
            ->findOrFail($id);

        return view('coordinateur.etudiants-classes.show', compact('inscription'));
    }

    public function edit($id)
    {
        $inscription = AnneeClasseEtudiant::findOrFail($id);
        $anneeClasses = AnneeClasse::with(['classe', 'anneeAcademique'])->get();
        $etudiants = Etudiant::with('user')->get();

        return view('coordinateur.etudiants-classes.edit', compact('inscription', 'anneeClasses', 'etudiants'));
    }

    public function update(Request $request, $id)
    {
        $inscription = AnneeClasseEtudiant::findOrFail($id);

        $request->validate([
            'annee_classe_id' => 'required|exists:annee_classes,id',
            'etudiant_id' => 'required|exists:etudiants,id',
        ]);

        // Vérifier si l'inscription existe déjà (sauf celle en cours de modification)
        $existingInscription = AnneeClasseEtudiant::where('annee_classe_id', $request->annee_classe_id)
            ->where('etudiant_id', $request->etudiant_id)
            ->where('id', '!=', $id)
            ->first();

        if ($existingInscription) {
            return back()->withErrors(['error' => 'Cet étudiant est déjà inscrit dans cette classe pour cette année.']);
        }

        $inscription->update([
            'annee_classe_id' => $request->annee_classe_id,
            'etudiant_id' => $request->etudiant_id,
        ]);

        return redirect()->route('gestion-etudiants-classes.index')
            ->with('success', 'Inscription modifiée avec succès.');
    }

    public function destroy($id)
    {
        $inscription = AnneeClasseEtudiant::findOrFail($id);
        $inscription->delete();

        return redirect()->route('gestion-etudiants-classes.index')
            ->with('success', 'Inscription supprimée avec succès.');
    }

    // Méthode pour inscrire plusieurs étudiants à la fois
    public function inscrirePlusieurs()
    {
        $anneeClasses = AnneeClasse::with(['classe', 'anneeAcademique'])->get();
        $etudiants = Etudiant::with('user')->get();

        return view('coordinateur.etudiants-classes.inscrire-plusieurs', compact('anneeClasses', 'etudiants'));
    }

    public function enregistrerPlusieurs(Request $request)
    {
        $request->validate([
            'annee_classe_id' => 'required|exists:annee_classes,id',
            'etudiant_ids' => 'required|array',
            'etudiant_ids.*' => 'exists:etudiants,id',
        ]);

        $successCount = 0;
        $errorCount = 0;

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

        $message = "Inscriptions traitées: {$successCount} créées";
        if ($errorCount > 0) {
            $message .= ", {$errorCount} déjà existantes";
        }

        return redirect()->route('gestion-etudiants-classes.index')
            ->with('success', $message);
    }
}

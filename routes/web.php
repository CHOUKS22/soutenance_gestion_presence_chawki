<?php

//  Controlleurs des utilisateurs
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
// use App\Http\Controllers\Etudiant\DashboardEtudiantController;
// use App\Http\Controllers\Parent\DashboardParentController;
use App\Http\Controllers\DashboardController;

//Controlleurs Coordinateur
use App\Http\Controllers\Coordinateur\SeanceController;
use App\Http\Controllers\Coordinateur\DashboardCoordinateurController;
use App\Http\Controllers\Coordinateur\ClasseCoordinateur;
use App\Http\Controllers\Coordinateur\EtudiantCoordinateur;
use App\Http\Controllers\Coordinateur\PresenceAbsenceController;
use App\Http\Controllers\Coordinateur\GestionEtudiantClasseController;

use App\Http\Controllers\Coordinateur\EmploiDuTempsController;
use App\Http\Controllers\Coordinateur\JustificationController;
use App\Http\Controllers\Coordinateur\StatistiquesPresenceController;
use App\Http\Controllers\Professeur\PresenceProfesseurController;
use App\Http\Controllers\Professeur\ProfesseurEtudiantController;



//Controlleurs Professeur
use App\Http\Controllers\Professeur\DashboardProfesseurController;
use App\Http\Controllers\Professeur\SeanceProfesseurController;


// Controlleurs Admin
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AnneeAcademiqueController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AnneAcademiqueController;
use App\Http\Controllers\Admin\ClasseController;
use App\Http\Controllers\Admin\EtudiantController;
use App\Http\Controllers\Admin\ParentController;
use App\Http\Controllers\Admin\ProfesseurController;
use App\Http\Controllers\Admin\CoordinateurController;
use App\Http\Controllers\Admin\SemestreController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\StatutPresenceController;
use App\Http\Controllers\Admin\AnneeClasseController;
use App\Http\Controllers\Admin\StatutSeanceController;
use App\Http\Controllers\Admin\TypeSeanceController;
use App\Http\Controllers\Admin\MatiereController;
use App\Http\Controllers\Etudiant\DashboardEtudiantController;
use App\Http\Controllers\Parent\DashboardParentController;
use App\Http\Controllers\Professeur\EmploiDuTempsProfesseurController;
use App\Http\Controllers\Professeur\PresenceAbsenceProfesseurController;
use App\Models\AnneeAcademique;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('auth.login');
});

// Routes proteges par authentification
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    // Dashboards par role
    Route::middleware(['Etudiant'])->group(function () {
        Route::get('/etudiant/dashboard', [DashboardEtudiantController::class, 'index'])->name('etudiant.dashboard');
    });

    Route::middleware(['Parent'])->group(function () {
        Route::get('/parent/dashboard', [DashboardParentController::class, 'index'])->name('parent.dashboard');
    });




    Route::prefix('professeur')->middleware(['Professeur'])->group(function () {
        Route::get('/professeur/dashboard', [DashboardProfesseurController::class, 'index'])->name('professeur.dashboard');
        Route::get('/professeur/seances', [SeanceProfesseurController::class, 'index'])->name('professeur.seances.index');
        Route::get('/professeur/seances/{seance}', [SeanceProfesseurController::class, 'show'])->name('professeur.seances.show');
        Route::get('/professeur/seances/{id}/presences', [SeanceProfesseurController::class, 'presences'])->name('professeur.seances.presences');
        Route::post('/professeur/presence/present', [PresenceAbsenceProfesseurController::class, 'marquerPresent'])->name('professeur.presence.present');
        Route::post('/professeur/presence/retard', [PresenceAbsenceProfesseurController::class, 'marquerRetard'])->name('professeur.presence.retard');
        Route::post('/professeur/presence/absent', [PresenceAbsenceProfesseurController::class, 'marquerAbsent'])->name('professeur.presence.absent');
        Route::get('/emploi-du-temps', [EmploiDuTempsProfesseurController::class, 'index'])->name('professeur.emploi_du_temps');
        Route::get('/absences-non-justifiees', [DashboardProfesseurController::class, 'absencesNonJustifiees'])->name('absences.non_justifiees');
    });





    Route::middleware(['Coordinateur'])->group(function () {
        Route::get('/emploi-du-temps', [EmploiDuTempsController::class, 'index'])->name('emploi.index');
        Route::get('/coordinateur/dashboard', [DashboardCoordinateurController::class, 'index'])->name('coordinateur.dashboard');
        Route::resource('etudiants-classes', GestionEtudiantClasseController::class);
        Route::get('/etudiants-classes/inscrire-plusieurs', [GestionEtudiantClasseController::class, 'inscrirePlusieurs'])->name('etudiants-classes.inscrire-plusieurs');
        Route::post('/etudiants-classes/enregistrer-plusieurs', [GestionEtudiantClasseController::class, 'enregistrerPlusieurs'])->name('etudiants-classes.enregistrer-plusieurs');
        Route::post('/presence/present', [PresenceAbsenceController::class, 'marquerPresent'])->name('presence.present');
        Route::post('/presence/retard', [PresenceAbsenceController::class, 'marquerRetard'])->name('presence.retard');
        Route::post('/presence/absent', [PresenceAbsenceController::class, 'marquerAbsent'])->name('presence.absent');
        // Route::post('/presence/plusieurs-presents', [PresenceAbsenceController::class, 'marquerPlusieursPresents'])->name('presence.plusieurs.presents');
        // Route::post('/presence/plusieurs-absents', [PresenceAbsenceController::class, 'marquerPlusieursAbsents'])->name('presence.plusieurs.absents');
        Route::get('/presence/statistiques', [PresenceAbsenceController::class, 'statistiques'])->name('presence.statistiques');
        Route::resource('/seances', SeanceController::class);
        Route::get('/seances/{id}/presences', [SeanceController::class, 'presences'])->name('seances.presences');
        Route::get('/coordinateurclasse', [ClasseCoordinateur::class, 'classe'])->name('classes.classe');
        Route::get('/etudiants-coord', [EtudiantCoordinateur::class, 'etudiant'])->name('coordinateur.etudiants');
        Route::get('/justifications', [JustificationController::class, 'index'])->name('justifications.index');
        Route::get('/justifications/{absence}/create', [JustificationController::class, 'create'])->name('justifications.create');
        Route::post('/justifications/{absence}/store', [JustificationController::class, 'store'])->name('justifications.store');
        Route::get('/statistiques/assiduite', [StatistiquesPresenceController::class, 'assiduiteEtudiantParMatiere'])->name('statistiques.assiduite');
        Route::get('/statistiques/taux-presence-etudiant', [StatistiquesPresenceController::class, 'tauxPresenceEtudiant'])->name('statistiques.taux-presence-etudiant');
        Route::get('/coordinateur/classes/selection', [StatistiquesPresenceController::class, 'selectionClasse'])->name('presence.selection');
        Route::get('/coordinateur/classes/{classe}/taux-presence', [StatistiquesPresenceController::class, 'graphiquePresence'])->name('presence.graphique');
        Route::get('/classes/taux-presence', [StatistiquesPresenceController::class, 'tauxPresenceParClasse'])->name('coordinateur.presences.parClasse');
        Route::get('/coordinateur/classes/volume-cours', [StatistiquesPresenceController::class, 'volumeCoursParSemestre'])->name('coordinateur.presences.volumeCours');
        Route::get('/coordinateur/classes/volume-cours-cumule', [StatistiquesPresenceController::class, 'volumeCoursCumule'])
            ->name('coordinateur.presences.volumeCoursCumule');
        Route::get('/coordinateur/presences/classe-global', [StatistiquesPresenceController::class, 'tauxPresenceGlobalParClasse'])
            ->name('coordinateur.presences.globalParClasse');
        Route::get('/coordinateur/statistiques', function () {
            return view('coordinateur.presences.statistiques');
        })->name('coordinateur.presences.statistiques');
    });




    Route::middleware(['Administrateur'])->group(function () {
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');
        Route::resource('admins', AdminDashboardController::class);
        Route::resource('users', UserController::class);
        Route::resource('etudiants', EtudiantController::class);
        Route::resource('parents', ParentController::class);
        Route::resource('matieres', MatiereController::class);
        Route::resource('professeurs', ProfesseurController::class);
        Route::resource('coordinateurs', CoordinateurController::class);
        Route::resource('admins', AdminController::class);
        Route::resource('classes', ClasseController::class)->parameters(['classes' => 'classe']);
        Route::resource('annees-academiques', AnneAcademiqueController::class)->parameters(['annees-academiques' => 'anneeAcademique']);
        Route::resource('semestres', SemestreController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('statuts-seances', StatutSeanceController::class);
        Route::resource('statuts-presences', StatutPresenceController::class)->parameters(['statuts-presences' => 'statuts_presence']);
        Route::resource('annees-classes', AnneeClasseController::class)->parameters(['annees-classes' => 'anneeClasse']);
        Route::resource('types-seances', TypeSeanceController::class)->parameters(['types-seances' => 'type_seance']);
        // Routes pour l'assignation d'étudiants aux parents
        Route::post('/parents/assign-etudiant', [ParentController::class, 'assignEtudiant'])->name('parents.assign-etudiant');
        Route::post('/parents/unassign-etudiant', [ParentController::class, 'unassignEtudiant'])->name('parents.unassign-etudiant');
    });







    // Routes de profil (accessible à tous les utilisateurs connectés)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__ . '/auth.php';

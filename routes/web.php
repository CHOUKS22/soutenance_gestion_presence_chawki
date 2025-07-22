<?php

//  Controlleurs des utilisateurs
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\ProfesseurController;
use App\Http\Controllers\CoordinateurController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\DashboardController;

//Controlleurs Coordinateur
use App\Http\Controllers\Coordinateur\SeanceController;
use App\Http\Controllers\Coordinateur\DashboardCoordinateurController;
use App\Http\Controllers\Coordinateur\PresenceAbsenceController;

//Controlleurs Professeur
use App\Http\Controllers\Professeur\DashboardProfesseurController;
// Controlleurs Admin
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ClasseController;
use App\Http\Controllers\Admin\EtudiantController as AdminEtudiantController;
use App\Http\Controllers\Admin\ParentController as AdminParentController;
use App\Http\Controllers\Admin\ProfesseurController as AdminProfesseurController;
use App\Http\Controllers\Admin\CoordinateurController as AdminCoordinateurController;
use App\Http\Controllers\Admin\ClasseController as AdminClasseController;
use App\Http\Controllers\Admin\AnneAcademiqueController as AdminAnneeAcademiqueController;
use App\Http\Controllers\Admin\SemestreController as AdminSemestreController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\StatutSeance as AdminStatutSeanceController;
use App\Http\Controllers\Admin\StatutPresenceController as AdminStatutPresenceController;
use App\Http\Controllers\Admin\AnneeClasseController as AdminAnneeClasseController;
use App\Http\Controllers\Coordinateur\EtudiantClasseController;
use App\Http\Controllers\Coordinateur\MatiereController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// Routes proteges par authentification
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Dashboards par role
    Route::middleware(['Etudiant'])->group(function () {
        // Route::get('/etudiant/dashboard', [EtudiantController::class, 'dashboard'])->name('etudiant.dashboard');
    });

    Route::middleware(['Professeur'])->group(function () {
        Route::get('/professeur/dashboard', [DashboardProfesseurController::class, 'index'])->name('professeur.dashboard');
        // Routes temporaires pour les liens du dashboard (à remplacer par les vrais contrôleurs plus tard)
        // Route::get('/professeur/seances', function() { return redirect()->route('professeur.dashboard'); })->name('professeur.seances.index');
        // Route::get('/professeur/seances/create', function() { return redirect()->route('professeur.dashboard'); })->name('professeur.seances.create');
        // Route::get('/professeur/seances/{id}', function($id) { return redirect()->route('professeur.dashboard'); })->name('professeur.seances.show');
        // Route::get('/professeur/matieres', function() { return redirect()->route('professeur.dashboard'); })->name('professeur.matieres.index');
        // Route::get('/professeur/matieres/{id}', function($id) { return redirect()->route('professeur.dashboard'); })->name('professeur.matieres.show');
    });

    Route::middleware(['Coordinateur'])->group(function () {
        Route::get('/coordinateur/dashboard', [DashboardCoordinateurController::class, 'index'])->name('coordinateur.dashboard');
        Route::get('/gestion-seances', [SeanceController::class, 'index'])->name('gestion-seances.index');
        Route::get('/gestion-seances/create', [SeanceController::class, 'create'])->name('gestion-seances.create');
        Route::post('/gestion-seances', [SeanceController::class, 'store'])->name('gestion-seances.store');
        Route::get('/gestion-seances/{seance}', [SeanceController::class, 'show'])->name('gestion-seances.show');
        Route::get('/gestion-seances/{seance}/edit', [SeanceController::class, 'edit'])->name('gestion-seances.edit');
        Route::patch('/gestion-seances/{seance}', [SeanceController::class, 'update'])->name('gestion-seances.update');
        Route::put('/gestion-seances/{seance}', [SeanceController::class, 'update'])->name('gestion-seances.update');
        Route::delete('/gestion-seances/{seance}', [SeanceController::class, 'destroy'])->name('gestion-seances.destroy');
        Route::resource('matieres', MatiereController::class);
        Route::resource('gestion-etudiants-classes', EtudiantClasseController::class);
        Route::get('/gestion-etudiants-classes/inscrire-plusieurs', [EtudiantClasseController::class, 'inscrirePlusieurs'])->name('gestion-etudiants-classes.inscrire-plusieurs');
        Route::post('/gestion-etudiants-classes/enregistrer-plusieurs', [EtudiantClasseController::class, 'enregistrerPlusieurs'])->name('gestion-etudiants-classes.enregistrer-plusieurs');
        Route::post('/presence/present', [PresenceAbsenceController::class, 'marquerPresent'])->name('presence.present');
        Route::post('/presence/retard', [PresenceAbsenceController::class, 'marquerRetard'])->name('presence.retard');
        Route::post('/presence/absent', [PresenceAbsenceController::class, 'marquerAbsent'])->name('presence.absent');
        Route::post('/presence/plusieurs-presents', [PresenceAbsenceController::class, 'marquerPlusieursPresents'])->name('presence.plusieurs.presents');
        Route::post('/presence/plusieurs-absents', [PresenceAbsenceController::class, 'marquerPlusieursAbsents'])->name('presence.plusieurs.absents');
        Route::get('/presence/statistiques', [PresenceAbsenceController::class, 'statistiques'])->name('presence.statistiques');
        Route::get('/seances/prochaines', [SeanceController::class, 'prochaines'])->name('seances.prochaines');
        Route::get('/seances/historique', [SeanceController::class, 'historique'])->name('seances.historique');
        Route::get('/seances/aujourd-hui', [SeanceController::class, 'aujourdhui'])->name('seances.aujourdhui');
        Route::get('/seances/cette-semaine', [SeanceController::class, 'cetteSemaine'])->name('seances.cette-semaine');
    });

    Route::middleware(['Parent'])->group(function () {

        // Route::get('/parent/dashboard', [ParentController::class, 'dashboard'])->name('parent.dashboard');
    });

    Route::middleware(['Administrateur'])->group(function () {
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');
        Route::resource('admins', AdminDashboardController::class);
        Route::resource('users', UserController::class);
        Route::resource('gestion-etudiants', AdminEtudiantController::class);
        Route::resource('gestion-parents', AdminParentController::class);
        Route::resource('gestion-professeurs', AdminProfesseurController::class);
        Route::resource('gestion-coordinateurs', AdminCoordinateurController::class);
        Route::resource('gestion-admins', AdminController::class);
        Route::resource('gestion-classes', AdminClasseController::class);
        Route::resource('gestion-annees-academiques', AdminAnneeAcademiqueController::class);
        Route::resource('gestion-semestres', AdminSemestreController::class);
        Route::resource('gestion-roles', AdminRoleController::class);
        Route::resource('gestion-statuts-seances', AdminStatutSeanceController::class);
        Route::resource('gestion-statuts-presences', AdminStatutPresenceController::class);
        Route::resource('gestion-annees-classes', AdminAnneeClasseController::class);
        // Routes pour l'assignation d'étudiants aux parents
        Route::post('/gestion-parents/assign-etudiant', [AdminParentController::class, 'assignEtudiant'])->name('gestion-parents.assign-etudiant');
        Route::post('/gestion-parents/unassign-etudiant', [AdminParentController::class, 'unassignEtudiant'])->name('gestion-parents.unassign-etudiant');

    });

    // Routes de profil (accessible à tous les utilisateurs connectés)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__ . '/auth.php';

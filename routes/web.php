<?php

//  Controlleurs des utilisateurs
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Etudiant\DashboardController as EtudiantDashboardController;
use App\Http\Controllers\Parent\DashboardController as ParentDashboardController;
use App\Http\Controllers\DashboardController;

//Controlleurs Coordinateur
use App\Http\Controllers\Coordinateur\SeanceController;
use App\Http\Controllers\Coordinateur\DashboardCoordinateurController;
// use App\Http\Controllers\Coordinateur\GestionMatiereController;
use App\Http\Controllers\Coordinateur\PresenceAbsenceController;
use App\Http\Controllers\Coordinateur\GestionEtudiantClasseController;
//Controlleurs Professeur
use App\Http\Controllers\Professeur\DashboardProfesseurController;
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
use App\Http\Controllers\Admin\StatutSeance;
use App\Http\Controllers\Admin\StatutPresenceController;
use App\Http\Controllers\Admin\AnneeClasseController;
use App\Http\Controllers\Admin\StatutSeanceController;
use App\Http\Controllers\Admin\TypeSeanceController;
use App\Http\Controllers\Coordinateur\EtudiantClasseController;
use App\Http\Controllers\Admin\MatiereController;
use App\Models\AnneeAcademique;
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
        Route::get('/etudiant/dashboard', [EtudiantDashboardController::class, 'dashboard'])->name('etudiant.dashboard');
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
        Route::get('/seances', [SeanceController::class, 'index'])->name('seances.index');
        Route::get('/seances/create', [SeanceController::class, 'create'])->name('seances.create');
        Route::post('/seances', [SeanceController::class, 'store'])->name('seances.store');
        Route::get('/seances/{seance}', [SeanceController::class, 'show'])->name('seances.show');
        Route::get('/seances/{seance}/edit', [SeanceController::class, 'edit'])->name('seances.edit');
        Route::patch('/seances/{seance}', [SeanceController::class, 'update'])->name('seances.update');
        Route::put('/seances/{seance}', [SeanceController::class, 'update'])->name('seances.update');
        Route::delete('/seances/{seance}', [SeanceController::class, 'destroy'])->name('seances.destroy');
        Route::resource('etudiants-classes', GestionEtudiantClasseController::class);
        Route::get('/etudiants-classes/inscrire-plusieurs', [GestionEtudiantClasseController::class, 'inscrirePlusieurs'])->name('etudiants-classes.inscrire-plusieurs');
        Route::post('/etudiants-classes/enregistrer-plusieurs', [GestionEtudiantClasseController::class, 'enregistrerPlusieurs'])->name('etudiants-classes.enregistrer-plusieurs');
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
        Route::get('/parent/dashboard', [ParentDashboardController::class, 'dashboard'])->name('parent.dashboard');
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
        Route::resource('classes', ClasseController::class)->parameters(['classes' => 'classe']);;
        Route::resource('annees-academiques', AnneAcademiqueController::class)->parameters(['annees-academiques' => 'anneeAcademique']);
        Route::resource('semestres', SemestreController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('statuts-seances', StatutSeanceController::class);
        Route::resource('statuts-presences', StatutPresenceController::class)->parameters(['statuts-presences' => 'statuts_presence']);
        Route::resource('annees-classes', AnneeClasseController::class)->parameters(['annees-classes' => 'anneeClasse']);
        Route::resource('types-seances', TypeSeanceController::class);

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

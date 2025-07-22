<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user && $user->role) {
            $role = $user->role->nom;
            if ($role === 'Administrateur') {
                return redirect()->route('admin.dashboard');
            }
            if ($role === 'Coordinateur') {
                return redirect()->route('coordinateur.dashboard');
            }
             if ($role === 'Professeur') {
                return redirect()->route('professeur.dashboard');
            }
            if ($role === 'Parent') {
                return redirect()->route('parent.dashboard');
            }
            if ($role === 'Etudiant') {
                return redirect()->route('etudiant.dashboard');
            }
            Auth::logout();
            return redirect()->route('login')->with('error', 'Rôle non autorisé. Veuillez vous reconnecter.');
        }

        // return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

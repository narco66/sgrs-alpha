<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Events\UserSelfRegistered;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * Flux adapté SGRS-CEEAC :
     * - Le compte est créé en statut « en attente » (is_active = false)
     * - Aucun rôle effectif n'est attribué immédiatement
     * - Une notification est envoyée aux administrateurs pour validation
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:utilisateurs,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'              => $request->string('name'),
            'email'             => $request->string('email')->lower(),
            'password'          => Hash::make($request->string('password')),
            // Compte en attente de validation par un administrateur
            'is_active'         => false,
            'email_verified_at' => null,
        ]);

        // Conserver le flux Laravel (email de vérification si activé)
        event(new Registered($user));

        // Evénement métier spécifique pour la demande de création de compte
        event(new UserSelfRegistered($user));

        // Ne PAS connecter automatiquement l'utilisateur.
        return redirect()
            ->route('login')
            ->with('status', 'Votre demande de création de compte a été transmise. '
                .'Elle sera examinée par un administrateur avant activation.');
    }
}

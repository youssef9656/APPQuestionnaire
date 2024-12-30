<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Affiche la page de connexion.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('users.login');
    }

    /**
     * Gère la soumission du formulaire de connexion.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validation des données
        $validatedData = $request->validate([
            'matricule' => 'required|string',
            'password' => 'required|string',
        ]);

        // Recherche de l'utilisateur par matricule
        $user = User::where('matricule', $request->matricule)->first(); // Utilisation de where()

        // Vérification de l'existence de l'utilisateur et du mot de passe
        if ($user && Hash::check($request->password, $user->password)) {
            // Authentifier l'utilisateur
            Auth::login($user);

            // Rediriger vers la page d'accueil ou dashboard
            return redirect()->route('home')->with('success', 'Connexion réussie');
        } else {
            // Retourner une erreur si les informations sont incorrectes
            return back()->withErrors(['matricule' => 'Matricule ou mot de passe incorrect.']);
        }
    }

    /**
     * Déconnecte l'utilisateur.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    /**
     * Affiche le formulaire pour créer un utilisateur.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Traitement du formulaire pour créer un utilisateur.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'matricule' => 'required|string|max:50|unique:users,matricule',
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'telephone' => 'required|string|max:15|regex:/^[0-9\-\+]{10,15}$/',
            'password' => 'required|string|confirmed|min:8',
            'poste_occupe' => 'nullable|string|max:100',
            'annees_exp_habillement' => 'nullable|integer',
            'annees_exp_formateur' => 'nullable|integer',
            'formations_certifications' => 'nullable|string|max:255',
        ], [
            'matricule.required' => 'Le matricule est obligatoire.',
            'matricule.unique' => 'Ce matricule est déjà utilisé.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'Le mot de passe de confirmation ne correspond pas.',
            'telephone.regex' => 'Le numéro de téléphone est invalide.',
        ]);

        // Création de l'utilisateur avec mot de passe haché
        User::create([
            'matricule' => $validated['matricule'],
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'],
            'poste_occupe' => $validated['poste_occupe'],
            'annees_exp_habillement' => $validated['annees_exp_habillement'],
            'annees_exp_formateur' => $validated['annees_exp_formateur'],
            'formations_certifications' => $validated['formations_certifications'],
            'password' => bcrypt($validated['password']), // Hachage du mot de passe confirmé
        ]);

        // Redirection avec message de succès
        return redirect()->route('users.create')->with('success', 'Utilisateur créé avec succès.');
    }
}

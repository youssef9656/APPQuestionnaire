@extends('layouts.app')

@section('content')
    <div class="row justify-content-center align-items-center vh-100">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="fw-bold mb-0">Connexion</h3>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Matricule -->
                        <div class="mb-3">
                            <label for="matricule" class="form-label fw-semibold">Matricule</label>
                            <input
                                type="text"
                                name="matricule"
                                id="matricule"
                                class="form-control"
                                placeholder="Entrez votre matricule"
                                required>
                            @error('matricule')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Mot de passe -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Mot de passe</label>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control"
                                placeholder="Entrez votre mot de passe"
                                required>
                            @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Bouton Connexion -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Se connecter</button>
                        </div>
                    </form>

                    <!-- Lien Créer un compte -->
                    <div class="text-center mt-3">
                        <small>Pas encore de compte ?</small>
                        <a href="{{ route('register') }}" class="text-decoration-none">Créer un compte</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

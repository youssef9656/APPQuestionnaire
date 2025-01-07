@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4 text-center">Ajouter un test</h1>
        <form action="{{ route('tests.store') }}" method="POST" class="shadow p-4 rounded bg-light">
            @csrf

            <!-- Nom du test -->
            <div class="mb-3">
                <label for="nom_test" class="form-label">Nom du test :</label>
                <input type="text" name="nom_test" id="nom_test" class="form-control" placeholder="Entrez le nom du test" required>
            </div>

            <!-- Durée -->
            <div class="mb-3">
                <label for="duree_test" class="form-label">Durée (minutes) :</label>
                <input type="number" name="duree_test" id="duree_test" class="form-control" placeholder="Ex : 30" required>
            </div>

            <!-- Actif -->
            <div class="form-check mb-3">
                <input type="checkbox" name="active" id="active" class="form-check-input" value="1" checked>
                <label for="active" class="form-check-label">Actif</label>
            </div>

            <!-- Boutons -->
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-success">Ajouter</button>
                <a href="{{ route('tests.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>

    <style>
        h1 {
            font-weight: bold;
            color: #343a40;
        }

        .form-label {
            font-weight: bold;
        }

        .form-control {
            border-radius: 0.25rem;
        }

        .form-check-label {
            font-weight: 500;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .shadow {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .container {
            max-width: 600px;
        }
    </style>
@endsection

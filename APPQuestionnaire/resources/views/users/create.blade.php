<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Utilisateur</title>
    <!-- Intégration de Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Intégration de FontAwesome pour les icônes -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            padding: 50px;
        }
        .form-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: auto;
        }
        .form-container h2 {
            color: #343a40;
            margin-bottom: 20px;
            font-weight: 700;
        }
        label {
            font-weight: bold;
            color: #495057;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: red;
            font-size: 0.875rem;
        }
        .success-message {
            background-color: #28a745;
            color: white;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container">
        <h2 class="text-center">Créer un Nouvel Utilisateur</h2>

        <!-- Formulaire -->
        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="matricule">Matricule:</label>
                    <input type="text" name="matricule" id="matricule" class="form-control" value="{{ old('matricule') }}" required>
                    @error('matricule')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="nom">Nom:</label>
                    <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom') }}" required>
                    @error('nom')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="prenom">Prénom:</label>
                    <input type="text" name="prenom" id="prenom" class="form-control" value="{{ old('prenom') }}" required>
                    @error('prenom')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                    @error('email')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="telephone">Téléphone:</label>
                    <input type="text" name="telephone" id="telephone" class="form-control" value="{{ old('telephone') }}" required>
                    @error('telephone')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="poste_occupe">Poste Occupé:</label>
                    <input type="text" name="poste_occupe" id="poste_occupe" class="form-control" value="{{ old('poste_occupe') }}" required>
                    @error('poste_occupe')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="annees_exp_habillement">Années d'Expérience (Habillement):</label>
                    <input type="number" name="annees_exp_habillement" id="annees_exp_habillement" class="form-control" value="{{ old('annees_exp_habillement') }}" required>
                    @error('annees_exp_habillement')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="annees_exp_formateur">Années d'Expérience (Formateur):</label>
                    <input type="number" name="annees_exp_formateur" id="annees_exp_formateur" class="form-control" value="{{ old('annees_exp_formateur') }}" required>
                    @error('annees_exp_formateur')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="formations_certifications">Formations et Certifications:</label>
                <input type="text" name="formations_certifications" id="formations_certifications" class="form-control" value="{{ old('formations_certifications') }}" required>
                @error('formations_certifications')
                <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="row g-3">
                <div class="col-md-6 position-relative">
                    <label for="password">Mot de Passe:</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control" required>
                        <span class="input-group-text">
                            <i class="fa fa-eye" id="togglePassword" style="cursor: pointer;"></i>
                        </span>
                    </div>
                    @error('password')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 position-relative">
                    <label for="password_confirmation">Confirmer le Mot de Passe:</label>
                    <div class="input-group">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                        <span class="input-group-text">
                            <i class="fa fa-eye" id="toggleConfirmPassword" style="cursor: pointer;"></i>
                        </span>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-4">Créer Utilisateur</button>
        </form>

        <!-- Message de succès -->
        @if (session('success'))
            <div class="success-message mt-4">{{ session('success') }}</div>
        @endif
    </div>
</div>

<!-- Intégration de Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- Script pour afficher/masquer le mot de passe -->
<script>
    // Afficher/Masquer le mot de passe
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordField = document.getElementById('password');
        const type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;
        this.classList.toggle('fa-eye-slash'); // Change l'icône
    });

    document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
        const confirmPasswordField = document.getElementById('password_confirmation');
        const type = confirmPasswordField.type === 'password' ? 'text' : 'password';
        confirmPasswordField.type = type;
        this.classList.toggle('fa-eye-slash'); // Change l'icône
    });
</script>
</body>
</html>

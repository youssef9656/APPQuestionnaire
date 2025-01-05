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
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #4e73df, #1cc88a);
            min-height: 100vh;
            display: flex; /* Utilisation de flexbox */
            justify-content: center; /* Centrer horizontalement */
            align-items: center; /* Centrer verticalement */
            margin: 0;
            padding: 0;
        }

        .form-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0px 12px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 900px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .form-container:hover {
            transform: translateY(-5px);
            box-shadow: 0px 15px 40px rgba(0, 0, 0, 0.3);
        }

        label {
            font-weight: 500;
            color: #555;
        }

        input,
        select {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input:focus,
        select:focus {
            border-color: #4e73df;
            outline: none;
            box-shadow: 0px 0px 10px rgba(78, 115, 223, 0.5);
        }

        .btn-primary {
            background: linear-gradient(135deg, #4e73df, #1cc88a);
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            transition: background 0.3s ease, transform 0.2s ease;
            font-weight: bold;
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #375a7f, #17a673);
            transform: scale(1.05);
        }

        .error-message {
            color: #e74c3c;
            font-size: 0.85rem;
        }

        .success-message {
            background: linear-gradient(135deg, #1cc88a, #28a745);
            color: white;
            padding: 12px;
            border-radius: 8px;
            margin-top: 15px;
            text-align: center;
            animation: fadeIn 0.5s ease;
        }

        .form-footer {
            text-align: center;
            margin-top: 20px;
        }

        .form-footer a {
            color: #4e73df;
            text-decoration: none;
            font-weight: 500;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        /* Animation pour les messages de succès */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 576px) {
            .form-container {
                padding: 20px;
            }
            input,
            select {
                font-size: 0.9rem;
            }
            .btn-primary {
                font-size: 0.9rem;
            }
        }


        /* Autres styles restent inchangés */

    </style>
</head>
<body>

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
<div  class="text-center">
    <button type="submit" class="btn btn-primary mt-4">Créer Utilisateur</button>
    <a href="{{ route('login') }}" class="text-decoration-none btn btn-primary mt-4">Connexion</a>

</div>


        </form>

        <!-- Message de succès -->
        @if (session('success'))
            <div class="success-message mt-4">{{ session('success') }}</div>
        @endif
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

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .logo-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body>

<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm w-100">
        <div class="container">
            <!-- Logo circulaire ou nom de l'application -->
            <a class="navbar-brand d-flex align-items-center" href="{{ url('http://127.0.0.1:8000/reponse/6') }}">
                <img src="{{ asset('Picture1.png') }}" alt="Logo" class="logo-circle me-2">
                Mon Application
            </a>
            <!-- Bouton pour petits écrans -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Liens de navigation -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php
                    session_start();
                    if (isset($_SESSION['userA'])) {
                        echo '<li class="nav-item">
            <span class="nav-link">
                Bienvenue, ' . $_SESSION['userA']['nom'] . ' ' . $_SESSION['userA']['prenom'] . '
            </span>
          </li>
          <li class="nav-item">
            <form id="logout-form" action="' . route('logout') . '" method="POST" class="d-inline">
                <input type="hidden" name="_token" value="' . csrf_token() . '">
                <button type="submit" class="btn btn-danger">Déconnexion</button>
            </form>
          </li>';
                    } else {
                        echo '<li class="nav-item">
            <a class="nav-link" href="' . route('login') . '">Connexion</a>
          </li>';
                    }
                    ?>

                </ul>
            </div>
        </div>
    </nav>
</header>

<div class="container mt-4">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

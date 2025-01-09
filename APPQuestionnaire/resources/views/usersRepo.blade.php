<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau Tree Style</title>

    <!-- CSS DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- JS DataTables -->
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

    <!-- Styles personnalisés -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
            color: #333;
            transition: background-color 0.3s, color 0.3s;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Bouton Dark Mode */
        .dark-mode-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .dark-mode-toggle:hover {
            background-color: #0056b3;
        }

        .dark-mode-toggle i {
            font-size: 18px;
        }

        /* Style tableau */
        table.dataTable {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #ddd;
        }

        table.dataTable thead {
            background-color: #4caf50;
            color: white;
            text-align: left;
            border-bottom: 3px solid #333;
        }

        table.dataTable tbody tr {
            transition: background-color 0.3s;
        }

        table.dataTable tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        table.dataTable tbody tr:nth-child(even) {
            background-color: #fff;
        }

        table.dataTable tbody tr:hover {
            background-color: #f1f1f1;
        }

        /* Style "tree" avec indentation */
        table.dataTable tbody td {
            padding: 10px 15px;
            border-bottom: 1px solid #ddd;
        }

        table.dataTable tbody tr td:first-child {
            padding-left: 30px; /* Ajout d'espace pour simuler une hiérarchie */
        }

        table.dataTable tbody tr.child td:first-child {
            padding-left: 50px; /* Plus d'espace pour une ligne "enfant" */
            font-style: italic;
        }

        body.dark-mode {
            background-color: #1c1c1c;
            color: #ccc;
        }

        body.dark-mode table.dataTable thead {
            background-color: #444;
        }

        body.dark-mode table.dataTable tbody tr:nth-child(odd) {
            background-color: #2c2c2c;
        }

        body.dark-mode table.dataTable tbody tr:nth-child(even) {
            background-color: #1c1c1c;
        }

        body.dark-mode table.dataTable tbody tr:hover {
            background-color: #333;
        }
    </style>
</head>
<body class="light-mode">
<div class="">
    <h1>user</h1>
        <i  onclick="toggleDarkMode()" class="icon btn">&#9728;</i>
    <table id="usersTable" class="display table-responsive" style="width:100%">
        <thead>
        <tr>
            <th>Matricule</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Poste Occupé</th>
            <th>Exp. Habillement</th>
            <th>Exp. Formateur</th>
            <th>Certifications</th>
            <th>Téléphone</th>
            <th>Email</th>
            <th>Test</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->matricule }}</td>
                <td>{{ $user->nom }}</td>
                <td>{{ $user->prenom }}</td>
                <td>{{ $user->poste_occupe }}</td>
                <td>{{ $user->annees_exp_habillement }}</td>
                <td>{{ $user->annees_exp_formateur }}</td>
                <td>{{ $user->formations_certifications }}</td>
                <td>{{ $user->telephone }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @php
                        $hasTest = $tests->contains('id_user', $user->id_user);
                    @endphp
                    @if($hasTest)
                        <span class="badge bg-success">Test Répondu</span>
                    @else
                        <span class="badge bg-danger">Pas de Test</span>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function () {
        $('#usersTable').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.5/i18n/French.json" // Traduction française
            }
        });
    });

    function toggleDarkMode() {
        document.body.classList.toggle('dark-mode');
        document.body.classList.toggle('light-mode');
        const icon = document.querySelector('.dark-mode-toggle i');
        icon.innerHTML = document.body.classList.contains('dark-mode') ? '&#9790;' : '&#9728;';
    }
</script>
</body>
</html>

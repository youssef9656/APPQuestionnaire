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
         .custom-btn {
             background: linear-gradient(45deg, #28a745, #85e085); /* Dégradé vert clair à foncé */
             border: 1px solid #28a745;
             color: #fff;
             border-radius: 8px;
             padding: 10px 15px;
             box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
             transition: transform 0.2s ease, box-shadow 0.2s ease;
         }

        .custom-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
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
                    @foreach($teste as $test)
                        @php
                            // Vérifier si l'utilisateur a répondu à ce test
                            $hasTest = $testsre->contains(function ($item) use ($test, $user) {
                                return $item->id_test == $test['id_test'] && $item->id_user == $user->id_user;
                            });
                        @endphp
                        <div class="circle {{ $hasTest ? 'bg-success' : 'bg-danger' }}">
{{--                            <span class="test-id" data-NomTest ={{$test['nom_test']}} >{{ $test['id_test'] }}</span>--}}
                            <span class="test-id" data-nomtest="{{ $test['nom_test'] }}" data-id="{{ $test['id_test'] }}">{{ $test['id_test'] }}</span>

                        </div>
                    @endforeach
                </td>

                <style>
                    .circle {
                        width: 30px;
                        height: 30px;
                        border-radius: 50%;
                        display: inline-block;
                        text-align: center;
                        line-height: 30px;
                        margin-right: 5px;
                        color: white;
                        font-size: 12px;
                    }
                    .bg-success {
                        background-color: green;
                    }
                    .bg-danger {
                        background-color: red;
                    }
                    .test-id {
                        font-size: 10px;
                    }
                </style>



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
                url: "//cdn.datatables.net/plug-ins/1.13.5/i18n/French.json"
            }
        });

        // Gestion du clic sur une ligne
        $('#usersTable tbody').on('click', 'tr', function () {
            const $row = $(this);

            // Vérifier si une ligne de détail est déjà affichée
            if ($row.next('.details-row').length) {
                $row.next('.details-row').remove();
                return;
            }

            // Supprimer toutes les lignes de détail existantes
            $('.details-row').remove();

            // Récupérer les données de la ligne
            const matricule = $row.find('td').eq(0).text();
            const id_user = matricule.trim(); // Supposons que l'ID utilisateur est contenu dans le champ Matricule
            const testCircles = $row.find('.circle.bg-success'); // Récupérer les tests réussis

            // Générer les boutons pour chaque test réussi
            let buttonsHtml = '';
            testCircles.each(function () {
                const test_name = $(this).find('.test-id').attr('data-nomtest'); // Récupérer le nom du test
                const id_test = $(this).find('.test-id').attr('data-id'); // Récupérer l'ID du test

                if (test_name && id_test) {
                    buttonsHtml += `
                    <button
                        class="btn btn-success custom-btn"
                        style="margin: 5px; font-weight: bold;"
                        onclick="redirectToTest('${id_user}', '${id_test}')"
                    >
                        ${test_name}
                    </button>
                `;
                }
            });

            // Générer le contenu de la nouvelle ligne
            const newRowHtml = `
            <tr class="details-row" style="background-color: #f0f8ff;">
                <td colspan="10">
                    <strong>Tests réussis pour l'utilisateur ${id_user} :</strong><br>
                    ${buttonsHtml || 'Aucun test réussi trouvé.'}
                </td>
            </tr>
        `;

            // Insérer la nouvelle ligne sous la ligne actuelle
            $row.after(newRowHtml);
        });
    });

    // Fonction pour rediriger vers une autre page avec les paramètres
    function redirectToTest(id_user, id_test) {
        const url = `test_page.php?id_user=${id_user}&id_test=${id_test}`;
        window.location.href = url;
    }

    // Fonction pour le mode sombre
    function toggleDarkMode() {
        document.body.classList.toggle('dark-mode');
        document.body.classList.toggle('light-mode');
        const icon = document.querySelector('.dark-mode-toggle i');
        icon.innerHTML = document.body.classList.contains('dark-mode') ? '&#9790;' : '&#9728;';
    }

</script></body>
</html>

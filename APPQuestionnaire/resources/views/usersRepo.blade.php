<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Table</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/footable/3.1.6/footable.bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1>Users</h1>
    <table class="table" data-paging="true" data-sorting="true" data-filtering="true">
        <thead>
        <tr>
            <th data-name="matricule" data-type="text">Matricule</th>
            <th data-name="nom" data-type="text">Nom</th>
            <th data-name="prenom" data-type="text">Prénom</th>
            <th data-name="poste_occupe" data-type="text">Poste Occupé</th>
            <th data-name="annees_exp_habillement" data-type="number">Exp. Habillement</th>
            <th data-name="annees_exp_formateur" data-type="number">Exp. Formateur</th>
            <th data-name="formations_certifications" data-type="text">Certifications</th>
            <th data-name="telephone" data-type="text">Téléphone</th>
            <th data-name="email" data-type="text">Email</th>
            <th data-name="test" data-type="text">Test</th>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/footable/3.1.6/footable.min.js"></script>
<script>
    $(function () {
        $('.table').footable({
            filtering: {
                enabled: true
            }
        });
    });
</script>
</body>
</html>

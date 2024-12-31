@extends('layout')

@section('content')
    <h1>Ajouter un test</h1>
    <form action="{{ route('tests.store') }}" method="POST">
        @csrf
        <label for="nom_test">Nom du test :</label>
        <input type="text" name="nom_test" id="nom_test" required>

        <label for="duree_test">Durée (minutes) :</label>
        <input type="number" name="duree_test" id="duree_test" required>

        <label for="active">Actif :</label>
        <input type="checkbox" name="active" id="active" value="1" checked>

        <button type="submit">Ajouter</button>
    </form>
@endsection

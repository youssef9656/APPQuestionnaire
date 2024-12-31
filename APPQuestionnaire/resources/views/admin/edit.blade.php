@extends('layout')

@section('content')
    <h1>Modifier le test</h1>
    <form action="{{ route('tests.update', $test->id_test) }}" method="POST">
        @csrf
        @method('PUT')
        <label for="nom_test">Nom du test :</label>
        <input type="text" name="nom_test" id="nom_test" value="{{ $test->nom_test }}" required>

        <label for="duree_test">Durée (minutes) :</label>
        <input type="number" name="duree_test" id="duree_test" value="{{ $test->duree_test }}" required>

        <label for="active">Actif :</label>
        <input type="checkbox" name="active" id="active" value="1" {{ $test->active ? 'checked' : '' }}>

        <button type="submit">Mettre à jour</button>
    </form>
@endsection

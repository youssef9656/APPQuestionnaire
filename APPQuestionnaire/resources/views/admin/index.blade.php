@extends('layout')

@section('content')
    <h1>Liste des tests</h1>
    <a href="{{ route('tests.create') }}">Ajouter un test</a>
    <table>
        <thead>
        <tr>
            <th>Nom</th>
            <th>Durée</th>
            <th>Actif</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($tests as $test)
            <tr>
                <td>{{ $test->nom_test }}</td>
                <td>{{ $test->duree_test }}</td>
                <td>{{ $test->active ? 'Oui' : 'Non' }}</td>
                <td>
                    <a href="{{ route('tests.edit', $test->id_test) }}">Modifier</a>
                    <form action="{{ route('tests.destroy', $test->id_test) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Supprimer</button>
                    </form>
                    <a href="{{ route('questions.index', $test->id_test) }}">Gérer les questions</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@extends('layout')

@section('content')
    <h1>Questions pour le test : {{ $test->nom_test }}</h1>

    <a href="{{ route('questions.create', $test->id_test) }}">Ajouter une question</a>

    <table>
        <thead>
        <tr>
            <th>Texte</th>
            <th>Type</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($questions as $question)
            <tr>
                <td>{{ $question->text }}</td>
                <td>{{ ucfirst($question->type) }}</td>
                <td>
                    <a href="{{ route('questions.edit', [$test->id_test, $question->id_question]) }}">Modifier</a>
                    <form action="{{ route('questions.destroy', [$test->id_test, $question->id_question]) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Supprimer</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <a href="{{ route('tests.index') }}">Retour aux tests</a>
@endsection

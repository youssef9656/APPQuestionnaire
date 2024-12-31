
@extends('layout')

@section('content')
    <h1>Ajouter une question au test : {{ $test->nom_test }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('questions.store', ['test' => $test->id_test]) }}" method="POST">
        @csrf

        <div>
            <label for="text">Texte de la question :</label>
            <input type="text" name="text" id="text" value="{{ old('text') }}" required>
        </div>

        <div>
            <label for="type">Type de question :</label>
            <select name="type" id="type" required>
                <option value="text" {{ old('type') == 'text' ? 'selected' : '' }}>Texte</option>
                <option value="multiple_choice" {{ old('type') == 'multiple_choice' ? 'selected' : '' }}>Choix multiple</option>
                <option value="true_false" {{ old('type') == 'true_false' ? 'selected' : '' }}>Vrai/Faux</option>
            </select>
        </div>

        <button type="submit">Ajouter la question</button>
        <a href="{{ route('questions.index', $test->id_test) }}">Annuler</a>
    </form>
@endsection

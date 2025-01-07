@extends('layouts.app')

@section('content')
    <h1>Modifier la question</h1>

    <form action="{{ route('questions.update', [$test->id_test, $question->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label for="text">Texte de la question :</label>
            <input type="text" name="text" id="text" value="{{ $question->text }}" required>
        </div>

        <div>
            <label for="type">Type :</label>
            <select name="type" id="type">
                <option value="text" {{ $question->type == 'text' ? 'selected' : '' }}>Texte</option>
                <option value="multiple_choice" {{ $question->type == 'multiple_choice' ? 'selected' : '' }}>Choix multiple</option>
                <option value="true_false" {{ $question->type == 'true_false' ? 'selected' : '' }}>Vrai/Faux</option>
            </select>
        </div>

        <button type="submit">Mettre à jour</button>
    </form>
@endsection


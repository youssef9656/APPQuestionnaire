@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1>Questions du Test : {{ $test->name }}</h1>
        <a href="{{ route('questions.create', $test->id_test) }}" class="btn btn-primary mb-4">Ajouter une nouvelle Question</a>

        <div class="row">
            @forelse ($questions as $question)
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <strong>Question :</strong> {{ $question->text_question }}
                            <span class="badge bg-secondary float-end">{{ ucfirst($question->type_question) }}</span>
                        </div>
                        <div class="card-body">
                            {{-- Sous-questions --}}
                            @if ($question->subQuestions && $question->subQuestions->count() > 0)
                                <h5 class="card-title">Sous-questions :</h5>
                                <ul class="list-group list-group-flush">
                                    @foreach ($question->subQuestions as $subQuestion)
                                        <li class="list-group-item">
                                            {{ $subQuestion->text_question ?? '(Texte non défini)' }}
                                            <span class="badge bg-info float-end">{{ ucfirst($subQuestion->type_question) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted">Aucune sous-question pour cette question.</p>
                            @endif

                            {{-- Options --}}
                            @if ($question->options && $question->options->count() > 0)
                                <h5 class="card-title mt-3">Options :</h5>
                                <ul class="list-group list-group-flush">
                                    @foreach ($question->options as $option)
                                        <li class="list-group-item">
                                            <strong>Option :</strong> {{ $option->text_option }} <br>
                                            <strong>Ordre :</strong> {{ $option->ordre_question }} <br>
                                            @if ($option->text_associé !== null)
                                                <strong>Question associée :</strong> {{ $option->text_associé }} <br>
                                            @else
                                                <span class="text-muted">Aucune question associée.</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted">Aucune option pour cette question.</p>
                            @endif
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('questions.edit', [$test->id_test, $question->id_question]) }}" class="btn btn-warning btn-sm">Modifier</a>
                            <form action="{{ route('questions.destroy', [$test->id_test, $question->id_question]) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette question ?')">Supprimer</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center">Aucune question trouvée pour ce test.</p>
            @endforelse
        </div>
    </div>
@endsection

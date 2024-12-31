@extends('layout')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4 text-center">Liste des tests</h1>

        <!-- Bouton Ajouter un test -->
        <div class="d-flex justify-content-end mb-4">
            <a href="{{ route('reponquition.create') }}" class="btn btn-primary">Ajouter un test</a>
        </div>

        <div class="row">
            @forelse($tests as $test)
                <div class="col-md-4">
                    <!-- Card du test -->
                    <div class="card mb-4 shadow-sm test-card" >
                        <div class="card-body">
                            <h5 class="card-title" data-bs-toggle="collapse" data-bs-target="#questions-{{ $test->id_test }}" aria-expanded="false">{{ $test->nom_test }}</h5>
                            <p class="card-text" data-bs-toggle="collapse" data-bs-target="#questions-{{ $test->id_test }}" aria-expanded="false">
                                <strong>Durée :</strong> {{ $test->duree_test }} minutes<br>
                                <strong>Actif :</strong>
                                <span class="badge {{ $test->active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $test->active ? 'Oui' : 'Non' }}
                                </span>
                            </p>
                            <!-- Boutons pour chaque test -->
                            <div class="d-flex justify-content-between mt-3">
                                <a href="{{ route('reponquition.edit', $test->id_test) }}" class="btn btn-warning btn-sm" onclick="event.stopPropagation()">Modifier</a>
                                <a href="{{ route('questions.index', $test->id_test) }}" class="btn btn-info btn-sm" onclick="event.stopPropagation()">Gérer les questions</a>
                                <form action="{{ route('reponquition.destroy', $test->id_test) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce test ?')" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.stopPropagation()">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Questions associées -->
                    <div class="collapse" id="questions-{{ $test->id_test }}">
                        <div class="card card-body mt-2">
                            <h6 class="mb-3">Questions associées :</h6>
                            @if($test->questions->isNotEmpty())
                                @foreach($test->questions as $question)
                                    <div class="mb-3">
                                        <strong>Question :</strong> {{ $question->text_question }}<br>
                                        <strong>Type :</strong> {{ $question->type_question }}<br>
                                        @if($question->subQuestions->isNotEmpty())
                                            <ul>
                                                @foreach($question->subQuestions as $subQuestion)
                                                    <li>{{ $subQuestion->text_question ?? 'Texte vide' }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p>Aucune sous-question pour cette question.</p>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <p>Aucune question trouvée pour ce test.</p>
                            @endif
                            <!-- Bouton Gérer les questions -->
                            <a href="{{ route('questions.index', $test->id_test) }}" class="btn btn-info btn-sm mt-3" onclick="event.stopPropagation()">Gérer les questions</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">Aucun test disponible pour le moment.</div>
                </div>
            @endforelse
        </div>
    </div>

    <style>
        .test-card {
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .test-card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .card-title {
            font-weight: bold;
        }

        .badge {
            font-size: 0.9em;
        }

        .btn-sm {
            font-size: 0.9rem;
        }
    </style>
@endsection

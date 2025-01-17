@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1>Questions du Test : {{ $test->nom_test }}</h1>
        <a href="{{ route('questions.create', $test->id_test) }}" class="btn btn-primary mb-4">Ajouter une nouvelle Question</a>

        <!-- Conteneur des cartes -->
        <div class="row" id="sortable-container">
            @forelse ($questions as $question)
                <div class="col-md-6 mb-4 card-item" data-id="{{ $question->id_question }}">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <strong>Question :</strong> {{ $question->text_question }}
                            <span class="badge bg-secondary float-end">{{ ucfirst($question->type_question) }}</span>
                        </div>
                        <div class="card-body">
                            @if ($question->mandatoryFields && $question->mandatoryFields->count() > 0)
                                <h5 class="card-title">Champs obligatoires :</h5>
                                <ul class="list-group list-group-flush">
                                    @foreach ($question->mandatoryFields as $mandatoryField)
                                        <li class="list-group-item">
                                            {{ $mandatoryField->question_text }}
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted">Aucun champ obligatoire pour cette question.</p>
                            @endif

                            @if ($question->subQuestions && $question->subQuestions->count() > 0)
                                <h5 class="card-title mt-3">Sous-questions :</h5>
                                <ul class="list-group list-group-flush">
                                    @foreach ($question->subQuestions as $subQuestion)
                                        <li class="list-group-item">
                                            {{ $subQuestion->text_question ?? '(Texte non défini)' }}
                                            <span class="badge bg-info float-end">{{ ucfirst($subQuestion->type_question) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            @if ($question->type_question === 'multiple' && $question->multiple && $question->multiple->count() > 0)
                                <h5 class="card-title mt-3">Options multiples :</h5>
                                @foreach ($question->multiple as $multiple)
                                    <div class="card mb-2">
                                        <div class="card-header bg-info text-white">
                                            <strong>Libellé :</strong> {{ $multiple->text_question }}
                                        </div>
                                        <div class="card-body">
                                            <p><strong>De :</strong> {{ $multiple->nombre_de }}</p>
                                            <p><strong>À :</strong> {{ $multiple->nombre_a }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

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

    <!-- Inclure SortableJS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>

    <!-- Script pour activer le tri et mettre à jour l'ordre -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const container = document.getElementById("sortable-container");
            const testId = {{ $test->id_test }};
            const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

            if (!csrfToken) {
                console.error("CSRF token not found.");
                return;
            }

            Sortable.create(container, {
                animation: 150,
                handle: ".card",
                onEnd: function () {
                    const updatedOrder = Array.from(container.children).map((card, index) => ({
                        id: card.dataset.id,
                        order: index + 1 // Ordre basé sur l'index + 1
                    }));

                    // Envoi de l'ordre au serveur via AJAX
                    fetch(`/api/questions/update-order/${testId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ order: updatedOrder }),
                    })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(error => {
                                    throw new Error(error.message);
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log(data);
                        })
                        .catch(error => {
                            console.error('Error updating order:', error);
                        });
                }
            });
        });
    </script>
@endsection

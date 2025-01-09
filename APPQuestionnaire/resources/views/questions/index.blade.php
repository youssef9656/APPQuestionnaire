@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1>Questions du Test : {{ $test->name }}</h1>
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

            Sortable.create(container, {
                animation: 150,
                handle: ".card",
                onEnd: function () {
                    const order = Array.from(container.children).map((card, index) => ({
                        id: card.dataset.id,
                        order: index + 1 // Ordre basé sur l'index + 1
                    }));

                    // Envoi de l'ordre au serveur via AJAX
                    fetch("{{ route('questions.updateOrder', $test->id_test) }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ order })

                    })
                        .then(response => {
                            if (response.ok) {
                                console.log("Ordre des questions mis à jour avec succès.");
                            } else {
                                console.error("Erreur lors de la mise à jour de l'ordre des questions.");
                            }
                        })
                        .catch(error => console.error("Erreur réseau :", error));

                }
            });
        });
    </script>
@endsection

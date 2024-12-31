@extends('layout')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">Ajouter une question au test : <strong>{{ $test->nom_test }}</strong></h1>

        <form action="{{ route('questions.store', ['test' => $test->id_test]) }}" method="POST" class="shadow p-4 rounded bg-light">
            @csrf

            <!-- Question principale -->
            <div class="mb-3">
                <label for="text_question" class="form-label">Texte de la question principale :</label>
                <input type="text" name="text_question" id="text_question"
                       class="form-control" value="{{ old('text_question') }}" required>
            </div>

            <div class="mb-3">
                <label for="type_question" class="form-label">Type de la question :</label>
                <select name="type_question" id="type_question" class="form-select" required onchange="toggleSubQuestions()">
                    <option value="text" {{ old('type_question') == 'text' ? 'selected' : '' }}>Texte</option>
                    <option value="multiple_choice" {{ old('type_question') == 'multiple_choice' ? 'selected' : '' }}>Choix multiple</option>
                    <option value="true_false" {{ old('type_question') == 'true_false' ? 'selected' : '' }}>Vrai/Faux</option>
                    <option value="short_question" {{ old('type_question') == 'short_question' ? 'selected' : '' }}>Question courte</option>
                </select>
            </div>

            <!-- Zone des sous-questions -->
            <div id="sub_questions_container" class="mt-4" style="display:none;">
                <h5 class="mb-3">Sous-questions :</h5>
                <div id="sub_questions" class="row g-3">
                    <!-- Les sous-questions seront ajoutées ici -->
                </div>
                <button type="button" class="btn btn-outline-primary mt-3" onclick="addSubQuestion()">Ajouter une sous-question</button>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">Ajouter la question</button>
                <a href="{{ route('questions.index', $test->id_test) }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>

    <script>
        function toggleSubQuestions() {
            var type = document.getElementById("type_question").value;
            var subQuestionsContainer = document.getElementById("sub_questions_container");

            if (type === "short_question") {
                subQuestionsContainer.style.display = "block";
            } else {
                subQuestionsContainer.style.display = "none";
            }
            addSubQuestion()
        }

        function addSubQuestion() {
            var subQuestionsContainer = document.getElementById("sub_questions");
            var index = subQuestionsContainer.children.length;

            var newSubQuestion = document.createElement("div");
            newSubQuestion.classList.add("col-md-6");

            newSubQuestion.innerHTML = `
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="sub_questions[${index}][text]" class="form-label">Texte de la sous-question :</label>
                        <input type="text" name="sub_questions[${index}][text]" id="sub_questions[${index}][text]" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="sub_questions[${index}][type]" class="form-label">Type de l'entrée :</label>
                        <select name="sub_questions[${index}][type]" class="form-select" required>
                            <option value="text">Texte</option>
                            <option value="number">Numérique</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeSubQuestion(this)">Supprimer</button>
                </div>
            </div>
        `;
            subQuestionsContainer.appendChild(newSubQuestion);
        }

        function removeSubQuestion(button) {
            button.closest('.col-md-6').remove();
        }
    </script>
@endsection

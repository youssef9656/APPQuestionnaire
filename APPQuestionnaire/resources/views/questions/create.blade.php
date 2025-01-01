

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
                <select name="type_question" id="type_question" class="form-select" required onchange="handleQuestionTypeChange()">
                    <option value="text" {{ old('type_question') == 'text' ? 'selected' : '' }}>Texte</option>
                    <option value="true_false" {{ old('type_question') == 'true_false' ? 'selected' : '' }}>Options</option>
                    <option value="short_question" {{ old('type_question') == 'short_question' ? 'selected' : '' }}>Question courte</option>
                </select>
            </div>

            <!-- Sous-questions pour les questions courtes -->
            <div id="short_questions_container" class="mt-4" style="display:none;">
                <h5 class="mb-3">Sous-questions :</h5>
                <div id="short_questions" class="row g-3">
                    <!-- Les sous-questions seront ajoutées ici -->
                </div>
                <button type="button" class="btn btn-outline-primary mt-3" onclick="addShortQuestion()">Ajouter une sous-question</button>
            </div>

            <!-- Options -->
            <div id="options_container" class="mt-4" style="display:none;">
                <h5 class="mb-3">Options :</h5>
                <div class="mb-3">
                    <label for="option_type" class="form-label">Type d'options :</label>
                    <select id="option_type" class="form-select" onchange="toggleChoicesForm()">
                        <option value="unique">Choix unique</option>
                        <option value="multiple">Choix multiple</option>
                    </select>
                </div>
                <div id="choices_container" class="row g-3">
                    <!-- Les choix seront ajoutés ici -->
                </div>
                <button type="button" class="btn btn-outline-primary mt-3" onclick="addChoice()">Ajouter une option</button>
                <button type="button" class="btn btn-outline-warning mt-3 ms-2" onclick="addMandatoryField()">Ajouter un champ obligatoire</button>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">Ajouter la question</button>
                <a href="{{ route('questions.index', $test->id_test) }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>

    <script>
        // Afficher ou masquer les sous-questions et options selon le type de question
        function handleQuestionTypeChange() {
            var type = document.getElementById("type_question").value;
            var shortQuestionsContainer = document.getElementById("short_questions_container");
            var optionsContainer = document.getElementById("options_container");

            shortQuestionsContainer.style.display = (type === "short_question") ? "block" : "none";
            if (type === "short_question") {
                addShortQuestion();
            } else {
                document.querySelectorAll('#short_questions .col-md-6').forEach(function(a) {
                    a.remove();
                });
            }

            optionsContainer.style.display = (type === "true_false") ? "block" : "none";
        }

        // Ajouter une sous-question pour les questions courtes
        function addShortQuestion() {
            var shortQuestionsContainer = document.getElementById("short_questions");
            var index = shortQuestionsContainer.children.length;

            var newShortQuestion = document.createElement("div");
            newShortQuestion.classList.add("col-md-6");

            newShortQuestion.innerHTML = `
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
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeShortQuestion(this)">Supprimer</button>
                    </div>
                </div>
            `;
            shortQuestionsContainer.appendChild(newShortQuestion);
        }

        // Supprimer une sous-question
        function removeShortQuestion(button) {
            button.closest('.col-md-6').remove();
        }

        // Ajouter un choix pour les options
        function addChoice() {
            var choicesContainer = document.getElementById("choices_container");
            var index = choicesContainer.children.length;

            var newChoice = document.createElement("div");
            newChoice.classList.add("col-md-6");

            newChoice.innerHTML = `
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="choices[${index}][label]" class="form-label">Libellé du choix :</label>
                            <input type="text" name="choices[${index}][label]" id="choices[${index}][label]" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="choices[${index}][question]" class="form-label">Question associée (facultatif) :</label>
                            <input type="text" name="choices[${index}][question]" id="choices[${index}][question]" class="form-control">
                        </div>
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeChoice(this)">Supprimer</button>
                    </div>
                </div>
            `;
            choicesContainer.appendChild(newChoice);
        }

        // Supprimer un choix
        function removeChoice(button) {
            button.closest('.col-md-6').remove();
        }

        // Ajouter un champ obligatoire
        function addMandatoryField() {
            var choicesContainer = document.getElementById("choices_container");
            var index = choicesContainer.children.length;

            var newMandatoryField = document.createElement("div");
            newMandatoryField.classList.add("col-md-6");

            newMandatoryField.innerHTML = `
                <div class="card shadow-sm border-warning">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="mandatory[${index}][text]" class="form-label">Texte du champ obligatoire :</label>
                            <input type="text" name="mandatory[${index}][text]" id="mandatory[${index}][text]" class="form-control" required>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeMandatoryField(this)">Supprimer</button>
                    </div>
                </div>
            `;
            choicesContainer.appendChild(newMandatoryField);
        }

        // Supprimer un champ obligatoire
        function removeMandatoryField(button) {
            button.closest('.col-md-6').remove();
        }
    </script>
@endsection

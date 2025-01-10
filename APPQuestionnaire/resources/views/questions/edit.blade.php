@extends('layouts.app')
@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">Modifier la question : <strong>{{ $question->text_question }}</strong></h1>
        <form action="{{ route('questions.update', [$question->id_test, $question->id_question]) }}" method="POST" class="shadow p-4 rounded bg-light" onsubmit="return ValidateQuestion()">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="text_question" class="form-label">Texte de la question principale :</label>
                <input type="text" name="text_question" id="text_question" class="form-control" value="{{ old('text_question', $question->text_question) }}" required>
            </div>
            <div class="mb-3">
                <label for="type_question" class="form-label">Type de la question :</label>
                <select name="type_question" id="type_question" class="form-select" required onchange="handleQuestionTypeChange()">
                    <option>Sélectionnez le type de la question</option>
                    <option value="options_choix" {{ old('type_question', $question->type_question) == 'options_choix' || old('type_question', $question->type_question) == 'multiple' ? 'selected' : '' }}>Options</option>                    <option value="short_question" {{ old('type_question', $question->type_question) == 'short_question' ? 'selected' : '' }}>Question courte</option>
                </select>
            </div>

            <div id="short_questions_container" class="mt-4" style="display: {{ $question->type_question == 'short_question' ? 'block' : 'none' }}">
                <h5 class="mb-3">Sous-questions :</h5>
                <div id="short_questions" class="row g-3">
                    @if(!empty($question->subQuestions))
                        @foreach($question->subQuestions as $index => $subQuestion)
                            <div class="col-md-6">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="sub_questions[{{ $index }}][text]" class="form-label">Texte de la sous-question :</label>
                                            <input type="text" name="sub_questions[{{ $index }}][text]" id="sub_questions[{{ $index }}][text_question]" class="form-control" value="{{ old('sub_questions.'.$index.'.text', $subQuestion->text_question) }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="sub_questions[{{ $index }}][type]" class="form-label">Type de l'entrée :</label>
                                            <select name="sub_questions[{{ $index }}][type]" class="form-select" required>
                                                <option value="text" {{ $subQuestion->type_question == 'text' ? 'selected' : '' }}>Texte</option>
                                                <option value="number" {{ $subQuestion->type_question == 'number' ? 'selected' : '' }}>Numérique</option>
                                            </select>
                                        </div>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeShortQuestion(this)">Supprimer</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <button type="button" class="btn btn-outline-primary mt-3" onclick="addShortQuestion()">Ajouter une sous-question</button>
            </div>

            <div id="options_container" class="mt-4" style="display: {{ $question->type_question === 'options_choix' ? 'block' : 'none' }}">
                <h5 class="mb-3">Options :</h5>
                <div class="mb-3">
                    <label for="option_type" class="form-label">Type d'options :</label>
                    <select id="option_type" name="option_type" class="form-select" onchange="toggleChoicesForm()">
                        <option value="unique" {{ $question->type_question == 'unique' ? 'selected' : '' }}>Choix unique</option>
                        <option value="multiple" {{ $question->type_question == 'multiple' ? 'selected' : '' }}>Choix multiple</option>
                    </select>
                </div>
                <div id="choices_container" class="row g-3">
                    @if(!empty($question->options))
                        @foreach($question->options as $index => $option)
                            <div class="col-md-6">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="choices[{{ $index }}][label]" class="form-label">Libellé du choix :</label>
                                            <input type="text" name="choices[{{ $index }}][label]" id="choices[{{ $index }}][label]" class="form-control" value="{{ old('choices.'.$index.'.label', $option->text_option) }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="choices[{{ $index }}][question]" class="form-label">Question associée (facultatif) :</label>
                                            <input type="text" name="choices[{{ $index }}][question]" id="choices[{{ $index }}][question]" class="form-control" value="{{ old('choices.'.$index.'.question', $option->text_associé) }}">
                                        </div>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeChoice(this)">Supprimer</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <button type="button" class="btn btn-outline-primary mt-3" onclick="addChoice()" id="add_choice_button" style="display: {{ $question->type_question == 'unique' ? 'inline-block' : 'none' }}">Ajouter une option</button>
                <div id="choices_multiple_container" class="row g-3" style="margin-top: 0; display: {{ $question->type_question === 'multiple' ? 'flex' : 'none' }}">
                    @if(!empty($question->multiple))
                        @foreach($question->multiple as $index => $choiceMultiple)
                            <div class="col-md-6">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="choicesMultiple[{{ $index }}][label]" class="form-label">Libellé du choix :</label>
                                            <input type="text" name="choicesMultiple[{{ $index }}][label]" id="choicesMultiple[{{ $index }}][label]" class="form-control" value="{{ old('choicesMultiple.'.$index.'.text_question', $choiceMultiple->text_question) }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="choicesMultiple[{{ $index }}][de]" class="form-label">De :</label>
                                            <input type="number" name="choicesMultiple[{{ $index }}][de]" id="choicesMultiple[{{ $index }}][de]" class="form-control" value="{{ old('choicesMultiple.'.$index.'.nombre_de', $choiceMultiple->nombre_de) }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="choicesMultiple[{{ $index }}][a]" class="form-label">À :</label>
                                            <input type="number" name="choicesMultiple[{{ $index }}][a]" id="choicesMultiple[{{ $index }}][a]" class="form-control" value="{{ old('choicesMultiple.'.$index.'.nombre_a', $choiceMultiple->nombre_a) }}" required>
                                        </div>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeMultipleChoice(this)">Supprimer</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
{{--                    @dd($question->multiple)--}}
                    @endif
                </div>
                <button type="button" class="btn btn-outline-primary mt-3" onclick="addMultipleChoice()" id="add_multiple_choice_button" style="display: {{ $question->type_question == 'multiple' ? 'inline-block' : 'none' }}">Ajouter une option</button>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">Modifier la question</button>
                <a href="{{ route('questions.index', $question->id_test) }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.querySelector("form");

            form.addEventListener("submit", function (event) {
                const invalidFields = form.querySelectorAll(":invalid");

                invalidFields.forEach(field => {
                    if (field.hasAttribute("required") && field.offsetParent === null) {
                        field.closest(".hidden-container").style.display = "block";
                    }
                });
            });
        });

        function ValidateQuestion() {
            let type = document.getElementById("type_question").value;
            let sub_question_inputs = document.querySelectorAll('#short_questions input[name^="sub_questions"][name$="[text]"]');
            let choices = document.querySelectorAll('#choices_container input[name^="choices"][name$="[label]"]');
            let choicesMultiple = document.querySelectorAll('#choices_multiple_container input[name^="choicesMultiple"][name$="[label]"]');
            let mandatoryFields = document.querySelectorAll('#mandatory_fields_container input[name^="mandatory"][name$="[text]"]');

            switch (type) {
                case "short_question":
                    for (let input of sub_question_inputs) {
                        if (input.value.trim() === "") {
                            alert("Veuillez remplir tous les champs des sous-questions.");
                            return false;
                        }
                    }
                    break;
                case "options_choix":
                    if (choices.length === 0 && choicesMultiple.length === 0) {
                        alert("Veuillez ajouter au moins une option.");
                        return false;
                    }
                    if (document.getElementById("option_type").value === "unique") {
                        for (let choice of choices) {
                            if (choice.value.trim() === "") {
                                alert("Veuillez remplir tous les champs des options.");
                                return false;
                            }
                        }
                    } else {
                        for (let choice of choicesMultiple) {
                            for (let choice of choicesMultiple) {
                                if (choice.value.trim() === "") {
                                    alert("Veuillez remplir tous les champs des options multiples.");
                                    return false;
                                }
                                let nombreDe = parseInt(choice.closest('.card-body').querySelector('input[name$="[de]"]').value, 5);
                                let nombreA = parseInt(choice.closest('.card-body').querySelector('input[name$="[a]"]').value, 5);
                                if (isNaN(nombreDe) || isNaN(nombreA) || nombreDe < 0 || nombreA < 0 || nombreDe > 9999 || nombreA > 9999) {
                                    alert("Les valeurs de 'De' et 'À' doivent être des nombres entre 0 et 5000.");
                                    return false;
                                }
                            }
                        }
                    }
                    for (let mandatoryField of mandatoryFields) {
                        if (mandatoryField.value.trim() === "") {
                            alert("Veuillez remplir tous les champs obligatoires.");
                            return false;
                        }
                    }
                    break;
            }
            return true;
        }

        function handleQuestionTypeChange() {
            var type = document.getElementById("type_question").value;
            var shortQuestionsContainer = document.getElementById("short_questions_container");
            var optionsContainer = document.getElementById("options_container");

            shortQuestionsContainer.style.display = (type === "short_question") ? "block" : "none";
            optionsContainer.style.display = (type === "options_choix") ? "block" : "none";

            if (type !== "short_question") {
                document.querySelectorAll('#short_questions .col-md-6').forEach(el => el.remove());
            }
            toggleChoicesForm();
        }

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
                    <input type="text" name="sub_questions[${index}][text]" id="sub_questions[${index}][text]" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="sub_questions[${index}][type]" class="form-label">Type de l'entrée :</label>
                    <select name="sub_questions[${index}][type]" id="sub_questions[${index}][type]" class="form-select" required>
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

        function removeShortQuestion(button) {
            button.closest('.col-md-6').remove();
        }

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
                    <input type="text" name="choices[${index}][label]" id="choices[${index}][label]" class="form-control">
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

        function removeChoice(button) {
            button.closest('.col-md-6').remove();
        }

        function addMandatoryField() {
            var mandatoryFieldsContainer = document.getElementById("mandatory_fields_container");
            var index = mandatoryFieldsContainer.children.length;
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
            mandatoryFieldsContainer.appendChild(newMandatoryField);
        }

        function removeMandatoryField(button) {
            button.closest('.col-md-6').remove();
        }

        function addMultipleChoice() {
            var choicesMultipleContainer = document.getElementById("choices_multiple_container");
            var index = choicesMultipleContainer.children.length;
            var newChoice = document.createElement("div");
            newChoice.classList.add("col-md-6");
            newChoice.innerHTML = `
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <label for="choicesMultiple[${index}][label]" class="form-label">Libellé du choix :</label>
                    <input type="text" name="choicesMultiple[${index}][label]" id="choicesMultiple[${index}][label]" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="choicesMultiple[${index}][de]" class="form-label">De :</label>
                    <input type="number" name="choicesMultiple[${index}][de]" id="choicesMultiple[${index}][de]" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="choicesMultiple[${index}][a]" class="form-label">À :</label>
                    <input type="number" name="choicesMultiple[${index}][a]" id="choicesMultiple[${index}][a]" class="form-control" required>
                </div>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeMultipleChoice(this)">Supprimer</button>
            </div>
        </div>
    `;
            choicesMultipleContainer.appendChild(newChoice);
        }

        function removeMultipleChoice(button) {
            button.closest('.col-md-6').remove();
        }

        function toggleChoicesForm() {
            var optionType = document.getElementById("option_type").value;
            var addChoiceButton = document.getElementById("add_choice_button");
            var addMultipleChoiceButton = document.getElementById("add_multiple_choice_button");
            var choicesContainer = document.getElementById("choices_container");
            var choicesMultipleContainer = document.getElementById("choices_multiple_container");

            addChoiceButton.style.display = (optionType === "unique") ? "inline-block" : "none";
            addMultipleChoiceButton.style.display = (optionType === "multiple") ? "inline-block" : "none";
            choicesContainer.style.display = (optionType === "unique") ? "flex" : "none";
            choicesMultipleContainer.style.display = (optionType === "multiple") ? "flex" : "none";

            if (choicesContainer.children.length === 0 && optionType === "unique") {
                addChoice();
            }
            if (choicesMultipleContainer.children.length === 0 && optionType === "multiple") {
                addMultipleChoice();
            }
        }

        handleQuestionTypeChange();
    </script>
@endsection

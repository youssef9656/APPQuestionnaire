<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Questions and Answers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('user.css')}}" type="text/css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</head>
@extends('layouts.layoutuser')

@section('contentuser')
<body>
<div id="bodyQui">



{{--<script>--}}
{{--    setInterval(function() {--}}
{{--        // Make an AJAX call to increment tempsTest in the session--}}
{{--        fetch('{{ route('updateTempsTest') }}', {--}}
{{--            method: 'POST',--}}
{{--            headers: {--}}
{{--                'Content-Type': 'application/json',--}}
{{--                'X-CSRF-TOKEN': '{{ csrf_token() }}'--}}
{{--            },--}}
{{--            body: JSON.stringify({ increment: true })--}}
{{--        })--}}
{{--            .then(response => response.json())--}}
{{--            .then(data => {--}}
{{--                console.log('Updated tempsTest:', data.tempsTest);--}}
{{--            });--}}
{{--    }, 1000);  // Every 1 second--}}

{{--</script>--}}

<div id="div_circle_temp" >
    <div class="progress-container" >
        <div class="circular-progress">
            <span class="progress-text">1/10</span>
        </div>
    </div>
    <div class="form-container w-100 text-centre" style="display: flex;flex-flow: column; align-items: center;">
        <h1>Questions</h1>


        <form class="form-container1" action="{{ route('reponses.store', $id_test) }}" method="POST">
            @csrf
            @foreach ($Questions as $question)
                <div class="question" id="question-{{ $question['id_question'] }}">
                    <h3>Question: {{ $question['text_question'] }}</h3>

                    <!-- Affichage des options -->
                    @if (count($question['options']) > 0)
                        @foreach ($question['options'] as $option)
                            <div class="option">
                                @if (!empty($option['text_option']))
                                    <div>
                                        <label>
                                            <input
                                                class="form-check-input me-2"
                                                type="radio"
                                                name="reponses[{{ $question['id_question'] }}][id_option_reponse]"
                                                value="{{ $option['id_option'] }}"
                                                onchange="toggleMandatoryOptions('{{ $option['id_option'] }}')"
                                                required>
                                            {{ $option['text_option'] }}
                                        </label>
                                    </div>

                                    <!-- Affichage des champs associés aux options -->
                                    @if (!empty($option['text_associé']))
                                        <div
                                            id="mandatory-{{ $option['id_option'] }}"
                                            class="mandatory-options"
                                            style="display: none;">
                                            <div class="form-check">
                                                <label>
                                                    {{ $option['question_text'] }}
                                                    <input
                                                        class="form-control mb-2"
                                                        type="text"
                                                        name="reponses[{{ $question['id_question'] }}][mandatoryOption-{{ $option['id_option'] }}]"
                                                        placeholder="Entrez votre réponse"
                                                        required>
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    @endif

                    <!-- Affichage des sous-questions -->
                    @if (count($question['subQuestions']) > 0)
                        @foreach ($question['subQuestions'] as $index => $subQuestion)
                            <div class="sub-question">
                                <label for="subQuestion-{{ $question['id_question'] }}-{{ $subQuestion['id_question'] }}">
                                    {{ $subQuestion['text_question'] }}
                                </label>
                                <input
                                    class="form-control mb-2"
                                    type="{{ $subQuestion['type_question'] }}"
                                    name="reponses[{{ $question['id_question'] }}][subQuestions{{ $question['id_question'] }}{{ $index + 1 }}][{{ $subQuestion['id_question'] }}]"
                                    id="subQuestion-{{ $question['id_question'] }}-{{ $subQuestion['id_question'] }}"
                                    placeholder="Entrez votre réponse"
                                    required >
                            </div>
                        @endforeach
                    @endif

                    <!-- Affichage des réponses multiples -->
                    @if (count($question['multiple']) > 0)
                        @foreach ($question['multiple'] as $multiple)
                            <div class="multiple-input">
                                <label for="multiple-{{ $multiple['id_multiple'] }}">
                                    {{ $multiple['text_question'] ?? 'Entrer un nombre' }}
                                </label>
                                <input
                                    class="form-control mb-2"
                                    type="number"
                                    name="reponses[{{ $question['id_question'] }}][multiple-{{ $multiple['id_multiple'] }}]"
                                    id="multiple-{{ $multiple['id_multiple'] }}"
                                    min="{{ $multiple['nombre_de'] }}"
                                    max="{{ $multiple['nombre_a'] }}"
                                    placeholder="Valeur entre {{ $multiple['nombre_de'] }} et {{ $multiple['nombre_a'] }}"
                                    required>
                            </div>
                        @endforeach
                    @endif

                    <!-- Affichage des champs obligatoires spécifiques -->
                    @if (count($question['OptionChoixObligatoire']) > 0)
                        @foreach ($question['OptionChoixObligatoire'] as $OptionChoixObligatoire)
                            <div class="mandatory-input">
                                <label for="OptionChoixObligatoire-{{ $OptionChoixObligatoire['id_option'] }}">
                                    {{ $OptionChoixObligatoire['question_text'] }}
                                </label>
                                <input
                                    class="form-control mb-2"
                                    type="text"
                                    name="reponses[{{ $OptionChoixObligatoire['id_question'] }}][mandatory-{{ $OptionChoixObligatoire['id_option'] }}]"
                                    id="OptionChoixObligatoire-{{ $OptionChoixObligatoire['id_option'] }}" required>
                            </div>
                        @endforeach
                    @endif
                </div>
            @endforeach

            <!-- Bouton de soumission -->
            <button type="submit" class="btn-validate">
                Soumettre les réponses
            </button>
        </form>

    </div>



    @php
////        session_start();
//        $user = $_SESSION['userA'];
//        // Réaffecte la session pour conserver les changements
//         $duree_test = null;
//    //     echo $user['tempsTest'] ;
//        $tempsTest  = $user['tempsTest']/60;
//    $tempsTestFormate = number_format($tempsTest, 2, '.', '');

    @endphp




    @foreach ($tests as $test)
        @if ($test['id_test'] == $id_test)
            @php
                $duree_test = $test['duree_test'] ;
            @endphp
          @break
        @endif
    @endforeach
    @php
        $duree_test = $duree_test ?? 0; // Assurez-vous que la durée est définie
    @endphp
    <div id="timer" class="timer" data-duration="{{ $duree_test * 60 }}"><br>
        <span id="time-display">{{ $duree_test }} </span>min
    </div>
    <script>
        // Récupérer la durée du test en secondes
        let dureeTest = parseInt(document.getElementById('timer').getAttribute('data-duration'));

        // Fonction pour mettre à jour l'affichage du temps restant
        function updateTimer() {
            let minutes = Math.floor(dureeTest / 60); // Calcul des minutes restantes
            let secondes = dureeTest % 60; // Calcul des secondes restantes

            // Formater le temps en "minutes:secondes"
            let formattedTime = `${minutes}:${secondes < 10 ? '0' + secondes : secondes}`;

            // Mettre à jour l'affichage
            document.getElementById('timer').innerText = `${formattedTime} min`;

            // Décrémenter le temps
            if (dureeTest > 0) {
                dureeTest--;
            } else {
                clearInterval(timerInterval); // Arrêter le compte à rebours lorsque le temps est écoulé
                alert("Le temps est écoulé !");
            }
        }

        // Lancer le compte à rebours
        let timerInterval = setInterval(updateTimer, 1000); // Mettre à jour chaque seconde
    </script>
</div>






<script>
    function toggleMandatoryOptions(optionId) {
        // Cache tous les champs obligatoires
        document.querySelectorAll('.mandatory-options').forEach(div => div.style.display = 'none');

        // Affiche le champ correspondant
        const mandatoryDiv = document.getElementById(`mandatory-${optionId}`);
        if (mandatoryDiv) {
            mandatoryDiv.style.display = 'block';

            // Ajoute l'attribut required au champ visible
            const input = mandatoryDiv.querySelector('input, select, textarea');
            if (input) {
                input.setAttribute('required', 'required');
            }
        }

        // Supprime l'attribut required des autres champs
        document.querySelectorAll('.mandatory-options input, .mandatory-options select, .mandatory-options textarea')
            .forEach(input => {
                if (!input.closest(`#mandatory-${optionId}`)) {
                    input.removeAttribute('required');
                }
            });
    }
    document.addEventListener("DOMContentLoaded", () => {
        const questions = document.querySelectorAll(".question");
        const submitButton = document.querySelector(".btn-validate");
        const progressText = document.querySelector(".progress-text");
        const progressCircle = document.querySelector(".circular-progress");

        let currentQuestionIndex = 0;

        // Met à jour la barre de progression
        function updateProgress() {
            const totalQuestions = questions.length;
            const progressPercentage = ((currentQuestionIndex + 1) / totalQuestions) * 100;
            progressText.textContent = `${currentQuestionIndex + 1}/${totalQuestions}`;
            progressCircle.style.background = `conic-gradient(#0d6efd ${progressPercentage}%, #e0e0e0 ${progressPercentage}% 100%)`;
        }

        // Cache toutes les questions sauf la première
        questions.forEach((question, index) => {
            if (index !== 0) question.style.display = "none";
        });

        // Cache le bouton "Soumettre les réponses" jusqu'à la dernière question
        submitButton.style.display = "none";

        // Ajoute les boutons "Précédent" et "Suivant" à chaque question
        questions.forEach((question, index) => {
            const buttonContainer = document.createElement("div");
            buttonContainer.className = "button-container mt-3";

            // Bouton "Précédent"
            if (index > 0) {
                const prevButton = document.createElement("button");
                prevButton.textContent = "Précédent";
                prevButton.type = "button";
                prevButton.className = "btn btn-secondary me-2";
                prevButton.onclick = () => {
                    showPreviousQuestion();
                    updateProgress(); // Met à jour la progression lors du retour
                };
                buttonContainer.appendChild(prevButton);
            }

            // Bouton "Suivant"
            if (index < questions.length - 1) {
                const nextButton = document.createElement("button");
                nextButton.textContent = "Suivant";
                nextButton.type = "button";
                nextButton.className = "btn btn-primary";
                nextButton.onclick = () => {
                    showNextQuestion();
                    updateProgress(); // Met à jour la progression lors de l'avancement
                };
                buttonContainer.appendChild(nextButton);
            }

            question.appendChild(buttonContainer);
        });

        // Fonction pour afficher la question suivante
        function showNextQuestion() {
            const currentQuestion = questions[currentQuestionIndex];
            const nextQuestion = questions[currentQuestionIndex + 1];

            const inputs = currentQuestion.querySelectorAll("input[required]");
            const allAnswered = Array.from(inputs).every(input => input.checkValidity());

            if (allAnswered) {
                currentQuestion.style.display = "none"; // Cache la question actuelle
                nextQuestion.style.display = "block"; // Affiche la question suivante
                currentQuestionIndex++; // Met à jour l'index de la question actuelle

                // Si c'est la dernière question, affiche le bouton "Soumettre les réponses"
                if (currentQuestionIndex === questions.length - 1) {
                    submitButton.style.display = "block";
                }
            } else {
                alert("Veuillez répondre à toutes les questions avant de continuer.");
            }
        }

        // Fonction pour afficher la question précédente
        function showPreviousQuestion() {
            const currentQuestion = questions[currentQuestionIndex];
            const previousQuestion = questions[currentQuestionIndex - 1];

            currentQuestion.style.display = "none"; // Cache la question actuelle
            previousQuestion.style.display = "block"; // Affiche la question précédente
            currentQuestionIndex--; // Met à jour l'index de la question actuelle

            // Cache le bouton "Soumettre les réponses" si ce n'est pas la dernière question
            submitButton.style.display = "none";
        }

        // Met à jour la progression lors du chargement de la page
        updateProgress();
    });
    document.addEventListener("DOMContentLoaded", () => {
        const submitButton = document.querySelector(".btn-validate");
        const timerElement = document.getElementById("timer");
        let timerDuration = parseInt(timerElement.getAttribute("data-duration"));
        let timerInterval;

        // بدء العد التنازلي
        function startTimer() {
            timerInterval = setInterval(() => {
                if (timerDuration <= 0) {
                    clearInterval(timerInterval);
                    handleTimeOut();
                } else {
                    timerDuration--;
                    updateTimerDisplay();
                }
            }, 1000);
        }

        // تحديث العرض للعد التنازلي
        function updateTimerDisplay() {
            const minutes = Math.floor(timerDuration / 60);
            const seconds = timerDuration % 60;
            document.getElementById("time-display").textContent = `Temps restant: ${minutes} min ${seconds} sec`;
        }

        // معالجة انتهاء الوقت
        function handleTimeOut() {
            alert("Le temps est écoulé!");

            // إلغاء شرط "required" لجميع الحقول
            const allInputs = document.querySelectorAll("input, select, textarea");
            allInputs.forEach(input => {
                input.removeAttribute("required");
            });

            allInputs.forEach(input => {
                if (!input.value) {
                    input.value = null; //
                }
            });

            //م "Soumettre les réponses"
            submitButton.click();
        }

        // بدء العداد عند تحميل الصفحة
        startTimer();
    });

</script>
</div>
</body>
</html>


@endsection















{{--<!DOCTYPE html>--}}
{{--<html lang="en">--}}
{{--<head>--}}
{{--    <meta charset="UTF-8">--}}
{{--    <meta name="viewport" content="width=device-width, initial-scale=1.0">--}}
{{--    <title>Questions and Answers</title>--}}
{{--    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>--}}
{{--    <style>--}}
{{--        body {--}}
{{--            font-family: 'Arial', sans-serif;--}}
{{--            background: linear-gradient(135deg, #e3f2fd, #bbdefb);--}}
{{--            color: #333;--}}
{{--            margin: 0;--}}
{{--            padding: 0;--}}
{{--            display: flex;--}}
{{--            justify-content: center;--}}
{{--            align-items: center;--}}
{{--            flex-direction: column;--}}
{{--            min-height: 100vh;--}}
{{--        }--}}

{{--        h1 {--}}
{{--            color: #0d6efd;--}}
{{--            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);--}}
{{--            margin-bottom: 20px;--}}
{{--            text-align: center;--}}
{{--        }--}}

{{--        .btn-validate {--}}
{{--            display: block;--}}
{{--            width: 100%;--}}
{{--            padding: 10px;--}}
{{--            font-size: 18px;--}}
{{--            background-color: #0d6efd;--}}
{{--            border: none;--}}
{{--            border-radius: 5px;--}}
{{--            color: #fff;--}}
{{--            transition: 0.3s ease;--}}
{{--        }--}}

{{--        .btn-validate:hover {--}}
{{--            background-color: #084298;--}}
{{--        }--}}

{{--        .question {--}}
{{--            margin-bottom: 30px;--}}
{{--        }--}}

{{--        .mandatory-options {--}}
{{--            padding-left: 20px;--}}
{{--            border-left: 3px solid #0d6efd;--}}
{{--        }--}}

{{--        .form-container {--}}
{{--            background: #fff;--}}
{{--            border-radius: 10px;--}}
{{--            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);--}}
{{--            padding: 20px;--}}
{{--            width: 90%;--}}
{{--            max-width: 600px;--}}
{{--        }--}}
{{--    </style>--}}
{{--</head>--}}
{{--<body>--}}

{{--<h1>Questions</h1>--}}

{{--<form class="form-container" action="{{ route('reponses.store', $id_test) }}" method="POST">--}}
{{--    @csrf--}}
{{--    @foreach ($Questions as $question)--}}
{{--        <div class="question" id="question-{{ $question['id_question'] }}">--}}
{{--            <h3>Question: {{ $question['text_question'] }}</h3>--}}
{{--            @if (count($question['options']) > 0)--}}
{{--                @foreach ($question['options'] as $option)--}}
{{--                    <div class="option">--}}
{{--                        @if (!empty($option['text_associé']))--}}
{{--                            <div>--}}
{{--                                <label>--}}
{{--                                    <input class="form-check-input me-2" type="radio" name="reponses[{{ $question['id_question'] }}][id_option_reponse]" value="{{ $option['id_option'] }}"--}}
{{--                                           onchange="toggleMandatoryOptions('{{ $option['id_option'] }}')" required>--}}
{{--                                    {{ $option['text_option'] }}--}}
{{--                                </label>--}}
{{--                            </div>--}}
{{--                        @endif--}}

{{--                        @if (!empty($question['OptionChoixObligatoire']))--}}
{{--                            <div id="mandatory-{{ $option['id_option'] }}" class="mandatory-options" style="display: none;">--}}
{{--                                @foreach ($question['OptionChoixObligatoire'] as $mandatory)--}}
{{--                                    @if ($option['id_option'] === $mandatory['id_option'])--}}
{{--                                        <div class="form-check">--}}
{{--                                            <input class="form-control mb-2" type="text"--}}
{{--                                                   name="reponses[{{ $question['id_question'] }}][mandatoryOption-{{ $option['id_option'] }}]"--}}
{{--                                                   placeholder="Entrez votre réponse" required>--}}
{{--                                            <label class="form-check-label" for="mandatory-{{ $mandatory['id_option'] }}">--}}
{{--                                                {{ $mandatory['question_text'] }}--}}
{{--                                            </label>--}}
{{--                                        </div>--}}
{{--                                    @endif--}}
{{--                                @endforeach--}}
{{--                            </div>--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                @endforeach--}}
{{--            @elseif(count($question['subQuestions']) > 0)--}}
{{--                @foreach ($question['subQuestions'] as $index => $subQuestion)--}}
{{--                    <div class="sub-question">--}}
{{--                        <label for="subQuestion-{{ $question['id_question'] }}-{{ $subQuestion['id_question'] }}">--}}
{{--                            {{ $subQuestion['text_question'] }}--}}
{{--                        </label>--}}
{{--                        <input class="form-control mb-2" type="{{ $subQuestion['type_question'] }}"--}}
{{--                               name="reponses[{{ $question['id_question'] }}][subQuestions{{ $question['id_question'] }}{{ $index + 1 }}][{{ $subQuestion['id_question'] }}]"--}}
{{--                               id="subQuestion-{{ $question['id_question'] }}-{{ $subQuestion['id_question'] }}"--}}
{{--                               placeholder="Entrez votre réponse" required>--}}
{{--                    </div>--}}
{{--                @endforeach--}}

{{--            @elseif(count($question['multiple']) > 0)--}}
{{--                @foreach ($question['multiple'] as $multiple)--}}
{{--                    <div class="multiple-input">--}}
{{--                        <label for="multiple-{{ $multiple['id_multiple'] }}">{{ $multiple['text_question'] ?? 'Entrer un nombre' }}</label>--}}
{{--                        <input class="form-control mb-2" type="number"--}}
{{--                               name="reponses[{{ $question['id_question'] }}][multiple-{{ $multiple['id_multiple'] }}]"--}}
{{--                               id="multiple-{{ $multiple['id_multiple'] }}"--}}
{{--                               min="{{ $multiple['nombre_de'] }}"--}}
{{--                               max="{{ $multiple['nombre_a'] }}"--}}
{{--                               placeholder="Valeur entre {{ $multiple['nombre_de'] }} et {{ $multiple['nombre_a'] }}">--}}
{{--                    </div>--}}
{{--                @endforeach--}}
{{--            @endif--}}
{{--        </div>--}}
{{--    @endforeach--}}

{{--    <button type="submit" class="btn-validate">--}}
{{--        Soumettre les réponses--}}
{{--    </button>--}}
{{--</form>--}}

{{--<script>--}}
{{--    function toggleMandatoryOptions(optionId) {--}}
{{--        document.querySelectorAll('.mandatory-options').forEach(div => div.style.display = 'none');--}}
{{--        const mandatoryDiv = document.getElementById(`mandatory-${optionId}`);--}}
{{--        if (mandatoryDiv) {--}}
{{--            mandatoryDiv.style.display = 'block';--}}
{{--        }--}}
{{--    }--}}
{{--    --}}
{{--</script>--}}

{{--</body>--}}
{{--</html>--}}

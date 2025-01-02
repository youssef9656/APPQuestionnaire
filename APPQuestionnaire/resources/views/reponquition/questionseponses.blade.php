<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Questions and Answers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 100vh;
        }

        h1 {
            color: #0d6efd;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            text-align: center;
        }

        .btn-validate {
            display: block;
            width: 100%;
            padding: 10px;
            font-size: 18px;
            background-color: #0d6efd;
            border: none;
            border-radius: 5px;
            color: #fff;
            transition: 0.3s ease;
        }

        .btn-validate:hover {
            background-color: #084298;
        }

        .question {
            margin-bottom: 30px;
        }

        .mandatory-options {
            padding-left: 20px;
            border-left: 3px solid #0d6efd;
        }

        .form-container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 90%;
            max-width: 600px;
        }
    </style>
</head>
<body>

<h1>Questions</h1>

<form class="form-container" action="{{ route('reponses.store', $id_test) }}" method="POST">
    @csrf
    @foreach ($Questions as $question)
        <div class="question" id="question-{{ $question['id_question'] }}">
            <h3>Question: {{ $question['text_question'] }}</h3>
            @if (count($question['options']) > 0)
                @foreach ($question['options'] as $option)
                    <div class="option">
                        @if (!empty($option['text_associé']))
                            <div>
                                <label>
                                    <input class="form-check-input me-2" type="radio" name="reponses[{{ $question['id_question'] }}][id_option_reponse]" value="{{ $option['id_option'] }}"
                                           onchange="toggleMandatoryOptions('{{ $option['id_option'] }}')" required>
                                    {{ $option['text_option'] }}
                                </label>
                            </div>
                        @endif

                        @if (!empty($question['OptionChoixObligatoire']))
                            <div id="mandatory-{{ $option['id_option'] }}" class="mandatory-options" style="display: none;">
                                @foreach ($question['OptionChoixObligatoire'] as $mandatory)
                                    @if ($option['id_option'] === $mandatory['id_option'])
                                        <div class="form-check">
                                            <input class="form-control mb-2" type="text"
                                                   name="reponses[{{ $question['id_question'] }}][mandatoryOption-{{ $option['id_option'] }}]"
                                                   placeholder="Entrez votre réponse" required>
                                            <label class="form-check-label" for="mandatory-{{ $mandatory['id_option'] }}">
                                                {{ $mandatory['question_text'] }}
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            @elseif(count($question['subQuestions']) > 0)
                @foreach ($question['subQuestions'] as $index => $subQuestion)
                    <div class="sub-question">
                        <label for="subQuestion-{{ $question['id_question'] }}-{{ $subQuestion['id_question'] }}">
                            {{ $subQuestion['text_question'] }}
                        </label>
                        <input class="form-control mb-2" type="{{ $subQuestion['type_question'] }}"
                               name="reponses[{{ $question['id_question'] }}][subQuestions{{ $question['id_question'] }}{{ $index + 1 }}][{{ $subQuestion['id_question'] }}]"
                               id="subQuestion-{{ $question['id_question'] }}-{{ $subQuestion['id_question'] }}"
                               placeholder="Entrez votre réponse" required>
                    </div>
                @endforeach

            @elseif(count($question['multiple']) > 0)
                @foreach ($question['multiple'] as $multiple)
                    <div class="multiple-input">
                        <label for="multiple-{{ $multiple['id_multiple'] }}">{{ $multiple['text_question'] ?? 'Entrer un nombre' }}</label>
                        <input class="form-control mb-2" type="number"
                               name="reponses[{{ $question['id_question'] }}][multiple-{{ $multiple['id_multiple'] }}]"
                               id="multiple-{{ $multiple['id_multiple'] }}"
                               min="{{ $multiple['nombre_de'] }}"
                               max="{{ $multiple['nombre_a'] }}"
                               placeholder="Valeur entre {{ $multiple['nombre_de'] }} et {{ $multiple['nombre_a'] }}">
                    </div>
                @endforeach
            @endif
        </div>
    @endforeach

    <button type="submit" class="btn-validate">
        Soumettre les réponses
    </button>
</form>

<script>
    function toggleMandatoryOptions(optionId) {
        document.querySelectorAll('.mandatory-options').forEach(div => div.style.display = 'none');
        const mandatoryDiv = document.getElementById(`mandatory-${optionId}`);
        if (mandatoryDiv) {
            mandatoryDiv.style.display = 'block';
        }
    }
</script>

</body>
</html>

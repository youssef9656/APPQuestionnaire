<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Questions and Answers</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .question {
            margin-bottom: 20px;
        }
        .answers {
            list-style: none;
            padding: 0;
        }
        .answers li {
            padding: 10px;
            background: #f1f1f1;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
<h1>{{ $test->nom_test }} - Questions</h1>
@foreach ($questions as $question)
<div class="question">
    <h3>{{ $question->question_text }}</h3>
    <ul class="answers">
        @foreach ($question->reponses as $reponse)
        <li>{{ $reponse->reponse_text }}</li>
        @endforeach
    </ul>
</div>
@endforeach
</body>
</html>

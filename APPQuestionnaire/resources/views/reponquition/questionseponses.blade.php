<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Questions and Answers</title>

</head>
<body>
{{--<h1>{{ $test->nom_test }} - Questions</h1>--}}
@foreach ($Questions as $question)
    <div class="question">
        @if( $question->type_question === "option" )
            <h3>{{ $question }}</h3>

        @endif
{{--        <h3>{{ $question->type_question }}</h3>--}}
{{--        <ul class="answers">--}}
{{--            @foreach ($question->reponses as $reponse)--}}
{{--                <li>{{ $reponse->reponse_text }}</li>--}}
{{--            @endforeach--}}
{{--        </ul>--}}
    </div>
@endforeach
</body>
</html>

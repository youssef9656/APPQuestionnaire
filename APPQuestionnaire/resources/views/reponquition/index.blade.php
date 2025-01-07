<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Active Tests</title>
    @extends('layouts.layoutuser')

    @section('contentuser')
        <style>
             /*Style global */
            .body {
                font-family: 'Arial', sans-serif;
                background: linear-gradient(135deg, #f5f7fa, #e4ebf7);
                color: #333;
                margin: 0;
                padding: 20px;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
            }

            h1 {
                color: #0d6efd;
                text-align: center;
                margin-bottom: 20px;
                text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.1);
            }

            /* Conteneur principal */
            .test-container {
                background: #ffffff;
                padding: 30px;
                border-radius: 15px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                max-width: 600px;
                width: 100%;
            }

            /* Liste des tests */
            .test-buttons {
                list-style-type: none;
                padding: 0;
                margin: 0;
            }

            .test-buttons li {
                margin: 15px 0;
            }

            /* Boutons de test */
            .test-buttons a {
                display: block;
                text-decoration: none;
                font-size: 16px;
                font-weight: bold;
                color: #ffffff;
                background: linear-gradient(135deg, #0d6efd, #5a8efc);
                padding: 15px;
                border-radius: 10px;
                text-align: center;
                transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease;
            }

            .test-buttons a:hover {
                background: linear-gradient(135deg, #084298, #4576e7);
                transform: translateY(-5px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            }

            /* Informations sur le test */
            .test-buttons a span {
                display: block;
                font-size: 14px;
                font-weight: normal;
                color: rgba(255, 255, 255, 0.8);
                margin-top: 5px;
            }
        </style>
</head>
<body>
<DIV class="body">

<div class="test-container">
    <h1>Available Tests</h1>
    <ul class="test-buttons">
        @foreach ($tests as $test)
            <li>
                <a href="{{ route('questionsrepose', ['id' => $test->id_test]) }}">
                    {{ $test->nom_test }}
                    <span>Duration: {{ $test->duree_test }} mins</span>
                </a>
            </li>
        @endforeach
    </ul>
</div>
</DIV>

</body>

@endsection
</html>

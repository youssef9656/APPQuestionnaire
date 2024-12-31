<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Active Tests</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
        }
        h1 {
            color: #333;
        }
        .test-buttons {
            list-style-type: none;
            padding: 0;
        }
        .test-buttons li {
            margin: 10px 0;
        }
        .test-buttons a {
            text-decoration: none;
            color: white;
            background-color: #007BFF;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .test-buttons a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<h1>Available Tests</h1>
<ul class="test-buttons">
    @foreach ($tests as $test)
        <li>
            <a href="{{ route('questionsrepose', ['id' => $test->id_test]) }}">
                {{ $test->nom_test }} - Duration: {{ $test->duree_test }} mins
            </a>
        </li>
    @endforeach
</ul>
</body>
</html>

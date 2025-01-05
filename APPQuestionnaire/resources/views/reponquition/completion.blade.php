@extends('layouts.layoutuser')

@section('contentuser')
    <div class="container mt-5 text-center">
        <div class="card shadow-lg">
            <div class="card-body">
                <h1 class="text-success">🎉 Félicitations ! 🎉</h1>
                <p class="mt-3">
                    Vous avez terminé le test avec succès. Nous vous remercions pour votre participation !
                </p>

                @if (isset($score))
                    <h2 class="mt-4">Votre score : <span class="text-primary">{{ $score }}</span>/100</h2>
                @endif

{{--                <div class="mt-4">--}}
{{--                    <a href="{{ route('tests.index') }}" class="btn btn-primary">Retour à la liste des tests</a>--}}
{{--                    <a href="{{ route('home') }}" class="btn btn-secondary">Accueil</a>--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
@endsection

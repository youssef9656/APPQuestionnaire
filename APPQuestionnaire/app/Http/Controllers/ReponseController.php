<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\Test;
use Illuminate\Support\Facades\Auth;

class ReponseController extends Controller
{
    public function index()
    {
        session_start();

        $user   =   $_SESSION['userA'] ;

        // Vérifiez si l'utilisateur est connecté
        if ($user) {
            // Vérifiez si le rôle est "user"
            if ($user->role === 'user') {
                // Récupérer les reponquition actifs

                $tests = Test::where('active', 1)->get();
                // Retourner la vue avec les reponquition
                return view('reponquition.index', compact('tests'));
            } else {
                return redirect('/')->with('error', 'Access denied!');
            }
        }

        return redirect('/login')->with('error', 'Please log in first!');
    }
    public function showReponse($id_test)
    {
        // Récupérer le test avec ses questions et réponses
//        $Questions = Question::where('id_question', $id_test)->get();

        // Récupérer les questions du test avec leurs relations
        $questions = Question::with([
            'options',              // Charger les options liées
            'subQuestions',       // Charger la question courte liée
            'multiple',
            'OptionChoixObligatoire',
            // Charger le multiple lié
        ])->where('id_test', $id_test)->get();

        dd($questions)  ;
        // Vérifier si des questions ont été trouvées


        // Retourner la vue avec les données


//        dd($Questions->toArray()[0]["type_question"]);


        // Vérifier si le test existe
//        if (!$Question) {
//            return redirect()->route('tests.index')->with('error', 'Test non trouvé.');
//        }

        // Retourner la vue avec les données
        return view('reponquition.questionseponses', compact('Questions'));
    }

}

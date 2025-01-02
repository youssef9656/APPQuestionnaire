<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Reponse;
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
        $Questions = Question::with([
            'options',              // Charger les options liées
            'subQuestions',       // Charger la question courte liée
            'multiple',
            'OptionChoixObligatoire',
            // Charger le multiple lié
        ])->where('id_test', $id_test)->get();

//        dd($Questions)  ;



//        dd($Questions->toArray()[0]["type_question"]);


        // Vérifier si le test existe
//        if (!$Question) {
//            return redirect()->route('tests.index')->with('error', 'Test non trouvé.');
//        }

        return view('reponquition.questionseponses', compact('Questions', 'id_test'));
    }


    public function store(Request $request, $id_test)
    {
        // Valider les données entrantes
        $validated = $request->validate([
            'reponses' => 'required|array',
        ]);

        // Récupérer l'utilisateur actuel (assurez-vous que l'utilisateur est authentifié)
        session_start();

        $id_user   =   $_SESSION['userA']['id_user'] ;
        // Boucle pour traiter chaque question et ses réponses
        foreach ($validated['reponses'] as $question_id => $reponseData) {

            foreach ($reponseData as $key => $value) {
                // Vérifie si c'est une sous-question (clé commence par "subQuestions")
                if (strpos($key, 'subQuestions') === 0 && is_array($value)) {
                    foreach ($value as $subQuestionId => $subAnswer) {
                        $reponse = new Reponse();
                        $reponse->id_user = $id_user;
                        $reponse->id_test = $id_test;
                        $reponse->id_question = $question_id; // ID de la question parent
                        $reponse->type_reponse = 'subQuestion';
                        $reponse->reponse = $subAnswer; // Réponse à la sous-question
                        $reponse->save();
                    }
                }
            }



            // 1. Si la question est une sous-question (type text)
            if (isset($reponseData['subQuestion131'])) {
                foreach ($reponseData as $key => $value) {
                    // Vérifie si c'est une sous-question (clé commence par "subQuestions")
                    if (strpos($key, 'subQuestions') === 0 && is_array($value)) {
                        foreach ($value as $subQuestionId => $subAnswer) {
                            $reponse = new Reponse();
                            $reponse->id_user = $id_user;
                            $reponse->id_test = $id_test;
                            $reponse->id_question = $question_id; // ID de la question parent
                            $reponse->type_reponse = 'subQuestion';
                            $reponse->reponse = $subAnswer; // Réponse à la sous-question
                            $reponse->save();
                        }
                    }
                }

            }


            // 2. Si la question contient des réponses multiples (checkboxes ou autres)
            elseif (isset($reponseData['multiple-1']) || isset($reponseData['multiple-2'])) {
                // Parcourir les clés pour traiter les réponses multiples
                foreach ($reponseData as $key => $value) {
                    if (strpos($key, 'multiple-') === 0) { // Vérifie que la clé commence par "multiple-"
                        // Enregistrer chaque réponse multiple
                        $reponse = new Reponse();
                        $reponse->id_user = $id_user;
                        $reponse->id_test = $id_test;
                        $reponse->id_question = $question_id;
                        $reponse->type_reponse = 'multiple';
                        $reponse->reponse = $value; // Valeur de la réponse multiple
                        $reponse->save(); // Sauvegarder la réponse
                    }
                }
            }

            // 3. Si la question est une option (radio button) avec une réponse obligatoire
            elseif (isset($reponseData['id_option_reponse'])) {
                $reponse = new Reponse();
                $reponse->id_user = $id_user;
                $reponse->id_test = $id_test;
                $reponse->id_question = $question_id;
                $reponse->type_reponse = 'option';
                $reponse->id_option_reponse = $reponseData['id_option_reponse']; // ID de l'option sélectionnée

                // Vérifier si une réponse obligatoire est fournie
                if (isset($reponseData['mandatoryOption-' . $reponseData['id_option_reponse']])) {
                    // Enregistrer la réponse obligatoire si elle existe
                    $reponse->reponse = $reponseData['mandatoryOption-' . $reponseData['id_option_reponse']];
                } else {
                    $reponse->reponse = null; // Sinon, aucune réponse dans le champ
                }
                $reponse->save();
            }
        }

        // Retourner une réponse JSON avec le succès
        return response()->json([
            'message' => 'Réponses enregistrées avec succès !',
            'data' => $validated['reponses']
        ]);
    }

//    public function store(Request $request, $id_test)
//    {
//        // Valider les données
//        $validated = $request->validate([
//            '_token' => 'required',
//            'reponses' => 'required|array',
//        ]);
//
//        // Récupérer l'utilisateur connecté
//        session_start();
//
//        $id_user   =   $_SESSION['userA']['id_user'] ;
//        // Traitement des réponses
//        foreach ($validated['reponses'] as $question_id => $reponseData) {
//
//            // Vérifier le type de réponse
//            if (isset($reponseData['subQuestion-' . $question_id])) {
//                // Sous-question
//                $reponse = new Reponse();
//                $reponse->id_user = $id_user;
//                $reponse->id_test = $id_test;
//                $reponse->id_question = $question_id;
//                $reponse->type_reponse = 'text';
//                $reponse->reponse = $reponseData['subQuestion-' . $question_id]; // Réponse textuelle
//                $reponse->save();
//            }
//            elseif (isset($reponseData['multiple-' . $question_id])) {
//                // Réponse multiple
//                foreach ($reponseData as $key => $value) {
//                    if (strpos($key, 'multiple-') === 0) {
//                        $reponse = new Reponse();
//                        $reponse->id_user = $id_user;
//                        $reponse->id_test = $id_test;
//                        $reponse->id_question = $question_id;
//                        $reponse->type_reponse = 'multiple';
//                        $reponse->reponse = $value; // Valeur multiple
//                        $reponse->save();
//                    }
//                }
//            }
//            elseif (isset($reponseData['id_option_reponse'])) {
//                // Créer une nouvelle réponse pour une option choisie
//                $reponse = new Reponse();
//                $reponse->id_user = $id_user;
//                $reponse->id_test = $id_test;
//                $reponse->id_question = $question_id;
//                $reponse->type_reponse = 'option';
//                $reponse->id_option_reponse = $reponseData['id_option_reponse']; // Option sélectionnée
//
//                // Vérifier si une réponse obligatoire est fournie
//                if (isset($reponseData['mandatoryOption-' . $reponseData['id_option_reponse']])) {
//                    // Si la réponse obligatoire existe, l'enregistrer
//                    $reponse->reponse = $reponseData['mandatoryOption-' . $reponseData['id_option_reponse']]; // Réponse obligatoire
//                } else {
//                    // Sinon, ne rien enregistrer dans le champ `reponse` pour cette option
//                    $reponse->reponse = null;
//                }
//                $reponse->save();
//            }
//        }
//
//        // Retourner une réponse JSON avec le succès
//        return response()->json([
//            'message' => 'Réponses enregistrées avec succès !',
//            'data' => $validated['reponses']
//        ]);
//    }



//    public function store(Request $request, $id_test)
//    {
//        session_start();
//
//        $id_user   =   $_SESSION['userA']['id_user'] ;
//dd($request->all());
//        // Valider les données
//        $validated = $request->validate([
//            'reponses' => 'required|array',
//            'reponses.*.id_question' => 'required|exists:questions,id_question',
//            'reponses.*.reponse' => 'nullable|string', // Réponse textuelle
//            'reponses.*.id_option_reponse' => 'nullable|exists:options,id_option', // Réponse par option
//            'reponses.*.type_reponse' => 'required|in:text,option',
//        ]);
//
//        // Récupérer l'utilisateur connecté
//dd($validated);
//        // Enregistrer les réponses
//        foreach ($validated['reponses'] as $reponseData) {
//            $reponse = new Reponse();
//            $reponse->id_user = $id_user;
//            $reponse->id_test = $id_test;
//            $reponse->id_question = $reponseData['id_question'];
//            $reponse->type_reponse = $reponseData['type_reponse'];
//
//            if ($reponseData['type_reponse'] == 'text') {
//                $reponse->reponse = $reponseData['reponse'];
//            } elseif ($reponseData['type_reponse'] == 'option') {
//                $reponse->id_option_reponse = $reponseData['id_option_reponse'];
//            }
//
//            dd($reponse);
//            $reponse->save();
//        }
//
//        // Retourner une réponse JSON avec le succès
//        return response()->json([
//            'message' => 'Réponses enregistrées avec succès !',
//            'data' => $validated['reponses']
//        ]);
//    }
}

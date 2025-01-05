<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Reponse;
use Illuminate\Http\Request;
use App\Models\Test;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

class ReponseController extends Controller
{
    public function __construct()
    {
        // Démarre la session dès l'instanciation de la classe
        session_start();
    }

    public function index()
    {

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

        $tests = Test::where('active', 1)->get();


//        dd($Questions)  ;


//        dd($Questions->toArray()[0]["type_question"]);





        return view('reponquition.questionseponses', compact('Questions','tests', 'id_test'));
    }


    public function store(Request $request, $id_test)
    {
        // Valider les données entrantes
        $validated = $request->validate([
            'reponses' => 'required|array',
        ]);

        // Récupérer l'utilisateur actuel (assurez-vous que l'utilisateur est authentifié)
        $id_user = $_SESSION['userA']['id_user'];

        // Encapsuler les opérations dans une transaction pour garantir l'intégrité des données
        DB::beginTransaction();

        try {
            // Parcourir les réponses fournies
            foreach ($validated['reponses'] as $question_id => $reponseData) {
                foreach ($reponseData as $key => $value) {
                    // Traiter les sous-questions
                    if (strpos($key, 'subQuestions') === 0 && is_array($value)) {
                        foreach ($value as $subQuestionId => $subAnswer) {
                            $this->enregistrerReponse($id_user, $id_test, $question_id, 'subQuestion', $subAnswer);
                        }
                    }
                    // Traiter les réponses multiples
                    elseif (strpos($key, 'multiple-') === 0) {
                        $this->enregistrerReponse($id_user, $id_test, $question_id, 'multiple', $value);
                    }
                    // Traiter les réponses obligatoires (mandatory)
                    elseif (strpos($key, 'mandatory-') === 0) {
                        $optionId = explode('-', $key)[1];
                        $this->enregistrerReponse($id_user, $id_test, $question_id, 'mandatory', $value, null,$optionId);
                    }
                }

                // Traiter les réponses optionnelles (radio buttons)
                if (isset($reponseData['id_option_reponse'])) {
                    $reponseOption = $reponseData['id_option_reponse'];
                    $mandatoryAnswer = $reponseData['mandatoryOption-' . $reponseOption] ?? null;

                    $this->enregistrerReponse($id_user, $id_test, $question_id, 'option', $mandatoryAnswer, $reponseOption);
                }
            }

            // Valider la transaction
            DB::commit();

            // Retourner une réponse JSON avec le succès
            return redirect()->route('test.completion', ['id_test' => $id_test, 'score' => "good"]);

//            return response()->json([
//                'message' => 'Réponses enregistrées avec succès !',
//                'data' => $validated['reponses']
//            ]);
        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            DB::rollBack();

            // Retourner une erreur
            return response()->json([
                'message' => 'Erreur lors de l’enregistrement des réponses.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Fonction pour enregistrer une réponse dans la base de données.
     *
     * @param int $id_user
     * @param int $id_test
     * @param int $id_question
     * @param string $type_reponse
     * @param string|null $reponse
     * @param int|null $id_option_reponse
     * @return void
     */
    protected function enregistrerReponse($id_user, $id_test, $id_question, $type_reponse, $reponse = null, $id_option_reponse = null ,$id_option_Ob=null)
    {
        $reponseModel = new Reponse();
        $reponseModel->id_user = $id_user;
        $reponseModel->id_test = $id_test;
        $reponseModel->id_question = $id_question;
        $reponseModel->type_reponse = $type_reponse;
        $reponseModel->reponse = $reponse;
        $reponseModel->id_option_reponse = $id_option_reponse;
        $reponseModel->id_option_Ob = $id_option_Ob;
        $reponseModel->save();
    }

//    public function store(Request $request, $id_test)
//    {
//        // Valider les données entrantes
//        $validated = $request->validate([
//            'reponses' => 'required|array',
//        ]);
//
//        // Récupérer l'utilisateur actuel (assurez-vous que l'utilisateur est authentifié)
//        session_start();
//
//        $id_user   =   $_SESSION['userA']['id_user'] ;
//        // Boucle pour traiter chaque question et ses réponses
//        foreach ($validated['reponses'] as $question_id => $reponseData) {
//
//            foreach ($reponseData as $key => $value) {
//                // Vérifie si c'est une sous-question (clé commence par "subQuestions")
//                if (strpos($key, 'subQuestions') === 0 && is_array($value)) {
//                    foreach ($value as $subQuestionId => $subAnswer) {
//                        $reponse = new Reponse();
//                        $reponse->id_user = $id_user;
//                        $reponse->id_test = $id_test;
//                        $reponse->id_question = $question_id; // ID de la question parent
//                        $reponse->type_reponse = 'subQuestion';
//                        $reponse->reponse = $subAnswer; // Réponse à la sous-question
//                        $reponse->save();
//                    }
//                }
//            }
//
//
//
//            // 1. Si la question est une sous-question (type text)
//            if (isset($reponseData['subQuestion131'])) {
//                foreach ($reponseData as $key => $value) {
//                    // Vérifie si c'est une sous-question (clé commence par "subQuestions")
//                    if (strpos($key, 'subQuestions') === 0 && is_array($value)) {
//                        foreach ($value as $subQuestionId => $subAnswer) {
//                            $reponse = new Reponse();
//                            $reponse->id_user = $id_user;
//                            $reponse->id_test = $id_test;
//                            $reponse->id_question = $question_id; // ID de la question parent
//                            $reponse->type_reponse = 'subQuestion';
//                            $reponse->reponse = $subAnswer; // Réponse à la sous-question
//                            $reponse->save();
//                        }
//                    }
//                }
//
//            }
//
//
//            // 2. Si la question contient des réponses multiples (checkboxes ou autres)
//            elseif (isset($reponseData['multiple-1']) || isset($reponseData['multiple-2'])) {
//                // Parcourir les clés pour traiter les réponses multiples
//                foreach ($reponseData as $key => $value) {
//                    if (strpos($key, 'multiple-') === 0) { // Vérifie que la clé commence par "multiple-"
//                        // Enregistrer chaque réponse multiple
//                        $reponse = new Reponse();
//                        $reponse->id_user = $id_user;
//                        $reponse->id_test = $id_test;
//                        $reponse->id_question = $question_id;
//                        $reponse->type_reponse = 'multiple';
//                        $reponse->reponse = $value; // Valeur de la réponse multiple
//                        $reponse->save(); // Sauvegarder la réponse
//                    }
//                }
//            }
//
//            // 3. Si la question est une option (radio button) avec une réponse obligatoire
//            elseif (isset($reponseData['id_option_reponse'])) {
//                $reponse = new Reponse();
//                $reponse->id_user = $id_user;
//                $reponse->id_test = $id_test;
//                $reponse->id_question = $question_id;
//                $reponse->type_reponse = 'option';
//                $reponse->id_option_reponse = $reponseData['id_option_reponse']; // ID de l'option sélectionnée
//
//                // Vérifier si une réponse obligatoire est fournie
//                if (isset($reponseData['mandatoryOption-' . $reponseData['id_option_reponse']])) {
//                    // Enregistrer la réponse obligatoire si elle existe
//                    $reponse->reponse = $reponseData['mandatoryOption-' . $reponseData['id_option_reponse']];
//                } else {
//                    $reponse->reponse = null; // Sinon, aucune réponse dans le champ
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

    public function testCompletion($id_test, $score = null)
    {
        return view('reponquition.completion', ['score' => $score]);
    }


}

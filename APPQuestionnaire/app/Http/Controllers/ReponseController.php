<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Reponse;
use App\Models\User;
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


        $user   =   $_SESSION['userA'] ?? null;

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
            'options',                  // Charger les options liées
            'subQuestions',             // Charger la question courte liée
            'multiple',                 // Charger le multiple lié
            'OptionChoixObligatoire'    // Charger l'option choix obligatoire
        ])
            ->where('id_test', $id_test)    // Filtrer par id_test
            ->orderBy('ordre_question')     // Ajouter un tri sur ordre_question
            ->get();


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


    public function testCompletion($id_test, $score = null)
    {
        return view('reponquition.completion', ['score' => $score]);
    }
    public function indexRepo()
    {
        $users = User::all();
        $teste = Test::all();
        $testsre = Reponse::select('id_user', 'id_test')->distinct()->get();
        return view('usersRepo', compact('users', 'testsre','teste'));
    }

}

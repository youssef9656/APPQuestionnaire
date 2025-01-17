<?php

namespace App\Http\Controllers;

use App\Models\Multiple;
use App\Models\Question;
use App\Models\Test;
use App\Models\QuestionCourte;
use App\Models\OptionChoixObligatoire;
use App\Models\Option;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    // Afficher les questions pour un test spécifique
    public function index(Test $test)
    {
//        $questions = $test->questions()->with('subQuestions')->get();
        $questions = $test->questions()
            ->with(['subQuestions', 'options.associatedQuestion'])
            ->orderBy('ordre_question', 'asc')
            ->get();

        return view('questions.index', compact('test', 'questions'));
    }


    // Formulaire pour créer une question
    public function create(Test $test)
    {
        return view('questions.create', compact('test'));
    }

    // Enregistrer une nouvelle question
    public function store(Request $request, Test $test)
    {
        // Validation des données spécifiques aux questions
        $request->validate([
            'text_question' => 'required|string|max:255',
            'type_question' => 'required',
            'sub_questions' => 'nullable|array',
            'sub_questions.*.text' => 'nullable|string|max:255',
            'sub_questions.*.type' => 'required',
            'choices' => 'nullable|array',
            'choices.*.label' => 'required_if:type_question,options_choix|max:255',

        ]);

        $ordre_question = $test->questions()->max('ordre_question') + 1;

        $tquestion = $request->input('option_type');

        if ($tquestion === 'multiple') {

            $type_question = 'multiple';

        }elseif ($tquestion === 'unique') {

            $type_question = 'options_choix';

        }elseif ($request->input('type_question') === 'short_question') {

            $type_question = 'short_question';

        }


        // Création de la question principale
        $question = $test->questions()->create([
            'id_test' => $test->id_test,
            'text_question' => $request->input('text_question'),
            'type_question' => $type_question,
            'ordre_question' => $ordre_question,
        ]);


        // Si la question est de type 'short_question', ajouter les sous-questions
        if ($request->input('type_question') === 'short_question') {
            $ordre_sub_question = 1; // Initialisation de l'ordre des sous-questions
            foreach ($request->input('sub_questions') as $subQuestionData) {
                QuestionCourte::create([
                    'id_question' => $question->id_question,
                    'text_question' => $subQuestionData['text'],
                    'type_question' => $subQuestionData['type'],
                    'ordre_question' => $ordre_sub_question++,
                ]);
            }
        }

        // Si la question est de type 'multiple_choice' ou 'options_choix', ajouter les options
        if (in_array($request->input('type_question'), ['multiple_choice', 'options_choix'])) {
            if ($request->input('option_type') === 'unique') {
                $ordre_option = 1; // Initialiser l'ordre des options pour cette question
                foreach ($request->input('choices') as $choice) {
                    $option = Option::create([
                        'id_question' => $question->id_question,
                        'text_option' => $choice['label'],
                        'text_associé' => $choice['question'] ?? null,
                        'ordre_question' => $ordre_option++,
                    ]);

                    // Vérifier si l'option est obligatoire et ajouter à OptionChoixObligatoire

                }
            }elseif ($request->input('option_type') === 'multiple'){
//                dd($request);
                $ordre_option = 1; // Initialiser l'ordre des options pour cette question
                foreach ($request->input('choicesMultiple') as $choice) {
                    $option = Multiple::create([
                        'id_question' => $question->id_question,
                        'text_question'=>  $choice['label'],
                        'nombre_de'=> $choice['de'],
                        'nombre_a'=> $choice['a'],
                        'ordre_multip'=> $ordre_option++
                    ]);

                    // Vérifier si l'option est obligatoire et ajouter à OptionChoixObligatoire

                }
            }

        }

        if ($request->has('mandatory')) {
            foreach ($request->input('mandatory') as $mandatoryField) {
                OptionChoixObligatoire::create([
                    'id_question' => $question->id_question,
                    'question_text' => $mandatoryField['text'],
                    'question_type' => 'text', // Si applicable, sinon ajuster selon la logique
                ]);
            }
        }

        return redirect()->route('questions.index', $test->id_test)
            ->with('success', 'Question ajoutée avec succès.');
    }


    // Formulaire pour modifier une question
    public function edit(Test $test, Question $question)
    {
        return view('questions.edit', compact('test', 'question'));
    }

    // Mettre à jour une question
    public function update(Request $request, Test $test, Question $question)
    {
        // Validation des données
        $request->validate([
            'text_question' => 'required|string|max:255',
            'type_question' => 'required',
            'sub_questions' => 'nullable|array',
            'sub_questions.*.text' => 'nullable|string|max:255',
            'sub_questions.*.type' => 'required',
            'choices' => 'nullable|array',
        ]);

        // Supprimer les données spécifiques au type précédent
        if ($question->type_question === 'short_question') {
            $question->subQuestions()->delete();
        } elseif ($question->type_question === 'options_choix') {
            $question->options()->delete();
        } elseif ($question->type_question === 'multiple') {
            $question->multiple()->delete();
        }

        // Mise à jour de la question principale

        if ($request->input('option_type') === 'multiple') {

            $type_question = 'multiple';
        }
        if ($request->input('option_type') === 'unique') {

            $type_question = 'options_choix';
        }
        if ($request->input('type_question') === 'short_question') {

            $type_question = 'short_question';
        }



        $question->update([
            'text_question' => $request->input('text_question'),
            'type_question' => $type_question,
        ]);

        // Ajout des sous-questions si la question est de type 'short_question'
        if ($request->input('type_question') === 'short_question') {
            $ordre_sub_question = 1;
            foreach ($request->input('sub_questions') as $subQuestionData) {
                QuestionCourte::create([
                    'id_question' => $question->id_question,
                    'text_question' => $subQuestionData['text'],
                    'type_question' => $subQuestionData['type'],
                    'ordre_question' => $ordre_sub_question++,
                ]);
            }
        }

        // Ajout des options si la question est de type 'options_choix'
        if ($request->input('type_question') === 'options_choix') {
            $ordre_option = 1;
            if ($request->input('option_type') === 'unique') {
                foreach ($request->input('choices') as $choice) {
                    Option::create([
                        'id_question' => $question->id_question,
                        'text_option' => $choice['label'],
                        'text_associé' => $choice['question'] ?? null,
                        'ordre_question' => $ordre_option++,
                    ]);
                }
            }

            if ($request->input('option_type') === 'multiple') {
                $ordre_option = 1;
                foreach ($request->input('choicesMultiple') as $choice) {
                    Multiple::create([
                        'id_question' => $question->id_question,
                        'text_question' => $choice['label'],
                        'nombre_de' => $choice['de'],
                        'nombre_a' => $choice['a'],
                        'ordre_multip' => $ordre_option++,
                    ]);
                }
        }
        }

        // Mise à jour des champs obligatoires
        if ($request->has('mandatory')) {
            foreach ($request->input('mandatory') as $mandatoryField) {
                OptionChoixObligatoire::updateOrCreate(
                    ['id_question' => $question->id_question, 'question_text' => $mandatoryField['text']],
                    ['question_type' => 'text']
                );
            }
        }

        return redirect()->route('questions.index', $test->id_test)
            ->with('success', 'Question mise à jour avec succès.');
    }

    // Supprimer une question
    public function destroy(Test $test, Question $question)
    {
        // Suppression des sous-questions associées si elles existent
        if ($question->type_question == 'short_question') {
            $question->subQuestions()->delete();
        }

        // Suppression de la question principale
        $question->delete();

        return redirect()->route('questions.index', $test->id_test)
            ->with('success', 'Question supprimée avec succès.');
    }

    public function updateOrder(Request $request, $testId)
    {
        try {
            $order = $request->input('order');

            // Log the order data for debugging
            \Log::info('Order data:', ['order' => $order]);

            // Collect all question IDs and their new order values
            $questionOrders = [];
            foreach ($order as $item) {
                $questionOrders[$item['id']] = $item['order'];
            }

            // Ensure unique order values by sorting and reassigning
            $uniqueOrders = array_values(array_unique($questionOrders));
            sort($uniqueOrders);

            // Update questions with unique order values
            foreach ($order as $index => $item) {
                $question = Question::find($item['id']);
                if ($question) {
                    $question->ordre_question = $uniqueOrders[$index];
                    $question->save();
                }
            }

            return response()->json(['status' => 'success', 'message' => 'Ordre des questions mis à jour avec succès.']);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error updating order:', ['error' => $e->getMessage()]);

            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }


}

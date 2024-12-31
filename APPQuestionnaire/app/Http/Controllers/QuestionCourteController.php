<?php
namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuestionCourte;
use App\Models\Test;
use Illuminate\Http\Request;

class QuestionCourteController extends Controller
{

    // Afficher le formulaire de création d'une question courte avec sous-questions
    public function create(Test $test, Question $question)
    {
        return view('questions.courte.create', compact('test', 'question'));
    }

    // Enregistrer une question courte avec plusieurs sous-questions
    public function store(Request $request, Test $test, Question $question)
    {
        $validatedData = $request->validate([
            'text_question' => 'required|string|max:255',
            'type_question' => 'required|in:numeric,text',
            'sub_questions.*.text' => 'required|string|max:255', // Valider chaque sous-question
            'sub_questions.*.type' => 'required|in:numeric,text', // Valider le type de chaque sous-question
        ]);

        // Enregistrer la question principale si c'est une question courte
        if ($question->type_question === 'short') {
            // Ajouter l'ordre des sous-questions
            foreach ($request->sub_questions as $index => $subQuestionData) {
                $ordreQuestion = $index + 1; // L'ordre commence à 1 pour chaque sous-question

                QuestionCourte::create([
                    'id_question' => $question->id_question,
                    'text_question' => $subQuestionData['text'],
                    'type_question' => $subQuestionData['type'],
                    'ordre_question' => $ordreQuestion,
                ]);
            }
        }

        return redirect()->route('questions.index', $test->id_test)
            ->with('success', 'Sous-questions ajoutées avec succès.');
    }

    // Supprimer une question courte
    public function destroy(QuestionCourte $questionCourte)
    {
        $questionCourte->delete();
        return redirect()->back()->with('success', 'Sous-question supprimée avec succès.');
    }
}

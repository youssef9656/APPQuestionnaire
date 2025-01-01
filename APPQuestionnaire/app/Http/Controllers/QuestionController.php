<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Test;
use App\Models\QuestionCourte;
use App\Models\Option;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    // Afficher les questions pour un test spécifique
    public function index(Test $test)
    {
        $questions = $test->questions()->with('subQuestions')->get();

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
            'type_question' => 'required|in:text,multiple_choice,true_false,short_question', // ajout des types valides
            'sub_questions' => 'nullable|array', // validation des sous-questions si présentes
            'sub_questions.*.text' => 'nullable|string|max:255',
            'sub_questions.*.type' => 'required|in:text,number', // Validation du type des sous-questions
            'choices' => 'nullable|array', // Validation des options si présentes
            'choices.*.label' => 'required_if:type_question,multiple_choice,true_false|string|max:255', // Validation des labels des choix
        ]);

        $ordre_question = $test->questions()->max('ordre_question') + 1;

        // Création de la question principale
        $question = $test->questions()->create([
            'id_test' => $test->id_test,
            'text_question' => $request->input('text_question'),
            'type_question' => $request->input('type_question'),
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

        // Si la question est de type 'multiple_choice' ou 'true_false', ajouter les options
        if (in_array($request->input('type_question'), ['multiple_choice', 'true_false'])) {
            $ordre_option = 1; // Initialiser l'ordre des options pour cette question
            foreach ($request->input('choices') as $choice) {
                Option::create([
                    'id_question' => $question->id_question,
                    'text_option' => $choice['label'],
                    'text_associé' => $choice['question'],
                    'ordre_question' => $ordre_option++,
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
            'text_question' => 'required|string|max:255', // Correctif du nom de la colonne
            'type_question' => 'required|in:text,multiple_choice,true_false,short_question', // Types autorisés
            'sub_questions' => 'nullable|array',
            'sub_questions.*.text' => 'required|string|max:255',
            'sub_questions.*.type' => 'required|in:text,number',
        ]);

        // Mise à jour de la question principale
        $question->update([
            'text_question' => $request->input('text_question'),
            'type_question' => $request->input('type_question'),
        ]);

        // Si la question est de type 'short_question', mettre à jour les sous-questions
        if ($request->input('type_question') == 'short_question') {
            foreach ($request->input('sub_questions') as $subQuestionData) {
                QuestionCourte::updateOrCreate(
                    ['id_question' => $question->id_question, 'text_question' => $subQuestionData['text']],
                    ['type_question' => $subQuestionData['type']]
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
}

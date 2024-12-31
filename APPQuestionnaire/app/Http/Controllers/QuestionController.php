<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Test;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    // Afficher les questions pour un test spécifique
    public function index(Test $test)
    {
        $questions = $test->questions; // Relation avec les questions
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
        // Validation des données
        $request->validate([
            'question_text' => 'required|string|max:255', // Nom correct de la colonne
            'type' => 'required|in:text,multiple_choice,true_false', // Types autorisés
        ]);

        // Création de la question
        $test->questions()->create([
            'question_text' => $request->input('question_text'),
            'type' => $request->input('type'),
        ]);

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
            'question_text' => 'required|string|max:255', // Nom correct de la colonne
            'type' => 'required|in:text,multiple_choice,true_false', // Types autorisés
        ]);

        // Mise à jour de la question
        $question->update([
            'question_text' => $request->input('question_text'),
            'type' => $request->input('type'),
        ]);

        return redirect()->route('questions.index', $test->id_test)
            ->with('success', 'Question mise à jour avec succès.');
    }

    // Supprimer une question
    public function destroy(Test $test, Question $question)
    {
        // Suppression de la question
        $question->delete();

        return redirect()->route('questions.index', $test->id_test)
            ->with('success', 'Question supprimée avec succès.');
    }
}

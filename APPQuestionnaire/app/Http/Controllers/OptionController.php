<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Question;
use App\Models\Option;

class OptionController extends Controller
{
    public function store(Request $request, $testId)
    {
        // Validation des données principales
        $validated = $request->validate([
            'text_question' => 'required|string|max:255',
            'type_question' => 'required|string|in:text,true_false,short_question',
        ]);

        // Création de la question principale
        $question = Question::create([
            'text_question' => $validated['text_question'],
            'type_question' => $validated['type_question'],
            'test_id' => $testId,
        ]);

        // Si la question contient des options (true/false ou choix multiples)
        if ($validated['type_question'] === 'true_false' && $request->has('choices')) {
            foreach ($request->input('choices') as $choice) {
                Option::create([
                    'id_question' => $question->id_question,
                    'text_option' => $choice['label'],
                    'text_associé' => $choice['question'] ?? null, // Facultatif
                    'question_type' => 'true_false',
                    'ordre_question' => $choice['ordre'] ?? null,
                ]);
            }
        }

        // Si la question contient des champs obligatoires
        if ($validated['type_question'] === 'true_false' && $request->has('mandatory')) {
            foreach ($request->input('mandatory') as $mandatoryField) {
                Option::create([
                    'id_question' => $question->id_question,
                    'text_option' => $mandatoryField['text'],
                    'text_associé' => null,
                    'question_type' => 'mandatory_field',
                ]);
            }
        }

        // Si la question contient des sous-questions
        if ($validated['type_question'] === 'short_question' && $request->has('short_questions')) {
            foreach ($request->input('short_questions') as $shortQuestion) {
                Question::create([
                    'text_question' => $shortQuestion['text'],
                    'type_question' => $shortQuestion['type'],
                    'test_id' => $testId,
                    'parent_id' => $question->id_question, // Relation avec la question principale
                ]);
            }
        }

        return redirect()->route('questions.index', $testId)
            ->with('success', 'Question ajoutée avec succès !');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Http\Request;

class TestController extends Controller
{
    // Afficher la liste des reponquition
    public function index()
    {
        $tests = Test::all();
        return view('admin.index', compact('tests'));
    }

    // Afficher le formulaire pour créer un test
    public function create()
    {
        return view('admin.create');
    }

    // Enregistrer un nouveau test
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nom_test' => 'required|string|max:100',
            'duree_test' => 'required|integer|min:1',
            'active' => 'boolean',
        ]);

        // Si la case "active" n'est pas cochée, elle ne sera pas présente dans $request. Fixons cela.
        $validatedData['active'] = $request->has('active') ? 1 : 0;

        Test::create($validatedData);

        return redirect()->route('reponquition.index')->with('success', 'Test ajouté avec succès.');
    }

    // Afficher un test spécifique (optionnel si pas nécessaire)
    public function show(Test $test)
    {
        return view('admin.show', compact('test'));
    }

    // Afficher le formulaire pour modifier un test
    public function edit(Test $test)
    {
        return view('admin.edit', compact('test'));
    }

    // Mettre à jour un test existant
    public function update(Request $request, Test $test)
    {
        $request->validate([
            'nom_test' => 'required|string|max:100',
            'duree_test' => 'required|integer|min:1',
            'active' => 'boolean',
        ]);

        $data = $request->all();
        $data['active'] = $request->has('active') ? 1 : 0;
        $test->update($data);

        return redirect()->route('reponquition.index')->with('success', 'Test mis à jour avec succès.');
    }

    // Supprimer un test
    public function destroy(Test $test)
    {
        $test->delete();
        return redirect()->route('reponquition.index')->with('success', 'Test supprimé avec succès.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\DecisionTree;
use App\Models\Instruction;
use Illuminate\Http\Request;

class DecisionTreeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $nodes = DecisionTree::whereNull('parent_id')->with(['children' => function($query) {
            $query->orderBy('position');
        }])->orderBy('position')->get();
        return view('decision_tree.index', compact('nodes'));
    }
 
    public function user()
    {
        $rootMenus = DecisionTree::whereNull('parent_id')->get();
        $menus = DecisionTree::with('children')->get()->keyBy('id');

        return view('decision_tree.user', compact('rootMenus', 'menus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parentNodes = DecisionTree::all();
        $instructions = Instruction::all();
        return view('decision_tree.create', compact('parentNodes','instructions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'action_type' => 'required|string',
            'action_value' => 'nullable|string',
            'parent_id' => 'nullable|exists:decision_tree,id',
            'instruction_id' => 'nullable|exists:instructions,id',
            'position' => 'required|integer',
        ]);

        DecisionTree::create($request->all());
        return redirect()->route('decision_tree.index')->with('success', 'Nó criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $decisionTree = DecisionTree::findOrFail($id);
        $parentNodes = DecisionTree::whereNull('parent_id')->get();
        $instructions = Instruction::all();

        return view('decision_tree.edit', compact('decisionTree', 'parentNodes', 'instructions'));
    }

    public function update(Request $request, $id)
    {
        $decisionTree = DecisionTree::findOrFail($id);
        $decisionTree->update($request->all());

        return redirect()->route('decision_tree.index')->with('success', 'Nó atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DecisionTree $decisionTree)
    {
        $decisionTree->delete();
        return redirect()->route('decision_tree.index')->with('success', 'Nó excluído com sucesso.');
    }
}

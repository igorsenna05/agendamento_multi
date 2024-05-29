<?php

namespace App\Http\Controllers;

use App\Models\Instruction;
use Illuminate\Http\Request;

class InstructionController extends Controller
{
    // Mostrar a lista de instruções
    public function index()
    {
        $instructions = Instruction::all();
        return view('instructions.index', compact('instructions'));
    }

    // Mostrar o formulário de criação de instrução
    public function create()
    {
        return view('instructions.create');
    }

    // Armazenar uma nova instrução
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Instruction::create($request->all());
        return redirect()->route('instructions.index')->with('success', 'Instrução criada com sucesso!');
    }

    // Mostrar o formulário de edição de instrução
    public function edit(Instruction $instruction)
    {
        return view('instructions.edit', compact('instruction'));
    }

    // Atualizar uma instrução existente
    public function update(Request $request, Instruction $instruction)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $instruction->update($request->all());
        return redirect()->route('instructions.index')->with('success', 'Instrução atualizada com sucesso!');
    }

    // Excluir uma instrução
    public function destroy(Instruction $instruction)
    {
        $instruction->delete();
        return redirect()->route('instructions.index')->with('success', 'Instrução excluída com sucesso!');
    }
}

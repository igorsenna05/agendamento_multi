<?php

namespace App\Http\Controllers;

use App\Models\Role;

use Illuminate\Http\Request;
use Log;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return view ('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $role = Role::create([
            'name' => $request->name
        ]);

        return response()->json($role);
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $role = Role::findOrFail($id);
            $role->update($validatedData);

            return response()->json($role);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar perfil: '.$e->getMessage());
            return response()->json(['error' => 'Erro ao atualizar perfil'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json(['success' => 'Perfil excluído com sucesso.']);
    }
}

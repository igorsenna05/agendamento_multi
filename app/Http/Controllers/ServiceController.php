<?php

namespace App\Http\Controllers;

use App\Models\Service;

use Illuminate\Http\Request;
use Log;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::all();
        return view ('services.index', compact('services'));
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
            'service_code' => 'required|string|max:255',
        ]);

        $service = Service::create([
                            'name' => $request->name,
                            'service_code' => $request->service_code]);

        return response()->json($service);
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
                'service_code' => 'required|string|max:255',
            ]);
    
            $service = Service::findOrFail($id);
            $service->update($validatedData);
    
            return response()->json($service);    
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar serviço: '.$e->getMessage());
            return response()->json(['error' => 'Erro ao atualizar serviço'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return response()->json(['success' => 'Serviço excluído com sucesso.']);
    }
}

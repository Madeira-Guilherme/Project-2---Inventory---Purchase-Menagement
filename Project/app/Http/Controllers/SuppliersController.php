<?php

namespace App\Http\Controllers;

use App\Models\Suppliers;
use Illuminate\Http\Request;

class SuppliersController extends Controller
{
    public function index()
    {
        $supplier = Suppliers::all();

        return response()->json($supplier);
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
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'email'        => 'nullable|email|max:255',
            'phone'        => 'nullable|string|max:50',
            'address'      => 'nullable|string|max:255',
            'is_active'    => 'boolean',
        ]);

        $supplier = Suppliers::create($validated);

        return response()->json([
            'message' => 'Supplier created successfully',
            'data' => $supplier
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $supplier = Suppliers::findOrFail($id);

        return response()->json($supplier);
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
    $supplier = Suppliers::findOrFail($id);

    $validated = $request->validate([
        'company_name' => 'sometimes|string|max:255',
        'contact_name' => 'nullable|string|max:255',
        'email'        => 'nullable|email|max:255',
        'phone'        => 'nullable|string|max:50',
        'address'      => 'nullable|string|max:255',
        'is_active'    => 'boolean',
    ]);

    $supplier->update($validated);

    return response()->json([
        'message' => 'Supplier updated successfully',
        'data' => $supplier
    ]);
}

    /**
     * Remove the specified resource from storage.
     */
public function destroy(string $id)
{
    $supplier = Suppliers::findOrFail($id);
    $supplier->delete();

    return response()->json([
        'message' => 'Supplier deleted successfully'
    ]);
}
}

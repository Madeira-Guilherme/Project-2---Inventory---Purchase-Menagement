<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index()
    {
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Products $product)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'sku' => 'required|string|max:255|unique:products,sku,' . $product->id,
        'description' => 'nullable|string',
        'unit_price' => 'required|numeric|min:0',
        'stock_quantity' => 'required|integer|min:0',
        'is_active' => 'boolean',
    ]);

    $validated['is_active'] = $request->has('is_active');

    $product->update($validated);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

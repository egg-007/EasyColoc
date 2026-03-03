<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Colocation;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
    public function store(StoreCategoryRequest $request,Colocation $colocation)
    {
        Category::create([
            'colocation_id' => $colocation->id,
            'name' => $request->validated('name'),
        ]);

        return redirect()->back()->with('success', 'Catégorie ajoutée avec succès.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update([
            'name' => $request->validated('name'),
        ]);

        return redirect()->back()->with('success', 'Catégorie mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $membership = auth()->user()->memberships()->whereNull('left_at')->first();

        // Check if owner
        if (!$membership || $membership->role !== 'Owner' || $category->colocation_id !== $membership->colocation_id) {
            abort(403, 'Unauthorized action.');
        }

        // Avoid dropping if expenses exist, or drop cascadingly. We'll simply verify if expenses are tied to it.
        if ($category->expenses()->exists()) {
            return redirect()->back()->with('error', 'Impossible de supprimer cette catégorie car elle contient des dépenses.');
        }

        $category->delete();

        return redirect()->back()->with('success', 'Catégorie supprimée avec succès.');
    }
}

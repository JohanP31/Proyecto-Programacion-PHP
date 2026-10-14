<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Categories::withCount('products')->orderBy('name')->paginate(15);

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        Categories::create($this->validatedData($request));

        return redirect()->route('categories.index')->with('status', 'Categoría creada correctamente.');
    }

    public function show(Categories $category)
    {
        return redirect()->route('categories.edit', $category);
    }

    public function edit(Categories $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Categories $category)
    {
        $category->update($this->validatedData($request, $category));

        return redirect()->route('categories.index')->with('status', 'Categoría actualizada correctamente.');
    }

    public function destroy(Categories $category)
    {
        if ($category->products()->exists()) {
            return back()->withErrors(['category' => 'No se puede eliminar una categoría que contiene productos.']);
        }

        $category->delete();

        return redirect()->route('categories.index')->with('status', 'Categoría eliminada correctamente.');
    }

    private function validatedData(Request $request, ?Categories $category = null): array
    {
        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categories', 'name')->ignore($category?->id),
            ],
        ]);
    }
}

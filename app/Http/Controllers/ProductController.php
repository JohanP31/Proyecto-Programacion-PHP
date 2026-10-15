<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        $products = Products::with('category')->latest('id')->paginate(15);

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Categories::orderBy('name')->get();

        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Products::create($data);

        return redirect()->route('admin.products.index')->with('status', 'Producto creado correctamente.');
    }

    public function show(Products $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Products $product)
    {
        $categories = Categories::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Products $product)
    {
        $data = $this->validatedData($request);
        $previousImage = $product->image;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        if ($request->hasFile('image') && $previousImage) {
            Storage::disk('public')->delete($previousImage);
        }

        return redirect()->route('admin.products.index')->with('status', 'Producto actualizado correctamente.');
    }

    public function destroy(Products $product)
    {
        if ($product->saleDetails()->exists()) {
            return back()->withErrors(['product' => 'No se puede eliminar un producto que ya aparece en ventas.']);
        }

        $image = $product->image;
        $product->delete();

        if ($image) {
            Storage::disk('public')->delete($image);
        }

        return redirect()->route('admin.products.index')->with('status', 'Producto eliminado correctamente.');
    }

    public function catalog()
    {
        $products = Products::with('category')
            ->where('stock', '>', 0)
            ->latest('id')
            ->paginate(12);

        return view('products.catalog', compact('products'));
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0.01', 'max:99999999.99'],
            'stock' => ['required', 'integer', 'min:0'],
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);
    }
}
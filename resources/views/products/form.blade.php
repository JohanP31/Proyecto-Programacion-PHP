{{-- // TEMPORALMENTE COMENTADO PARA ENTREGA ACADÉMICA
<div class="mb-3">
    <label class="form-label" for="name">Nombre</label>
    <input class="form-control" id="name" name="name" value="{{ old('name', $product->name ?? '') }}" maxlength="100" required>
</div>
// REACTIVAR EN FUTURAS VERSIONES --}}
<div class="mb-3">
    <label class="form-label" for="price">Precio</label>
    <input class="form-control" id="price" name="price" type="number" min="0.01" step="0.01" value="{{ old('price', $product->price ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label" for="stock">Stock</label>
    <input class="form-control" id="stock" name="stock" type="number" min="0" step="1" value="{{ old('stock', $product->stock ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label" for="category_id">Categoría</label>
    <select class="form-select" id="category_id" name="category_id" required>
        <option value="">Selecciona una categoría</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? '') == $category->id)>{{ $category->name }}</option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label class="form-label" for="image">Imagen (opcional)</label>
    <input class="form-control" id="image" name="image" type="file" accept="image/*">
    @if (isset($product) && $product->image)
        <img class="mt-2" src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" style="max-height: 120px">
    @endif
</div>

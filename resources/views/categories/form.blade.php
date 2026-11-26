<div class="mb-3">
    <label class="form-label" for="name">Nombre</label>
    <input class="form-control" id="name" name="name" maxlength="100" value="{{ old('name', $category->name ?? '') }}" required>
</div>

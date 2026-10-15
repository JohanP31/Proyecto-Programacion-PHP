{{-- // TEMPORALMENTE COMENTADO PARA ENTREGA ACADÉMICA
@extends('layouts.app')
@section('content')
    <div class="pagetitle d-flex justify-content-between">
        <h1>Categorías</h1>
        <a class="btn btn-primary" href="{{ route('admin.categories.create') }}">Nueva categoría</a>
    </div>
    <div class="card"><div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead><tr><th>Nombre</th><th>Productos</th><th></th></tr></thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td><td>{{ $category->products_count }}</td>
                            <td class="d-flex gap-2">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.categories.edit', $category) }}">Editar</a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('¿Eliminar esta categoría?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $categories->links() }}
    </div></div>
@endsection
// REACTIVAR EN FUTURAS VERSIONES --}}

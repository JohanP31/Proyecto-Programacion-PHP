{{-- // TEMPORALMENTE COMENTADO PARA ENTREGA ACADÉMICA
@extends('layouts.app')

@section('content')
    <div class="pagetitle d-flex justify-content-between">
        <h1>Productos</h1>
        <a class="btn btn-primary" href="{{ route('admin.products.create') }}">Nuevo producto</a>
    </div>
    <div class="card"><div class="card-body">
        <div class="table-responsive">
            <table class="table datatable">
                <thead><tr><th>Producto</th><th>Categoría</th><th>Precio</th><th>Stock</th><th></th></tr></thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td>${{ number_format((float) $product->price, 2) }}</td>
                            <td>{{ $product->stock }}</td>
                            <td class="d-flex gap-2">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.products.edit', $product) }}">Editar</a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('¿Eliminar este producto?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $products->links() }}
    </div></div>
@endsection
// REACTIVAR EN FUTURAS VERSIONES --}}

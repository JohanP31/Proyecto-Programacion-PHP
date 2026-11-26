@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Productos</h1>
    </div>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Disponibles</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td>${{ number_format((float) $product->price, 2) }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('products.show', $product) }}">Ver producto</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No hay productos disponibles en este momento.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $products->links() }}
        </div>
    </div>
@endsection

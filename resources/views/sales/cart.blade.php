@extends('layouts.app')
@section('content')
    <div class="pagetitle"><h1>Carrito</h1></div>
    @if ($items->isEmpty())
        <p>Tu carrito está vacío. <a href="{{ route('products.catalog') }}">Ver catálogo</a></p>
    @else
        <div class="card"><div class="card-body table-responsive">
            <table class="table">
                <thead><tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th><th></th></tr></thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td>{{ $item['product']->name }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>${{ number_format((float) $item['product']->price, 2) }}</td>
                            <td>${{ number_format((float) $item['product']->price * $item['quantity'], 2) }}</td>
                            <td><form method="POST" action="{{ route('cart.remove', $item['product']) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Quitar</button></form></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p class="text-end fw-bold">Total: ${{ number_format((float) $total, 2) }}</p>
            {{-- // TEMPORALMENTE COMENTADO PARA ENTREGA ACADÉMICA
            @auth
                <form method="POST" action="{{ route('sales.store') }}" class="text-end">@csrf<button class="btn btn-primary">Confirmar compra</button></form>
            @else
                <p class="text-end"><a class="btn btn-primary" href="{{ route('login') }}">Inicia sesión para comprar</a></p>
            @endauth
            // REACTIVAR EN FUTURAS VERSIONES --}}
        </div></div>
    @endif
@endsection

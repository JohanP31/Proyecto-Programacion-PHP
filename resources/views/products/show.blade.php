@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>{{ $product->name }}</h1>
    </div>
    <div class="card">
        <div class="card-body p-4">
            @if ($product->image)
                <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="img-fluid mb-3" style="max-height: 360px">
            @endif
            <p>Categoría: {{ $product->category->name }}</p>
            <p>Precio: ${{ number_format((float) $product->price, 2) }}</p>
            <p>Disponibles: {{ $product->stock }}</p>
            @if ($product->stock > 0)
                <form method="POST" action="{{ route('cart.add', $product) }}" class="d-flex gap-2">
                    @csrf
                    <input class="form-control" type="number" name="quantity" min="1" max="{{ $product->stock }}" value="1" required style="max-width: 100px">
                    <button class="btn btn-primary">Agregar al carrito</button>
                </form>
            @else
                <p class="text-danger">Producto agotado.</p>
            @endif
        </div>
    </div>
@endsection

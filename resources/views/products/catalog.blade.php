@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Catálogo de productos</h1>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="row">
        @forelse ($products as $product)
            <div class="col-md-4 col-lg-3 mb-4">
                <div class="card h-100">
                    @if ($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 190px; object-fit: cover">
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="text-muted">{{ $product->category->name }}</p>
                        <p class="mb-3">${{ number_format((float) $product->price, 2) }}</p>
                        <div class="mt-auto d-flex gap-2">
                            <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary">Ver</a>
                            <form method="POST" action="{{ route('cart.add', $product) }}">
                                @csrf
                                <button class="btn btn-primary" type="submit">Agregar al carrito</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p>No hay productos disponibles en este momento.</p>
        @endforelse
    </div>

    {{ $products->links() }}
@endsection

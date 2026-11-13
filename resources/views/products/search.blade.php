@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Resultados de búsqueda</h1>
    </div>

    @if ($searchError)
        <div class="alert alert-warning" role="alert">{{ $searchError }}</div>
    @else
        <p>Resultados para: <strong>{{ $query }}</strong></p>
        <div class="row">
            @forelse ($products as $product)
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card h-100">
                        @if ($product->image)
                            <img src="{{ asset('storage/'.$product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 190px; object-fit: cover">
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h2 class="h5 card-title">{{ $product->name }}</h2>
                            <p class="text-muted">{{ $product->category->name }}</p>
                            <p>${{ number_format((float) $product->price, 2) }}</p>
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
                <p>No se encontraron resultados para la búsqueda realizada.</p>
            @endforelse
        </div>
        {{ $products->links() }}
    @endif
@endsection

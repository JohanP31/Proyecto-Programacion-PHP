@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Acceso temporalmente no disponible</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <p>El inicio de sesión se está actualizando. Las áreas protegidas siguen restringidas.</p>
            <a class="btn btn-primary" href="{{ route('products.catalog') }}">Volver al catálogo</a>
        </div>
    </div>
@endsection

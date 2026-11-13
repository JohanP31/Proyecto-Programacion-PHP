@extends('layouts.app')
@section('content')
    <div class="pagetitle"><h1>Editar producto</h1></div>
    <form class="card card-body" method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('products.form')
        <div><button class="btn btn-primary">Guardar cambios</button> <a class="btn btn-secondary" href="{{ route('admin.products.index') }}">Cancelar</a></div>
    </form>
@endsection

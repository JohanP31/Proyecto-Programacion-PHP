@extends('layouts.app')
@section('content')
    <div class="pagetitle"><h1>Crear producto</h1></div>
    <form class="card card-body" method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf
        @include('products.form')
        <div><button class="btn btn-primary">Guardar</button> <a class="btn btn-secondary" href="{{ route('admin.products.index') }}">Cancelar</a></div>
    </form>
@endsection

@extends('layouts.app')
@section('content')
    <div class="pagetitle"><h1>Nueva categoría</h1></div>
    <form class="card card-body" method="POST" action="{{ route('admin.categories.store') }}">
        @csrf
        @include('categories.form')
        <div><button class="btn btn-primary">Guardar</button> <a class="btn btn-secondary" href="{{ route('admin.categories.index') }}">Cancelar</a></div>
    </form>
@endsection

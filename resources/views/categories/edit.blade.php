{{-- // TEMPORALMENTE COMENTADO PARA ENTREGA ACADÉMICA
@extends('layouts.app')
@section('content')
    <div class="pagetitle"><h1>Editar categoría</h1></div>
    <form class="card card-body" method="POST" action="{{ route('admin.categories.update', $category) }}">
        @csrf @method('PUT')
        @include('categories.form')
        <div><button class="btn btn-primary">Guardar cambios</button> <a class="btn btn-secondary" href="{{ route('admin.categories.index') }}">Cancelar</a></div>
    </form>
@endsection
// REACTIVAR EN FUTURAS VERSIONES --}}

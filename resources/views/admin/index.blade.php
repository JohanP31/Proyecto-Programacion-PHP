{{-- // TEMPORALMENTE COMENTADO PARA ENTREGA ACADÉMICA
@extends('layouts.app')
@section('content')
    <div class="pagetitle"><h1>Administración</h1></div>
    <div class="row">
        <div class="col-md-4 mb-3"><a class="card card-body text-decoration-none" href="{{ route('admin.products.index') }}">Administrar productos</a></div>
        <div class="col-md-4 mb-3"><a class="card card-body text-decoration-none" href="{{ route('admin.categories.index') }}">Administrar categorías</a></div>
        <div class="col-md-4 mb-3"><a class="card card-body text-decoration-none" href="{{ route('admin.sales.index') }}">Historial de ventas</a></div>
    </div>
@endsection
// REACTIVAR EN FUTURAS VERSIONES --}}

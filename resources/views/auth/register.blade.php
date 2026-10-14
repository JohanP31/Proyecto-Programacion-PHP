@extends('layouts.app')
@section('content')
    <div class="pagetitle"><h1>Crear cuenta de cliente</h1></div>
    <form class="card card-body" method="POST" action="{{ route('register.store') }}">
        @csrf
        <div class="mb-3"><label class="form-label" for="name">Nombre</label><input class="form-control" name="name" id="name" value="{{ old('name') }}" maxlength="100" required></div>
        <div class="mb-3"><label class="form-label" for="email">Correo</label><input class="form-control" type="email" name="email" id="email" value="{{ old('email') }}" maxlength="150" required></div>
        <div class="mb-3"><label class="form-label" for="password">Contraseña (mínimo 8 caracteres)</label><input class="form-control" type="password" name="password" id="password" minlength="8" required></div>
        <div class="mb-3"><label class="form-label" for="password_confirmation">Confirmar contraseña</label><input class="form-control" type="password" name="password_confirmation" id="password_confirmation" required></div>
        <div><button class="btn btn-primary">Crear cuenta</button></div>
    </form>
@endsection

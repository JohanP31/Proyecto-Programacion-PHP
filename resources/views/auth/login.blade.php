@extends('layouts.app')
@section('content')
    <div class="pagetitle"><h1>Iniciar sesión</h1></div>
    <form class="card card-body" method="POST" action="{{ route('login.store') }}">
        @csrf
        <div class="mb-3"><label class="form-label" for="email">Correo</label><input class="form-control" type="email" name="email" id="email" value="{{ old('email') }}" autocomplete="email" required></div>
        <div class="mb-3"><label class="form-label" for="password">Contraseña</label><input class="form-control" type="password" name="password" id="password" autocomplete="current-password" required></div>
        <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="remember" id="remember"><label class="form-check-label" for="remember">Recordarme</label></div>
        <div><button class="btn btn-primary">Entrar</button> <a href="{{ route('register') }}">Crear cuenta</a></div>
    </form>
@endsection

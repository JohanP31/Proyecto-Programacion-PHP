@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="pagetitle">
        <h1>Editar Usuario</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Administración</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Usuarios</a></li>
                <li class="breadcrumb-item active">Editar</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                
                <div class="card admin-card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Modificar datos de: {{ $user->name }}</h5>

                        <form action="{{ route('admin.users.update', $user) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="name" class="form-label">Nombre completo</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Nueva Contraseña (Opcional)</label>
                                <input type="password" class="form-control" id="password" name="password">
                                <div class="form-text">Déjala en blanco si no deseas cambiar la contraseña actual.</div>
                            </div>

                            <div class="mb-4">
                                <label for="role" class="form-label">Rol del usuario</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="Administrador" {{ (old('role', $user->role) == 'Administrador' || old('role', $user->role) == 'admin') ? 'selected' : '' }}>Administrador</option>
                                    <option value="Cliente" {{ (old('role', $user->role) == 'Cliente' || old('role', $user->role) == 'cliente') ? 'selected' : '' }}>Cliente</option>
                                </select>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary px-4">Actualizar Usuario</button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary px-4">Cancelar</a>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
@endsection
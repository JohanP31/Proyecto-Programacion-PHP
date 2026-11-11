@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="pagetitle">
        <h1>Crear Nuevo Usuario</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Administración</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Usuarios</a></li>
                <li class="breadcrumb-item active">Crear</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                
                <div class="card admin-card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Formulario de Registro</h5>

                        <form action="{{ route('admin.users.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Nombre completo</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div class="form-text">Mínimo 6 caracteres.</div>
                            </div>

                            <div class="mb-4">
                                <label for="role" class="form-label">Rol del usuario</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="">Selecciona un rol...</option>
                                    <option value="Administrador" {{ old('role') == 'Administrador' ? 'selected' : '' }}>Administrador</option>
                                    <option value="Cliente" {{ old('role') == 'Cliente' ? 'selected' : '' }}>Cliente</option>
                                </select>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary px-4">Guardar Usuario</button>
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
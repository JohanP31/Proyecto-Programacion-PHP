@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="pagetitle">
        <h1>Gestión de Usuarios</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Administración</a></li>
                <li class="breadcrumb-item active">Usuarios</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card admin-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
                            <h5 class="card-title mb-0">Lista de Usuarios</h5>
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                <i class="bi bi-person-plus-fill"></i> Nuevo Usuario
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle table-custom">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Correo Electrónico</th>
                                        <th>Rol</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td><strong>{{ $user->name }}</strong></td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <span class="badge bg-{{ in_array(mb_strtolower($user->role), ['admin', 'administrador']) ? 'danger' : 'secondary' }}">
                                                    {{ $user->role }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-action" title="Editar">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-action" title="Eliminar">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">No hay usuarios registrados.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $users->links() }}
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
@endsection
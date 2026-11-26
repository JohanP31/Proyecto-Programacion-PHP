@extends('layouts.app')
@section('content')
    @php
        $canCreateCategories = auth()->user()->hasPermission('categories.create');
        $canUpdateCategories = auth()->user()->hasPermission('categories.update');
        $canDeleteCategories = auth()->user()->hasPermission('categories.delete');
        $canManageCategories = $canUpdateCategories || $canDeleteCategories;
    @endphp

    <div class="pagetitle d-flex justify-content-between">
        <h1>Categorías</h1>
        @if ($canCreateCategories)
            <a class="btn btn-primary" href="{{ route('admin.categories.create') }}">Nueva categoría</a>
        @endif
    </div>
    <div class="card"><div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Productos</th>
                        @if ($canManageCategories)
                            <th>Acciones</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->products_count }}</td>
                            @if ($canManageCategories)
                                <td class="d-flex gap-2">
                                    @if ($canUpdateCategories)
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.categories.edit', $category) }}">Editar</a>
                                    @endif
                                    @if ($canDeleteCategories)
                                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('¿Eliminar esta categoría?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                        </form>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $categories->links() }}
    </div></div>
@endsection

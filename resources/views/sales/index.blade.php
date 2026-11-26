@extends('layouts.app')
@section('content')
    @php
        $canViewAllSales = auth()->user()->hasPermission('sales.view_all');
    @endphp

    <div class="pagetitle"><h1>{{ $canViewAllSales ? 'Historial de ventas' : 'Mis compras' }}</h1></div>
    <div class="card"><div class="card-body table-responsive">
        <table class="table">
            <thead><tr><th>Venta</th><th>Fecha</th>@if ($canViewAllSales)<th>Cliente</th>@endif<th>Total</th><th>Estado</th><th></th></tr></thead>
            <tbody>
                @forelse ($sales as $sale)
                    <tr><td>#{{ $sale->id }}</td><td>{{ $sale->sale_date }}</td>@if ($canViewAllSales)<td>{{ $sale->user->name }}</td>@endif<td>${{ number_format((float) $sale->total, 2) }}</td><td>{{ $sale->status }}</td><td><a class="btn btn-sm btn-outline-primary" href="{{ route('sales.show', $sale) }}">Ver</a></td></tr>
                @empty
                    <tr><td colspan="6">No hay compras registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $sales->links() }}
    </div></div>
@endsection

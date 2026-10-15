{{-- // TEMPORALMENTE COMENTADO PARA ENTREGA ACADÉMICA
@extends('layouts.app')
@section('content')
    <div class="pagetitle"><h1>Venta #{{ $sale->id }}</h1></div>
    <p>Fecha: {{ $sale->sale_date }} | Cliente: {{ $sale->user->name }} | Estado: {{ $sale->status }}</p>
    <div class="card"><div class="card-body table-responsive">
        <table class="table">
            <thead><tr><th>Producto</th><th>Cantidad</th><th>Precio unitario</th><th>Subtotal</th></tr></thead>
            <tbody>
                @foreach ($sale->details as $detail)
                    <tr><td>{{ $detail->product->name }}</td><td>{{ $detail->quantity }}</td><td>${{ number_format((float) $detail->unitary_price, 2) }}</td><td>${{ number_format((float) $detail->final_price, 2) }}</td></tr>
                @endforeach
            </tbody>
        </table>
        <p class="fw-bold">Total: ${{ number_format((float) $sale->total, 2) }}</p>
        <h2 class="h5">Pagos</h2>
        @forelse ($sale->payments as $payment)
            <p>{{ $payment->payment_method }} — ${{ number_format((float) $payment->amount, 2) }} — {{ $payment->status }}</p>
        @empty
            @if ($sale->user_id === auth()->id())
                <a class="btn btn-primary" href="{{ route('payments.create', $sale) }}">Registrar pago</a>
            @else
                <p>Sin pagos registrados.</p>
            @endif
        @endforelse
    </div></div>
@endsection
// REACTIVAR EN FUTURAS VERSIONES --}}

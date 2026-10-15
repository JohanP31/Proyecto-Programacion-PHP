{{-- // TEMPORALMENTE COMENTADO PARA ENTREGA ACADÉMICA
@extends('layouts.app')
@section('content')
    <div class="pagetitle"><h1>Registrar pago de venta #{{ $sale->id }}</h1></div>
    <div class="card"><div class="card-body">
        <p>Total a pagar: <strong>${{ number_format((float) $sale->total, 2) }}</strong></p>
        <form method="POST" action="{{ route('payments.store', $sale) }}">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="payment_method">Método de pago</label>
                <select class="form-select" name="payment_method" id="payment_method" required>
                    <option value="">Selecciona un método</option>
                    <option value="transferencia">Transferencia</option>
                    <option value="tarjeta">Tarjeta</option>
                    <option value="efectivo">Efectivo</option>
                </select>
            </div>
            <button class="btn btn-primary">Registrar pago pendiente</button>
        </form>
        <p class="text-muted mt-3">Este registro no procesa cobros; la confirmación del pago debe realizarse mediante un proveedor o por administración.</p>
    </div></div>
@endsection
// REACTIVAR EN FUTURAS VERSIONES --}}

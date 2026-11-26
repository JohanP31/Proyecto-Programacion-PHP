@extends('layouts.app')
@section('content')
    <div class="pagetitle"><h1>Carrito</h1></div>
    @if ($items->isEmpty())
        <p>Tu carrito está vacío. <a href="{{ route('products.catalog') }}">Ver catálogo</a></p>
    @else
        <div class="card"><div class="card-body table-responsive">
            <table class="table">
                <thead><tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th><th></th></tr></thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td>{{ $item['product']->name }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>${{ number_format((float) $item['product']->price, 2) }}</td>
                            <td>${{ number_format((float) $item['product']->price * $item['quantity'], 2) }}</td>
                            <td><form method="POST" action="{{ route('cart.remove', $item['product']) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Quitar</button></form></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p class="text-end fw-bold fs-5">Monto total: ${{ number_format((float) $total, 2) }}</p>
            <form method="POST" action="{{ route('sales.store') }}">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="payment_method">Opción de pago</label>
                        <select class="form-select" name="payment_method" id="payment_method" required>
                            <option value="">Selecciona un método de pago</option>
                            <option value="transferencia" @selected(old('payment_method') === 'transferencia')>Transferencia</option>
                            <option value="tarjeta" @selected(old('payment_method') === 'tarjeta')>Tarjeta</option>
                            <option value="efectivo" @selected(old('payment_method') === 'efectivo')>Efectivo</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3 text-md-end">
                        <button type="submit" class="btn btn-primary">Pagar</button>
                    </div>
                </div>
            </form>
            <p class="text-muted mb-0">El pago queda pendiente de confirmación; esta pantalla no realiza cobros automáticos.</p>
        </div></div>
    @endif
@endsection

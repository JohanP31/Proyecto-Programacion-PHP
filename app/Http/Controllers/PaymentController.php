<?php

namespace App\Http\Controllers;

use App\Models\Payments;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function create(Request $request, Sales $sale)
    {
        abort_unless($sale->user_id === $request->user()->id, 403);

        return view('payments.create', compact('sale'));
    }

    public function store(Request $request, Sales $sale)
    {
        abort_unless($sale->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'payment_method' => ['required', 'string', 'max:50'],
        ]);

        DB::transaction(function () use ($sale, $data) {
            $lockedSale = Sales::whereKey($sale->id)->lockForUpdate()->firstOrFail();

            if ($lockedSale->payments()->whereIn('status', ['completed', 'paid'])->exists()) {
                abort(409, 'Esta venta ya tiene un pago confirmado.');
            }

            Payments::create([
                'payment_method' => $data['payment_method'],
                'amount' => $lockedSale->total,
                'status' => 'pending',
                'sale_id' => $lockedSale->id,
            ]);
        });

        return redirect()->route('sales.show', $sale)->with('status', 'Pago registrado como pendiente de confirmación.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Sale_Details;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    public function cart()
    {
        $cart = session('cart', []);
        $products = Products::whereIn('id', array_keys($cart))->get()->keyBy('id');
        $items = collect($cart)->map(function ($quantity, $id) use ($products) {
            $product = $products->get($id);

            return $product ? ['product' => $product, 'quantity' => $quantity] : null;
        })->filter();
        $total = $items->sum(fn (array $item) => $item['product']->price * $item['quantity']);

        return view('sales.cart', compact('items', 'total'));
    }

    public function addToCart(Request $request, Products $product)
    {
        $data = $request->validate(['quantity' => ['sometimes', 'integer', 'min:1']]);
        $quantity = (int) ($data['quantity'] ?? 1);
        $cart = session('cart', []);
        $newQuantity = ($cart[$product->id] ?? 0) + $quantity;

        if ($newQuantity > $product->stock) {
            return back()->withErrors(['cart' => 'La cantidad solicitada supera el inventario disponible.']);
        }

        $cart[$product->id] = $newQuantity;
        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('status', 'Producto agregado al carrito.');
    }

    public function removeFromCart(Products $product)
    {
        $cart = session('cart', []);
        unset($cart[$product->id]);
        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('status', 'Producto eliminado del carrito.');
    }

    public function store(Request $request)
    {
        $cart = session('cart', []);

        if ($cart === []) {
            return redirect()->route('cart.index')->withErrors(['cart' => 'El carrito está vacío.']);
        }

        $sale = DB::transaction(function () use ($cart, $request) {
            $products = Products::whereIn('id', array_keys($cart))
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($products->count() !== count($cart)) {
                throw ValidationException::withMessages([
                    'cart' => 'Uno o más productos ya no están disponibles.',
                ]);
            }

            $totalCents = 0;
            $maximumAmountCents = 9_999_999_999;
            foreach ($cart as $productId => $quantity) {
                $product = $products->get($productId);
                if (! is_int($quantity) || $quantity < 1 || $product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'cart' => "Inventario insuficiente para {$product->name}.",
                    ]);
                }

                $unitPriceCents = (int) round((float) $product->price * 100);
                if (
                    $unitPriceCents < 1
                    || $quantity > intdiv($maximumAmountCents, $unitPriceCents)
                ) {
                    throw ValidationException::withMessages([
                        'cart' => "El subtotal de {$product->name} supera el máximo permitido.",
                    ]);
                }

                $subtotalCents = $unitPriceCents * $quantity;
                if ($totalCents > $maximumAmountCents - $subtotalCents) {
                    throw ValidationException::withMessages([
                        'cart' => 'El total de la compra supera el máximo permitido.',
                    ]);
                }

                $totalCents += $subtotalCents;
            }

            $sale = Sales::create([
                'sale_date' => now(),
                'total' => number_format($totalCents / 100, 2, '.', ''),
                'status' => 'pending',
                'user_id' => $request->user()->id,
            ]);

            foreach ($cart as $productId => $quantity) {
                $product = $products->get($productId);
                $unitPriceCents = (int) round((float) $product->price * 100);

                Sale_Details::create([
                    'quantity' => $quantity,
                    'unitary_price' => $product->price,
                    'final_price' => number_format(($unitPriceCents * $quantity) / 100, 2, '.', ''),
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                ]);

                $product->decrement('stock', $quantity);
            }

            return $sale;
        });

        session()->forget('cart');

        return redirect()->route('payments.create', $sale)->with('status', 'Compra registrada. Completa el registro del pago.');
    }

    public function index(Request $request)
    {
        $sales = Sales::with('user')
            ->when(! in_array(mb_strtolower((string) $request->user()->role), ['admin', 'administrador'], true),
                fn ($query) => $query->where('user_id', $request->user()->id))
            ->latest('id')
            ->paginate(15);

        return view('sales.index', compact('sales'));
    }

    public function show(Request $request, Sales $sale)
    {
        abort_unless(
            $sale->user_id === $request->user()->id
                || in_array(mb_strtolower((string) $request->user()->role), ['admin', 'administrador'], true),
            403
        );

        $sale->load(['details.product', 'payments', 'user']);

        return view('sales.show', compact('sale'));
    }
}

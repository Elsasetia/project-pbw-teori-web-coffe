<?php

namespace App\Http\Controllers;

use App\Exceptions\CheckoutException;
use App\Http\Requests\CartItemRequest;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(CartService $cart): View
    {
        $lines = $cart->lines();

        return view('cart.index', [
            'lines' => $lines,
            'total' => $cart->total($lines),
        ]);
    }

    public function store(CartItemRequest $request, Product $product, CartService $cart): RedirectResponse
    {
        abort_unless($product->is_active, 404);

        try {
            $cart->add($product, $request->integer('quantity'));
        } catch (CheckoutException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', "{$product->name} masuk ke keranjang.");
    }

    public function update(CartItemRequest $request, Product $product, CartService $cart): RedirectResponse
    {
        $cart->update($product, $request->integer('quantity'));

        return redirect()->route('cart.index');
    }

    public function destroy(Product $product, CartService $cart): RedirectResponse
    {
        $cart->remove($product);

        return redirect()->route('cart.index')->with('success', "{$product->name} dihapus dari keranjang.");
    }
}

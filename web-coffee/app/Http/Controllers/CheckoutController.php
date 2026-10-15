<?php

namespace App\Http\Controllers;

use App\Enums\PaymentMethod;
use App\Exceptions\CheckoutException;
use App\Http\Requests\CheckoutRequest;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function create(CartService $cart): View|RedirectResponse
    {
        $lines = $cart->lines();

        if ($lines->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong. Pilih menu dulu.');
        }

        return view('checkout.create', [
            'lines' => $lines,
            'total' => $cart->total($lines),
            'paymentMethods' => PaymentMethod::cases(),
        ]);
    }

    public function store(CheckoutRequest $request, OrderService $orders): RedirectResponse
    {
        try {
            $order = $orders->placeOrder($request->user(), $request->validated());
        } catch (CheckoutException $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }

        return redirect()
            ->route('orders.show', $order->order_code)
            ->with('success', 'Pesananmu sudah kami terima.');
    }
}

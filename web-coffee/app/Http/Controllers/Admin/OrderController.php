<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Models\Order;
use App\Services\OrderService;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $status = is_string($request->query('status'))
            ? OrderStatus::tryFrom($request->query('status'))
            : null;

        $orders = Order::query()
            ->with('user')
            ->when($status, fn ($query) => $query->where('status', $status->value))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'activeStatus' => $status,
        ]);
    }

    public function show(Order $order): View
    {
        return view('admin.orders.show', [
            'order' => $order->load(['items', 'user']),
        ]);
    }

    public function update(UpdateOrderStatusRequest $request, Order $order, OrderService $orders): RedirectResponse
    {
        try {
            $orders->updateStatus($order, $request->enum('status', OrderStatus::class));
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Status pesanan diperbarui.');
    }
}

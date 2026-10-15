<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

/**
 * Menyiapkan angka-angka untuk dashboard admin.
 */
class DashboardService
{
    private const LOW_STOCK_LIMIT = 5;

    /** @return array<string, mixed> */
    public function summary(): array
    {
        $cancelled = OrderStatus::Cancelled->value;

        $todayOrders = Order::query()->whereDate('created_at', today());

        return [
            'orders_today' => (clone $todayOrders)->count(),
            'revenue_today' => (int) (clone $todayOrders)->where('status', '!=', $cancelled)->sum('total'),
            'pending_orders' => Order::query()->where('status', OrderStatus::Pending->value)->count(),
            'active_products' => Product::query()->active()->count(),

            'best_sellers' => OrderItem::query()
                ->select('product_name', DB::raw('SUM(quantity) as total_qty'))
                ->whereHas('order', fn ($q) => $q->where('status', '!=', $cancelled))
                ->groupBy('product_name')
                ->orderByDesc('total_qty')
                ->limit(5)
                ->get(),

            'low_stock' => Product::query()
                ->active()
                ->where('stock', '<=', self::LOW_STOCK_LIMIT)
                ->orderBy('stock')
                ->limit(8)
                ->get(),

            'recent_orders' => Order::query()->with('user')->latest()->limit(8)->get(),
        ];
    }
}

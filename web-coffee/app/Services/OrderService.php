<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Exceptions\CheckoutException;
use App\Exceptions\InsufficientStockException;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Support\CartLine;
use DomainException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    public function __construct(private readonly CartService $cart)
    {
    }

    /**
     * Mengubah isi keranjang menjadi pesanan.
     *
     * Semuanya dibungkus transaksi database: kalau satu langkah gagal
     * (misalnya stok kurang), tidak ada pesanan setengah jadi dan stok tidak berubah.
     *
     * @param  array{customer_name: string, customer_phone: string, payment_method: string, notes?: ?string}  $data
     *
     * @throws CheckoutException
     */
    public function placeOrder(User $user, array $data): Order
    {
        $lines = $this->cart->lines();

        if ($lines->isEmpty()) {
            throw CheckoutException::emptyCart();
        }

        $order = DB::transaction(function () use ($user, $data, $lines) {
            // Kunci baris produk agar dua pembeli tidak bisa mengambil stok terakhir bersamaan.
            $products = Product::query()
                ->whereIn('id', $lines->map(fn (CartLine $line) => $line->product->id)->all())
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $items = [];
            $total = 0;

            foreach ($lines as $line) {
                $product = $products->get($line->product->id);

                if (! $product || ! $product->is_active || $product->stock < $line->quantity) {
                    throw InsufficientStockException::forProduct($line->product);
                }

                $subtotal = $product->price * $line->quantity;
                $total += $subtotal;

                $items[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $line->quantity,
                    'subtotal' => $subtotal,
                ];

                $product->decrement('stock', $line->quantity);
            }

            $order = Order::create([
                'order_code' => $this->generateCode(),
                'user_id' => $user->id,
                'status' => OrderStatus::Pending,
                'payment_method' => $data['payment_method'],
                'total' => $total,
                'customer_name' => $data['customer_name'],
                'customer_phone' => $data['customer_phone'],
                'notes' => $data['notes'] ?? null,
            ]);

            $order->items()->createMany($items);

            return $order;
        });

        // Dikosongkan setelah transaksi sukses supaya keranjang tidak hilang kalau gagal.
        $this->cart->clear();

        return $order;
    }

    /**
     * Mengubah status pesanan (dipakai admin).
     * Pesanan yang dibatalkan mengembalikan stok dan tidak bisa dibuka lagi.
     *
     * @throws DomainException
     */
    public function updateStatus(Order $order, OrderStatus $status): Order
    {
        if ($order->status === OrderStatus::Cancelled) {
            throw new DomainException('Pesanan yang sudah dibatalkan tidak bisa diubah lagi.');
        }

        if ($order->status === $status) {
            return $order;
        }

        return DB::transaction(function () use ($order, $status) {
            if ($status === OrderStatus::Cancelled) {
                foreach ($order->items()->whereNotNull('product_id')->get() as $item) {
                    Product::query()->whereKey($item->product_id)->increment('stock', $item->quantity);
                }
            }

            $order->update(['status' => $status]);

            return $order;
        });
    }

    private function generateCode(): string
    {
        return 'WC-'.now()->format('ymd').'-'.Str::upper(Str::random(5));
    }
}

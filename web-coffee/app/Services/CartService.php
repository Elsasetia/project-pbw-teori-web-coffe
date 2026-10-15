<?php

namespace App\Services;

use App\Exceptions\InsufficientStockException;
use App\Models\Product;
use App\Support\CartLine;
use Illuminate\Support\Collection;

/**
 * Keranjang belanja berbasis session.
 * Yang disimpan di session hanya [product_id => jumlah]; nama, harga, dan stok
 * selalu dibaca ulang dari database supaya tidak basi.
 */
class CartService
{
    private const SESSION_KEY = 'cart';

    public function add(Product $product, int $quantity = 1): void
    {
        if (! $product->isAvailable()) {
            throw InsufficientStockException::forProduct($product);
        }

        $cart = $this->raw();
        $wanted = ($cart[$product->id] ?? 0) + max($quantity, 1);
        $cart[$product->id] = min($wanted, $product->stock);

        $this->save($cart);
    }

    public function update(Product $product, int $quantity): void
    {
        $cart = $this->raw();

        if (! isset($cart[$product->id])) {
            return;
        }

        $quantity = min($quantity, $product->stock);

        if ($quantity < 1) {
            $this->remove($product);

            return;
        }

        $cart[$product->id] = $quantity;
        $this->save($cart);
    }

    public function remove(Product $product): void
    {
        $cart = $this->raw();
        unset($cart[$product->id]);

        $this->save($cart);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    /** Jumlah total item (untuk badge di navbar). */
    public function count(): int
    {
        return array_sum($this->raw());
    }

    /**
     * Isi keranjang sebagai daftar CartLine.
     * Produk yang sudah nonaktif/habis otomatis tidak ikut ditampilkan.
     *
     * @return Collection<int, CartLine>
     */
    public function lines(): Collection
    {
        $raw = $this->raw();

        if ($raw === []) {
            return collect();
        }

        $products = Product::query()
            ->active()
            ->whereIn('id', array_keys($raw))
            ->get()
            ->keyBy('id');

        return collect($raw)
            ->map(function (int $quantity, int $productId) use ($products) {
                $product = $products->get($productId);

                if (! $product) {
                    return null;
                }

                $quantity = min($quantity, $product->stock);

                return $quantity >= 1 ? new CartLine($product, $quantity) : null;
            })
            ->filter()
            ->values();
    }

    /** @param Collection<int, CartLine> $lines */
    public function total(Collection $lines): int
    {
        return $lines->sum(fn (CartLine $line) => $line->subtotal());
    }

    /** @return array<int, int> */
    private function raw(): array
    {
        return session()->get(self::SESSION_KEY, []);
    }

    /** @param array<int, int> $cart */
    private function save(array $cart): void
    {
        session()->put(self::SESSION_KEY, $cart);
    }
}

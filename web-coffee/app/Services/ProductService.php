<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Pengelolaan menu oleh admin, termasuk upload & pembersihan gambar.
 */
class ProductService
{
    /** @param array<string, mixed> $data hasil $request->validated() */
    public function create(array $data): Product
    {
        return Product::create($this->prepare($data));
    }

    /** @param array<string, mixed> $data hasil $request->validated() */
    public function update(Product $product, array $data): Product
    {
        $product->update($this->prepare($data, $product));

        return $product;
    }

    public function delete(Product $product): void
    {
        $this->deleteImage($product->image);
        $product->delete();
    }

    /** @param array<string, mixed> $data */
    private function prepare(array $data, ?Product $existing = null): array
    {
        if (($data['image'] ?? null) instanceof UploadedFile) {
            $this->deleteImage($existing?->image);
            $data['image'] = $data['image']->store('products', 'public');
        } else {
            // Tidak ada file baru -> jangan timpa gambar lama.
            unset($data['image']);
        }

        return $data;
    }

    private function deleteImage(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}

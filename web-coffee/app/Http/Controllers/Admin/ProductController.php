<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('admin.products.index', [
            'products' => Product::query()->with('category')->latest()->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.products.create', [
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function store(ProductRequest $request, ProductService $products): RedirectResponse
    {
        $product = $products->create($request->validated());

        return redirect()
            ->route('admin.products.index')
            ->with('success', "Menu {$product->name} ditambahkan.");
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', [
            'product' => $product,
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function update(ProductRequest $request, Product $product, ProductService $products): RedirectResponse
    {
        $products->update($product, $request->validated());

        return redirect()
            ->route('admin.products.index')
            ->with('success', "Menu {$product->name} diperbarui.");
    }

    public function destroy(Product $product, ProductService $products): RedirectResponse
    {
        $products->delete($product);

        return redirect()
            ->route('admin.products.index')
            ->with('success', "Menu {$product->name} dihapus.");
    }
}

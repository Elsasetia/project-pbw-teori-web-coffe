<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => Category::query()->withCount('products')->orderBy('name')->get(),
        ]);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $category = Category::create($request->validated());

        return back()->with('success', "Kategori {$category->name} ditambahkan.");
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        return back()->with('success', 'Nama kategori diperbarui.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        // Menu di kategori ini tidak ikut terhapus; kategorinya jadi kosong (lihat migrasi).
        $category->delete();

        return back()->with('success', "Kategori {$category->name} dihapus.");
    }
}

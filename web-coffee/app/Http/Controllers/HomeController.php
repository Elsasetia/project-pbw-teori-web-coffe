<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Feedback;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        // Query string bisa berupa array (?q[]=x); hanya terima string biasa.
        $categorySlug = is_string($request->query('category')) && $request->query('category') !== ''
            ? $request->query('category')
            : null;
        $search = is_string($request->query('q')) ? trim($request->query('q')) : '';

        $products = Product::query()
            ->active()
            ->with('category')
            ->inCategory($categorySlug)
            ->search($search)
            ->orderBy('name')
            ->paginate(8)
            ->withQueryString()
            ->fragment('menu');

        return view('home', [
            'products' => $products,
            'categories' => Category::query()->orderBy('name')->get(),
            'activeCategory' => $categorySlug,
            'search' => $search,
            'featured' => Product::query()->active()->where('stock', '>', 0)->latest()->limit(5)->get(),
            'latestFeedback' => Feedback::query()->approved()->latest()->limit(3)->get(),
        ]);
    }
}

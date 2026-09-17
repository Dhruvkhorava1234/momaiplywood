<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $term = trim((string) $request->input('q', ''));

        if (mb_strlen($term) < 2) {
            return response()->json([]);
        }

        $products = Product::query()
            ->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('company_name', 'like', "%{$term}%")
                    ->orWhere('sku', 'like', "%{$term}%")
                    ->orWhere('category', 'like', "%{$term}%");
            })
            ->latest()
            ->limit(10)
            ->get();

        return response()->json($products);
    }

    public function index(Request $request): View
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->input('stock_status') === 'low') {
            $query->where('stock_quantity', '>', 0)
                ->whereColumn('stock_quantity', '<=', 'min_alert_stock');
        } elseif ($request->input('stock_status') === 'out') {
            $query->where('stock_quantity', '<=', 0);
        }

        $products = $query->latest()->get();
        $categories = Product::select('category')->distinct()->pluck('category');

        return view('products.index', compact('products', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:100', 'unique:products,sku'],
            'category' => ['required', 'string', 'max:100'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'min_alert_stock' => ['nullable', 'integer', 'min:0'],
            'unit' => ['nullable', 'string', 'max:20'],
        ]);

        if (empty($validated['sku'])) {
            $validated['sku'] = 'SKU-'.strtoupper(substr(uniqid(), -6));
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product added successfully!');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:100', 'unique:products,sku,'.$product->id],
            'category' => ['required', 'string', 'max:100'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'min_alert_stock' => ['nullable', 'integer', 'min:0'],
            'unit' => ['nullable', 'string', 'max:20'],
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function adjustStock(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'adjustment_type' => ['required', 'in:add,subtract,set'],
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $qty = $validated['quantity'];
        if ($validated['adjustment_type'] === 'add') {
            $product->increment('stock_quantity', $qty);
        } elseif ($validated['adjustment_type'] === 'subtract') {
            $product->decrement('stock_quantity', min($product->stock_quantity, $qty));
        } elseif ($validated['adjustment_type'] === 'set') {
            $product->update(['stock_quantity' => $qty]);
        }

        return back()->with('success', "Stock updated for {$product->name}!");
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}

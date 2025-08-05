<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar produk berdasarkan kategori, pencarian, dan pagination.
     */
    public function index(Request $request, $category)
    {
        $search = $request->input('search');
        $query = Product::where('category', $category);

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Menggunakan paginate() untuk memecah hasil menjadi beberapa halaman (8 item per halaman)
        $products = $query->latest()->paginate(8);

        return view('products.index', compact('products', 'category', 'search'));
    }

    /**
     * Menampilkan form untuk membuat produk baru.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Menyimpan produk baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'stock' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $path = $request->file('image')->store('public/products');

        Product::create([
            'name' => $request->name,
            'category' => $request->category,
            'stock' => $request->stock,
            'image_path' => $path,
        ]);

        $categorySlug = strtolower(str_replace(' ', '-', $request->category));
        $routeName = 'products.' . ($categorySlug === 'baju-premium' ? 'premium' : ($categorySlug === 'baju-original' ? 'original' : 'accessories'));

        return redirect()->route($routeName)->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail satu produk.
     */
    public function show(Product $product)
    {
        $bookedDates = $product->rentals()->pluck('rent_date')->map(function ($date) {
            return $date->format('Y-m-d');
        })->toArray();

        return view('products.show', compact('product', 'bookedDates'));
    }

    /**
     * Menampilkan form untuk mengedit produk.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Mengupdate produk di database.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $path = $product->image_path;
        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::delete($product->image_path);
            }
            $path = $request->file('image')->store('public/products');
        }

        $product->update([
            'name' => $request->name,
            'category' => $request->category,
            'stock' => $request->stock,
            'image_path' => $path,
        ]);

        $categorySlug = strtolower(str_replace(' ', '-', $request->category));
        $routeName = 'products.' . ($categorySlug === 'baju-premium' ? 'premium' : ($categorySlug === 'baju-original' ? 'original' : 'accessories'));

        return redirect()->route($routeName)->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Menghapus produk dari database.
     */
    public function destroy(Product $product)
    {
        if ($product->image_path) {
            Storage::delete($product->image_path);
        }

        $category = $product->category;
        $product->delete();

        $categorySlug = strtolower(str_replace(' ', '-', $category));
        $routeName = 'products.' . ($categorySlug === 'baju-premium' ? 'premium' : ($categorySlug === 'baju-original' ? 'original' : 'accessories'));

        return redirect()->route($routeName)->with('success', 'Produk berhasil dihapus!');
    }
}

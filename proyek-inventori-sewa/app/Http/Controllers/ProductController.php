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
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp',
    ]);

    // INI BARIS YANG DIPERBAIKI
    $path = $request->file('image')->store('products', 'public');

    Product::create([
        'name' => $request->name,
        'category' => $request->category,
        'stock' => $request->stock,
        'image_path' => $path,
    ]);

    return redirect()->route('dashboard')->with('success', 'Produk berhasil ditambahkan!');
}

    /**
     * Menampilkan detail satu produk.
     */
    public function show(Product $product)
{
    // Eager load relasi rentals untuk efisiensi query
    $product->load('rentals');

    // Siapkan array tanggal yang sudah dibooking untuk menonaktifkan di kalender
    $bookedDates = $product->rentals->pluck('rent_date')->map(function ($date) {
        return $date->format('Y-m-d');
    })->toArray();

    // Buat objek/kamus yang memetakan tanggal ke detail penyewaan
    $rentalDetailsByDate = $product->rentals->keyBy(function ($rental) {
        return $rental->rent_date->format('Y-m-d');
    })->map(function ($rental) {
        return [
            'renter_code' => $rental->renter_code,
            'quantity' => $rental->quantity,
            'rent_date' => $rental->rent_date->format('j F Y'), // Format tanggal yang lebih mudah dibaca
        ];
    });

    // Kirim semua data yang diperlukan ke view
    return view('products.show', compact('product', 'bookedDates', 'rentalDetailsByDate'));
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
       'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp',
    ]);

    $data = $request->only(['name', 'category', 'stock']);

    if ($request->hasFile('image')) {
        // Hapus gambar lama jika ada
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        // Simpan gambar baru dan dapatkan path-nya
        // INI BARIS YANG DIPERBAIKI
        $path = $request->file('image')->store('products', 'public');
        $data['image_path'] = $path;
    }

    $product->update($data);

    return redirect()->route('dashboard')->with('success', 'Produk berhasil diperbarui!');
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

        return redirect()->route('dashboard')->with('success', 'Produk berhasil dihapus!');
    }
}

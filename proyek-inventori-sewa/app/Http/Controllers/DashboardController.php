<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
   public function index(Request $request)
{
    $search = $request->input('search');

    // Query dasar dengan filter pencarian jika ada
    $query = Product::query();
    if ($search) {
        $query->where('name', 'like', '%' . $search . '%');
    }

    // Clone query untuk setiap kategori agar filter pencarian diterapkan ke semuanya
    $premiumProducts = (clone $query)->where('category', 'Baju Premium')->latest()->take(4)->get();
    $originalProducts = (clone $query)->where('category', 'Baju Original')->latest()->take(4)->get();
    $accessoryProducts = (clone $query)->where('category', 'Aksesoris')->latest()->take(4)->get();

    return view('dashboard', [
        'premiumProducts' => $premiumProducts,
        'originalProducts' => $originalProducts,
        'accessoryProducts' => $accessoryProducts,
        'search' => $search, // Kirim variabel search ke view
    ]);
}
}

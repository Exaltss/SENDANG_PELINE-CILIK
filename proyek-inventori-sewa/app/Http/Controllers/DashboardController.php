<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $premiumProducts = Product::where('category', 'Baju Premium')->latest()->take(4)->get();
        $originalProducts = Product::where('category', 'Baju Original')->latest()->take(4)->get();
        $accessoryProducts = Product::where('category', 'Aksesoris')->latest()->take(4)->get();

        return view('dashboard', [
            'premiumProducts' => $premiumProducts,
            'originalProducts' => $originalProducts,
            'accessoryProducts' => $accessoryProducts,
        ]);
    }
}

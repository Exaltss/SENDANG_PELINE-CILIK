<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RentalController extends Controller
{
    /**
     * Menyimpan data penyewaan baru ke dalam database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'rent_date' => 'required|date_format:Y-m-d',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($request->quantity > $product->stock) {
            return back()->with('error', 'Stok tidak mencukupi untuk jumlah yang diminta.');
        }

        DB::transaction(function () use ($request, $product) {
            $product->decrement('stock', $request->quantity);
            Rental::create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'rent_date' => $request->rent_date,
            ]);
        });

        return redirect()->route('products.show', $product)->with('success', 'Penyewaan berhasil dicatat!');
    }

    /**
     * Menampilkan halaman riwayat dari semua penyewaan dengan filter dan pagination.
     */
    public function history(Request $request)
    {
        // Ambil input filter dari request
        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Mulai query dengan eager loading relasi produk
        $query = \App\Models\Rental::with('product');

        // Terapkan filter pencarian nama produk jika ada
        if ($search) {
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        // Terapkan filter rentang tanggal jika ada
        if ($startDate) {
            $query->whereDate('rent_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('rent_date', '<=', $endDate);
        }

        // Ambil hasil dengan pagination (10 item per halaman), urutkan dari yang terbaru
        $rentals = $query->latest()->paginate(10);

        // Kirim data ke view
        return view('rentals.history', compact('rentals'));
    }
}

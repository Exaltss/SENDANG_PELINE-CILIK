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
        // Validasi input dari form, termasuk renter_code yang baru
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'renter_code' => 'required|string|max:255', // <-- VALIDASI BARU
            'rent_date' => 'required|date_format:Y-m-d|unique:rentals,rent_date,NULL,id,product_id,' . $request->product_id,
        ], [
            // Pesan error kustom untuk tanggal yang sudah ada
            'rent_date.unique' => 'Tanggal ini sudah disewa untuk produk ini.',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Cek apakah stok mencukupi
        if ($request->quantity > $product->stock) {
            return back()->with('error', 'Stok tidak mencukupi untuk jumlah yang diminta.')->withInput();
        }

        // Gunakan transaksi database untuk memastikan konsistensi data
        DB::transaction(function () use ($validatedData, $product) {
            // Kurangi stok produk
            $product->decrement('stock', $validatedData['quantity']);

            // Buat entri penyewaan baru dengan data yang sudah divalidasi
            Rental::create($validatedData);
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
        $query = Rental::with('product');

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

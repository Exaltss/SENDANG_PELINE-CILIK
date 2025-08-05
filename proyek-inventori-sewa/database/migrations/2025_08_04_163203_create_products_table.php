<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Nama atau kode baju/aksesoris
        $table->string('category'); // Kategori: 'Baju Premium', 'Baju Original', 'Aksesoris'
        $table->integer('stock'); // Jumlah stok
        $table->string('image_path')->nullable(); // Path untuk menyimpan gambar
        $table->timestamps(); // Kolom created_at dan updated_at
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

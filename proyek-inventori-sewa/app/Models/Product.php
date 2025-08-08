<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Rental; // <-- TAMBAHKAN BARIS INI

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'category',
        'stock',
        'image_path',
    ];

    /**
     * Mendefinisikan relasi one-to-many ke model Rental.
     * Satu produk bisa memiliki banyak data penyewaan.
     */
    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}

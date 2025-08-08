<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['product_id', 'rent_date', 'quantity'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'rent_date' => 'date', // <-- INI PENAMBAHANNYA
    ];

    /**
     * Mendefinisikan relasi "belongsTo" ke model Product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

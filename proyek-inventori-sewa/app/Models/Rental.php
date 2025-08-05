<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    //
    public function rentals()
{
    return $this->hasMany(Rental::class);

}
    protected $fillable = ['product_id', 'rent_date', 'quantity'];
}

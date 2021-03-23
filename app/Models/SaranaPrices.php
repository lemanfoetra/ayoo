<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaranaPrices extends Model
{
    protected $table = "sarana_prices";
    protected $fillable = ['sarana_id', 'prices', 'description'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlists extends Model
{
    protected $table = "wishlists";
    protected $fillable = ['sarana_id', 'seeker_id'];
}

<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class SaranaCategories extends Model
{
    protected $table = "sarana_categories";
    protected $fillable = ['category_name'];
}

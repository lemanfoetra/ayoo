<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaranaPhotos extends Model
{
    protected $table = "sarana_images";
    protected $fillable = ['sarana_id', 'path'];
}

<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class Sarana extends Model
{
    protected $table = "saranas";
    protected $fillable = ['user_id', 'category_id', 'name', 'address', 'longitude', 'latitude', 'publish', 'step_created'];

}

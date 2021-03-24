<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sarana extends Model
{
    protected $table = "saranas";
    protected $fillable = ['user_id', 'category_id', 'name', 'address', 'longitude', 'latitude', 'publish', 'step_created'];



    /**
     * Apakah sarana ini punya saya,
     * @return true jika benar
     */
    static function isMine($idSarana): bool
    {
        $result = DB::table('saranas')
            ->where('id', $idSarana)
            ->where('user_id', auth('owner')->user()->id)
            ->get('user_id')
            ->first();
        if ($result != null) {
            return true;
        }
        return false;
    }
}

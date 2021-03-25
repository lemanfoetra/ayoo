<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Resources\Seeker\Home\CategoriesResource;
use App\Http\Resources\Seeker\Home\SaranaResource;
use App\Models\SaranaCategories;
use Illuminate\Support\Facades\DB;

class Home extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function index($search = null)
    {
        $saranas = DB::table('saranas');
        $saranas->select([
            'saranas.*',
            'sarana_categories.category_name'
        ]);
        $saranas->join('sarana_categories', 'saranas.category_id', '=', 'sarana_categories.id');

        // if have a search
        if ($search !=  null) {
            $saranas->whereRaw("(name LIKE ? OR address LIKE ? )", ["%$search%", "%$search%"]);
        }

        return SaranaResource::collection($saranas->paginate(6));
    }



    public function categories()
    {
        return CategoriesResource::collection(SaranaCategories::all());
    }
}

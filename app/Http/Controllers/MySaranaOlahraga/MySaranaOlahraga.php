<?php

namespace App\Http\Controllers\MySaranaOlahraga;

use App\Http\Controllers\Controller;
use App\Http\Resources\Resources\Owners\MySaranaOlahragaResource;
use App\Models\Sarana;
use App\Models\SaranaPhotos;
use App\Models\SaranaPrices;
use Illuminate\Support\Facades\DB;

class MySaranaOlahraga extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:owner');
    }


    public function index($search = null)
    {
        $saranas = DB::table('saranas');
        $saranas->select([
            'saranas.*',
            'sarana_categories.category_name'
        ]);
        $saranas->where('user_id', auth('owner')->user()->id);
        $saranas->join('sarana_categories', 'saranas.category_id', '=', 'sarana_categories.id');

        // if have a search
        if ($search !=  null) {
            $saranas->whereRaw("(name LIKE ? OR address LIKE ? )", ["%$search%", "%$search%"]);
        }

        return MySaranaOlahragaResource::collection($saranas->paginate(5));
    }


    /**
     * Remove my sarana
     */
    public function remove($idSarana)
    {
        if (Sarana::isMine($idSarana)) {

            // Delete Sarana
            $sarana = Sarana::find($idSarana);
            $sarana->delete();

            // Delete Photos
            $saranaPhotos = SaranaPhotos::where('sarana_id', $sarana->id)->get();
            SaranaPhotos::where('sarana_id', $sarana->id)->delete();
            if (count($saranaPhotos) > 0) {
                foreach ($saranaPhotos as $value) {
                    try {
                        unlink($value->path);
                    } catch (\Throwable $th) {
                    }
                }
            }

            // Delete Prices
            SaranaPrices::where('sarana_id', $sarana->id)->delete();

            return $this->apiResponse([], 'success', 'Success');
        }
        return $this->apiResponse([], 'error', 'This Sarana is not yours');
    }


    private function apiResponse($data = [], $status = 'success', $message = '', $statusCode = 200)
    {
        return response()->json(
            [
                'status'  => $status,
                'message' => $message,
                'data'    => $data,
            ],
            $status = $statusCode,
        );
    }
}

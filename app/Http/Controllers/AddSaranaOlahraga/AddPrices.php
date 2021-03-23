<?php

namespace App\Http\Controllers\AddSaranaOlahraga;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddSaranaOlahraga\AddPricesRequest;
use App\Models\SaranaPrices;
use Illuminate\Support\Facades\DB;

class AddPrices extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:owner');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($idSarana)
    {
        return $this->apiResponse(
            array_merge($this->getDataSarana($idSarana), ['prices' => $this->getListPrices($idSarana)]),
            'success'
        );
    }


    /**
     * Submit list prices sarana
     */
    public function store(AddPricesRequest $request, $idSarana)
    {
        // Hapus data yang lama
        SaranaPrices::where('sarana_id', $idSarana)
            ->delete();

        $data = [];
        if (count($request->prices) > 0) {
            foreach ($request->prices as $key => $value) {
                $newData = [
                    'sarana_id'     =>  $idSarana,
                    'prices'        =>  $value,
                    'description'   => ($request->description[$key] ?? null)
                ];
                $data = array_merge($data, [$newData]);
            }
            // Insert Data Baru
            SaranaPrices::insert($data);
        }

        return $this->apiResponse(
            array_merge($this->getDataSarana($idSarana), ['prices' => $this->getListPrices($idSarana)]),
            'success'
        );
    }


    /**
     * Get Data Sarana
     */
    private function getDataSarana($idSarana)
    {
        return (array)DB::table('saranas')
            ->where('id', $idSarana)
            ->get()->first();
    }


    /**
     * Get List Prices Sarana
     */
    private function getListPrices($idSarana)
    {
        return (array)DB::table('sarana_prices')
            ->where('sarana_id', $idSarana)
            ->get(['id', 'prices', 'description'])
            ->all();
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

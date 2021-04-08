<?php

namespace App\Http\Controllers\AddSaranaOlahraga;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddSaranaOlahraga\AddAdressRequest;
use App\Models\Sarana;

class AddAddress extends Controller
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
            Sarana::find($idSarana)
        );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddAdressRequest $request, $idSarana)
    {
        $message = '';
        $dataResponse = [];
        $status  = 'error';

        $sarana = Sarana::find($idSarana);
        $sarana->address = $request->address;
        $sarana->latitude = $request->latitude;
        $sarana->longitude = $request->longitude;

        if ($sarana->step_created < 2) {
            $sarana->step_created = 2;
        }

        if (Sarana::isMine($idSarana)) {
            if ($sarana->save()) {
                $message = 'Address saved';
                $dataResponse = $sarana;
                $status = 'success';
            }
        } else {
            $message = 'This sarana is not yours';
        }
        return $this->apiResponse($dataResponse, $status, $message);
    }




    private function apiResponse($data = [], $status = 'success', $message = '', $statusCode = 200)
    {
        return response()->json(
            [
                'status'  => $status,
                'message' => $message,
                'data'    => $data,
            ],
            $status = $statusCode
        );
    }
}

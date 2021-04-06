<?php

namespace App\Http\Controllers\AddSaranaOlahraga;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddSaranaOlahraga\BasicInformationRequest;
use App\Models\Sarana;
use App\Models\SaranaCategories;
use Illuminate\Support\Facades\DB;

class AddBasicInformation extends Controller
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
    public function index($idSarana = null)
    {
        $dataSarana = $this->saranaUnPublish();
        if ($idSarana != null) {
            if (Sarana::isMine($idSarana)) {
                $dataSarana = $this->getDataSarana($idSarana);
            }
        }

        return $this->apiResponse(
            [
                'sarana_id'                 => $dataSarana->id ?? null,
                'name'                      => $dataSarana->name ?? null,
                'categoreis_id_selected'    => $dataSarana->category_id ?? null,
                'categories'                => SaranaCategories::all(['id', 'category_name']),
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BasicInformationRequest $request)
    {
        $user               = auth('owner')->user();
        $responseMessage    = '';
        $responseData       = [];

        if ($request->sarana_id != null) {
            // UPDATE
            $sarana = Sarana::find($request->sarana_id);
            $sarana->name           = $request->name;
            $sarana->category_id    = $request->category_id;
            $sarana->user_id        = $user->id;
            if (Sarana::isMine($request->sarana_id)) {
                if ($sarana->save()) {
                    $responseMessage    = "Success data updated";
                    $responseData       = $sarana;
                }
            } else {
                $responseMessage = "This Sarana is not yours";
            }
        } else {
            // INSERT
            $sarana = new Sarana();
            $sarana->name           = $request->name;
            $sarana->category_id    = $request->category_id;
            $sarana->user_id        = $user->id;
            $sarana->publish        = 'D';
            $sarana->step_created   = 1;
            if ($sarana->save()) {
                $responseMessage = "Success data inserted";
                $responseData   = $sarana;
            }
        }
        return $this->apiResponse($responseData, 'success', $responseMessage);
    }


    /**
     * Get data sarana Unpublish
     */
    private function saranaUnPublish()
    {
        return DB::table('saranas')
            ->where('user_id', auth('owner')->user()->id)
            ->where('publish', 'D')
            ->first();
    }


    /**
     * Get data sarana by ID
     */
    private function getDataSarana($idSarana)
    {
        return DB::table('saranas')
            ->where('id', $idSarana)
            ->first();
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

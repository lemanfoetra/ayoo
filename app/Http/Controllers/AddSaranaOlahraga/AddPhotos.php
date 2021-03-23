<?php

namespace App\Http\Controllers\addSaranaOlahraga;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddSaranaOlahraga\AddPhotosRequest;
use App\Models\Sarana;
use App\Models\SaranaPhotos;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AddPhotos extends Controller
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
        // get data sarana
        $data = DB::table('saranas')
            ->where('id', $idSarana)
            ->get()->first();

        // get list photos
        $listPhotos = DB::table('sarana_images')
            ->where('sarana_id', $idSarana)
            ->get(['id', 'path'])
            ->all();

        return $this->apiResponse(
            array_merge((array)$data, ['photos' => (array)$listPhotos]),
            'success'
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addPhoto(AddPhotosRequest $request, $idSarana)
    {
        // get data sarana
        $data = DB::table('saranas')
            ->where('id', $idSarana)
            ->get()->first();

        // get list photos
        $listPhotos = DB::table('sarana_images')
            ->where('sarana_id', $idSarana)
            ->get(['id', 'path'])
            ->all();

        // saving file
        $file       = $request->file('image');
        $path       = 'uploads/sarana/images';
        $fileName   = Carbon::now()->timestamp . "_" . uniqid() . "." . $file->getClientOriginalExtension();
        $file->move($path, $fileName);

        // initiate saving data photos
        $saranaPhotos = new SaranaPhotos();
        $saranaPhotos->sarana_id = $idSarana;
        $saranaPhotos->path      = $path . '/' . $fileName;

        // saving data photos
        if ($saranaPhotos->save()) {

            // get new list photos
            $listPhotos = DB::table('sarana_images')
                ->where('sarana_id', $idSarana)
                ->get(['id', 'path'])
                ->all();

            return $this->apiResponse(
                array_merge((array)$data, ['photos' => (array)$listPhotos]),
                'success'
            );
        }
        return $this->apiResponse(
            array_merge((array)$data, ['photos' => (array)$listPhotos]),
            'error',
        );
    }



    /**
     * Detele Photo
     */
    public function delete($idSarana, $idPhoto)
    {
        $saranaPhotos = SaranaPhotos::find($idPhoto)->first();

        // Delete files
        try {
            unlink($saranaPhotos->path);
        } catch (\Throwable $th) {
        }

        // Delete data
        $saranaPhotos->delete();

        return $this->apiResponse([], 'success', 'Berhasil dihapus');
    }



    /**
     * Submit step add photos 
     */
    public function submitPhotos($sarana_id)
    {
        $count_photos = DB::table('sarana_images')
            ->where('sarana_id', $sarana_id)
            ->get(['id'])->all();

        if (count($count_photos) > 0) {

            $sarana = Sarana::find($sarana_id);
            $sarana->step_created = $sarana->step_created < 3
                ? 3
                : $sarana->step_created;
            if ($sarana->save()) {
                return $this->apiResponse([], 'success', 'Success');
            }
        }
        return $this->apiResponse([], 'error', 'Tidak ada photo di upload.');
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

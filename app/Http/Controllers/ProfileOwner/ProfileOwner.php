<?php

namespace App\Http\Controllers\ProfileOwner;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileOwner\PhotoProfileRequest;
use App\Http\Requests\ProfileOwner\UpdateBasicInformationRequest;
use App\Owner;
use Carbon\Carbon;

class ProfileOwner extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:owner');
    }


    public function index()
    {
        return $this->apiResponse(auth('owner')->user());
    }


    /**
     * Update basic infomation profile
     */
    public function updateBasicInformationProfile(UpdateBasicInformationRequest $request)
    {
        $me = Owner::find(auth('owner')->user()->id);
        $me->name = $request->name;
        $me->save();

        return $this->apiResponse($me);
    }


    /**
     * Update poto profile 
     */
    public function updatePhotoProfile(PhotoProfileRequest $request)
    {
        // saving file
        $file       = $request->file('photo');
        $path       = 'uploads/profiles/photos';
        $fileName   = Carbon::now()->timestamp . "_" . uniqid() . "." . $file->getClientOriginalExtension();
        $file->move($path, $fileName);

        // saving data photo
        $me = Owner::find(auth('owner')->user()->id);

        // hapus file foto lama
        try {
            unlink($me->photo);
        } catch (\Throwable $th) {}

        // save data foto baru
        $me->photo = $path . '/' . $fileName;
        $me->save();

        return $this->apiResponse($me);
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

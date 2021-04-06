<?php

namespace App\Http\Controllers\Wishlist;

use App\Http\Controllers\Controller;
use App\Http\Requests\Wishlist\AddWishlistRequest;
use App\Http\Resources\Seeker\Wishlist\Wishlist as WishlistWishlist;
use App\Http\Resources\Seeker\Wishlist\WishlistResource;
use App\Models\Wishlists;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Wishlist extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * POST insert wishtlist baru
     */
    public function store(AddWishlistRequest $request)
    {
        $wishlist = DB::table('wishlists')
            ->where('sarana_id', $request->sarana_id)
            ->where('seeker_id',  auth('api')->user()->id)
            ->first(['id']);

        if ($wishlist == null) {
            $newWishtlist = new Wishlists();
            $newWishtlist->seeker_id = auth('api')->user()->id;
            $newWishtlist->sarana_id = $request->sarana_id;
            $newWishtlist->save();
            return $this->apiResponse($newWishtlist, 'success', 'Saved');
        }
        return $this->apiResponse($wishlist, 'error', 'Sarana telah di wishlist');
    }


    /** 
     * GET my wishlist
     */
    public function index()
    {
        return WishlistResource::collection(
            DB::table('wishlists')
                ->select(['wishlists.id AS wishlist_id', 'saranas.id', 'saranas.category_id', 'saranas.name', 'saranas.address', 'saranas.latitude', 'saranas.longitude', 'saranas.updated_at'])
                ->join('saranas', 'wishlists.sarana_id', '=', 'saranas.id')
                ->where('wishlists.seeker_id', '=', auth('api')->user()->id)
                ->paginate(8)
        );
    }


    /**
     * DELETE wishlist
     */
    public function delete($idWishlist = null)
    {
        $result = DB::table('wishlists')
            ->where('id', $idWishlist)
            ->delete();

        if ($result) {
            return $this->apiResponse([], 'success', 'Deleted');
        }
        return $this->apiResponse([], 'error', 'cant delete');
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

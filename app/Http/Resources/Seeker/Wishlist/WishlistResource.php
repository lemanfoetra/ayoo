<?php

namespace App\Http\Resources\Seeker\Wishlist;

use App\Http\Resources\Resources\Owners\SaranaPricesResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class WishlistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'sarana_id'     => $this->id,
            'wishlist_id'   => $this->wishlist_id,
            'category_id'   => $this->category_id,
            'name'          => $this->name,
            'address'       => $this->address,
            'latitude'      => $this->latitude,
            'longitude'     => $this->longitude,
            'updated_at'    => $this->updated_at,
            'prices'        => SaranaPricesResource::collection(
                DB::table('sarana_prices')
                    ->select(['prices', 'description'])
                    ->where('sarana_id', $this->id)
                    ->get()->all()
            ),
        ];
    }
}

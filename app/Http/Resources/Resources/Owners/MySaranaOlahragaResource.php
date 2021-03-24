<?php

namespace App\Http\Resources\Resources\Owners;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class MySaranaOlahragaResource extends JsonResource
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
            'name'      => $this->name,
            'category_name' => $this->category_name,
            'user_id'   => $this->user_id,
            'address'   => $this->address,
            'latitude'  => $this->latitude,
            'longitude' => $this->longitude,
            'publish'   => $this->publish,
            'updated_at' => $this->updated_at,
            'prices'    => SaranaPricesResource::collection(
                DB::table('sarana_prices')
                    ->select(['prices', 'description'])
                    ->where('sarana_id', $this->id)
                    ->get()->all()
            ),
        ];
    }
}

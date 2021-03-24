<?php

namespace App\Http\Resources\Resources\Owners;

use Illuminate\Http\Resources\Json\JsonResource;

class SaranaPricesResource extends JsonResource
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
            'prices'     => $this->prices,
            'description'  => $this->description,
        ];
    }
}

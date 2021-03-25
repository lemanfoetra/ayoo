<?php

namespace App\Http\Resources\Seeker\Home;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class SaranaResource extends JsonResource
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
            'name'  => $this->name,
            'address'   => $this->address,
            'latitude'  => $this->latitude,
            'longitude' => $this->longitude,
            'category_name'  => $this->category_name,
        ];
    }
}

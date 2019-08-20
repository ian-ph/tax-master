<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class CountyCollection extends Resource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'country_code' => $this->county_code,
            'country_name' => $this->county_name,
            'country' => $this->country->country_name,
            'state' => $this->state->state_name
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class StateCollection extends Resource
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
            'state_code' => $this->state_code,
            'state_name' => $this->state_name,
            'country' => $this->country->country_name
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentTypeResource extends JsonResource
{
    public static $timestamps = false;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if(self::$timestamps){
            return $this->resource;
        }

        return [
            "code" => $this->code,
            "name" => $this->name,
            "description" => $this->description,
            "validation_rules" => json_decode($this->validation_rules)
        ];
    }
}

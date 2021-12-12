<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VCardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id' => $this->phone_number,
            'phone_number' => $this->phone_number,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'photo_url' => $this->photo_url ? $this->photo_url : '',//pq aceita nulo
            'confirmation_code' => $this->confirmation_code,
            'balance' => $this->balance,
            'blocked' => $this->blocked,
            'max_debit' => $this->max_debit,
            'custom_options' => $this->custom_options,
            'custom_data' => $this->custom_data,
        ];
    }
}

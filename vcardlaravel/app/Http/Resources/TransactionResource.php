<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'vcard_owner' => new UserResource($this->vCard),
            'vcard_dest' => new UserResource($this->pairVcard),
            'balance' => $this->balance,
            'type' => $this->type == 'C' ? "Credit" : "Debit",
            'datetime' => $this->datetime,
            'description' => $this->description,
            'category_name' => $this->category,
            'reference' => $this->payment_reference,
            'payment_type' => $this->payment_type
        ];
    }
}

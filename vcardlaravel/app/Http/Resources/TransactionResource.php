<?php

namespace App\Http\Resources;

use App\Models\Category;
use App\Models\PaymentType;
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
            'vcard_owner' => new VCardResource($this->vCard),
            'balance' => $this->balance,
            'type' => $this->type == 'C' ? "Credit" : "Debit",
            'datetime' => $this->datetime,
            'date' => $this->date,
            'description' => $this->description,
            'category_name' => $this->category?->name,
            'reference' => $this->payment_reference,
            'payment_type' => new PaymentTypeResource($this->paymentType),
            'payment_reference' => $this->payment_reference
        ];
    }
}

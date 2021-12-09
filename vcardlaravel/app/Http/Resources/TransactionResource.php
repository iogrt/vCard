<?php

namespace App\Http\Resources;

use App\Models\Category;
use App\Models\PaymentType;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public static $includeDeleted = false;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $foo = [
            'id' => $this->id,
            'value' => $this->value,
            'vcard_owner' => $this->vcard,
            'type' => $this->type,
            'datetime' => $this->datetime,
            'date' => $this->date,
            'old_balance' => $this->old_balance,
            'new_balance' => $this->new_balance,
            'description' => $this->description ?? '',
            'category_name' => $this->category ? $this->category->name : '',//pq aceita nulo, pq é a relacao
            'payment_type' => $this->paymentType->name,
            'payment_reference' => $this->payment_reference
        ];

        if(self::$includeDeleted == true){
            $foo['deleted_at'] = $this->deleted_at;
        }

        return $foo;
    }
}

<?php

namespace App\Http\Resources;

use App\Models\Vcard;
use Illuminate\Http\Resources\Json\JsonResource;

class VcardViewAdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $arr = [
            'id' => $this->id,
            'user_type' => $this->user_type,
            'email' => $this->email,
            'name' => $this->name,
            'photo_url' => $this->photo_url,
        ];

        if($this->user_type === 'V'){
            $vcard = Vcard::find($this->username);

            $arr = array_merge($arr,[
                'balance' => $vcard->balance,
                'blocked' => $vcard->blocked,
                'max_debit' => $vcard->max_debit,
                'phone_number' => $vcard->phone_number,
            ]);
        }

        return $arr;
    }
}

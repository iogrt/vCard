<?php

namespace App\Http\Resources;

use App\Models\Vcard;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $userData = [
            'id' => $this->id,
            'user_type' => $this->user_type,
            'username' => $this->username,
            'name' => $this->name,
            'email' => $this->email,
            'photo_url' => $this->photo_url
        ];
        if($this->user_type != 'V') {
         return $userData;
        }
        // vcard
        $vcard = Vcard::find($this->username);
        $arr = (new VCardResource($vcard))->toArray($request);

        return array_merge($arr,['user_type' => $this->user_type]);
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
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
            'name'              => $this->name,
            'organization'      => $this->organization,
            'lpse'              => $this->lpse,
            'name'              => $this->name,
            'email'             => $this->email,
            'photo'             => $this->photo == null ? url('images/default-profile.png') : Storage::url($this->photo),
            'phone_number'      => $this->phone_number,
            'date_of_birth'     => $this->date_of_birth,
            'is_actived'        => $this->actived,
            'role'              => $this->roles,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}

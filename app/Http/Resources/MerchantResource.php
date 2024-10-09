<?php

namespace App\Http\Resources;

use App\Helpers\Helpers;
use App\Repositories\UserRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantResource extends JsonResource
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
            'id'    => $this->id,
            'name'  => $this->name,
            'pic'   => new UserResource($this->pic),
            'phone' => $this->phone,
            'npwp'  => $this->npwp,
            'is_pkp'=> $this->is_pkp,
            'photo' => Helpers::image($this->photo ?? 'x'),
            'status'=> $this->status ? 'Actived' : 'Deactived',
            'address' => UserRepository::merchantAddress($this->id)->first(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

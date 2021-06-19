<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Company\Company as CompanyResource;
use App\Http\Resources\Role\Role as RoleResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'email' => $this->resource->email,
            'password' => $this->resource->password,
            'profile_photo_path' => $this->resource->profile_photo_path,
            'role' => RoleResource::make($this->whenLoaded('role')),
            'company' => CompanyResource::make($this->whenLoaded('company'))
        ];
    }
}

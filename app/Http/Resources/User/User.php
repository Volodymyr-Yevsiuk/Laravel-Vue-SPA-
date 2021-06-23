<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Company;
use App\Models\Role;

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
            'role' => Role::where('id', $this->resource->role_id)->first(),
            'company' => Company::where('user_id', $this->resource->id)->first(),
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at
        ];
    }
}

<?php

namespace App\Http\Resources\Company;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User\User as UserResource;

class Company extends JsonResource
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
            'name' => $this->resource->name,
            'image' => $this->resource->image,
            'description' => $this->resource->description,
            'address' => $this->resource->address,
            'employees' => $this->resource->employees,
            'user' => UserResource::make($this->whenLoaded('user')),
        ];
    }
}

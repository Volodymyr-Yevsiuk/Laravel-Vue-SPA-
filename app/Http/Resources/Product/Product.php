<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User\User as UserResource;
use App\Http\Resources\Company\Company as CompanyResource;

class Product extends JsonResource
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
            'image' => $this->resource->image,
            'description' => $this->resource->description,
            'price' => $this->resource->price,
            'company'=> CompanyResource::make($this->whenLoaded('company')),
            'user' => UserResource::make($this->whenLoaded('user')),
        ];
    }
}

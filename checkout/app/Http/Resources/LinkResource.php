<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Microservices\UserService;

class LinkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'user' => ['id' => 45, 'first_name' => 'Influencer', 'last_name' => 'Influencer'],//(new UserService())->get($this->user_id),
            'products' => ProductResource::collection($this->products),
        ];
    }
}

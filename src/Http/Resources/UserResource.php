<?php

namespace Glocurrency\ApiLayer\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array(
            'id' => $this->id,
            'codename' => $this->codename,
            'description' => $this->description,
            'enabled' => $this->enabled,
            'hasAccessTokens' => $this->hasAccessTokens(),
        );
    }
}

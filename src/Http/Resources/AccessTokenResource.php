<?php

namespace Glocurrency\ApiLayer\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccessTokenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'scopes' => $this->scopes,
            'revoked' => $this->revoked,
            'accessToken' => $this->accessToken,
            'expires_at' => $this->expires_at,
        ];

        if ($result['accessToken'] === null) {
            unset($result['accessToken']);
        }

        return $result;
    }
}

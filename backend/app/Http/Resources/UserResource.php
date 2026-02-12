<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'login' => $this->login,
            'email' => $this->email,
            'phone' => $this->phone,
            'profile' => [
                'address' => $this->address,
                'city' => $this->city,
                'country' => $this->country,
                'postal_code' => $this->postal_code,
            ],
            'avatar' => $this->avatar_url ?? $this->avatar,
            'role' => $this->role,
            'is_active' => $this->is_active,
            'email_verified' => !is_null($this->email_verified_at),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
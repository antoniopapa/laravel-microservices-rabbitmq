<?php

namespace App\Http\Resources;

use App\Models\Role;
use App\Models\UserRole;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
        $userRole = UserRole::where('user_id', $this->id)->first();
        $role = Role::find($userRole->role_id);

        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'role' => $role,
            'permissions' => $role->permissions->pluck('name'),
        ];
    }
}

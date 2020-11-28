<?php

namespace App\Http\Controllers;


use App\Http\Resources\UserResource;
use Microservices\UserService;

class AuthController
{
    public function user()
    {
        return new UserResource((new UserService)->getUser());
    }
}

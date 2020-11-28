<?php

namespace App\Http\Controllers;

use App\Http\Resources\LinkResource;
use App\Models\Link;
use Illuminate\Http\Request;
use Microservices\UserService;

class LinkController
{
    public function show($code)
    {
        $link = Link::where('code', $code)->first();

        return new LinkResource($link);
    }
}

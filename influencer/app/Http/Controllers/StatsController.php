<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Order;
use Illuminate\Http\Request;
use Microservices\UserService;

class StatsController
{
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $user = $this->userService->getUser();

        $links = Link::where('user_id', $user->id)->get();

        return $links->map(function (Link $link) {
            $orders = Order::where('code', $link->code)->get();

            return [
                'code' => $link->code,
                'count' => $orders->count(),
                'revenue' => $orders->sum(fn(Order $order) => (int)$order->total),
            ];
        });
    }

    public function rankings()
    {
        $users = collect($this->userService->all(-1));

        $users = $users->filter(function ($user) {
            return $user->is_influencer;
        });

        $rankings = $users->map(function ($user) {
            $orders = Order::where('user_id', $user->id)->get();

            return [
                'name' => $user->fullName(),
                'revenue' => $orders->sum(fn(Order $order) => (int)$order->total),
            ];
        });

        return $rankings->sortByDesc('revenue')->values();
    }
}

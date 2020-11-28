<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Microservices\UserService;

class UpdateRankingsCommand extends Command
{
    protected $signature = 'update:rankings';

    public function handle()
    {
        $userService = new UserService();

        $users = collect($userService->all(-1));

        $users = $users->filter(function ($user) {
            return $user->is_influencer;
        });

        $users->each(function ($user) {
            $orders = Order::where('user_id', $user->id)->get();
            $revenue = $orders->sum(fn (Order $order) => (int)$order->total);

            Redis::zadd('rankings', $revenue, $user->fullName());
        });
    }
}

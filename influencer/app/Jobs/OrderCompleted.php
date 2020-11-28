<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;
use Microservices\UserService;

class OrderCompleted implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $orderData;
    public $orderItemsData;

    public function __construct($orderData, $orderItemsData)
    {
        $this->orderData = $orderData;
        $this->orderItemsData = $orderItemsData;
    }

    public function handle()
    {
        $order = Order::create([
           'id' => $this->orderData['id'],
           'code' => $this->orderData['code'],
           'user_id' => $this->orderData['user_id'],
           'created_at' => $this->orderData['created_at'],
           'updated_at' => $this->orderData['updated_at'],
        ]);

        foreach ($this->orderItemsData as $item){
            $item['revenue'] = $item['influencer_revenue'];
            unset($item['influencer_revenue']);
            unset($item['admin_revenue']);
            OrderItem::create($item);
        }

        $revenue = $order->total;

        $userService = new UserService();

        $user = $userService->get($order->user_id);

        Redis::zincrby('rankings', $revenue, $user->fullName());
    }
}

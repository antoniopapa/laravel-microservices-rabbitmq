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
        $data = $this->orderData;

        Order::create([
            'id' => $data['id'],
            'code' => $data['code'],
            'transaction_id' => $data['transaction_id'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'user_id' => $data['user_id'],
            'influencer_email' => $data['influencer_email'],
            'address' => $data['address'],
            'address2' => $data['address2'],
            'city' => $data['city'],
            'country' => $data['country'],
            'zip' => $data['zip'],
            'created_at' => $data['created_at'],
            'updated_at' => $data['updated_at'],
        ]);

        foreach ($this->orderItemsData as $item) {
            $item['revenue'] = $item['admin_revenue'];
            unset($item['influencer_revenue']);
            unset($item['admin_revenue']);
            OrderItem::create($item);
        }
    }
}

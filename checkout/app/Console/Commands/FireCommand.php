<?php

namespace App\Console\Commands;

use App\Jobs\OrderCompleted;
use App\Models\Order;
use Illuminate\Console\Command;

class FireCommand extends Command
{
    protected $signature = 'fire';

    public function handle()
    {
        $order = Order::find(102);
        $data = $order->toArray();
        $data['admin_total'] = $order->admin_total;
        $data['influencer_total'] = $order->influencer_total;

        $orderItems = [];

        foreach ($order->orderItems as $item) {
            $orderItems[] = $item->toArray();
        }

        OrderCompleted::dispatch($data, $orderItems)->onQueue('influencer_queue');
        OrderCompleted::dispatch($data, $orderItems)->onQueue('admin_queue');
        OrderCompleted::dispatch($data, $orderItems)->onQueue('emails_queue');
    }
}

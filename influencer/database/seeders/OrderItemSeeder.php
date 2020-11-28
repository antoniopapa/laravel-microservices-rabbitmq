<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = \DB::connection('old_mysql')->table('order_items')->get();

        foreach ($items as $item) {
            OrderItem::create([
                'id' => $item->id,
                'order_id' => $item->order_id,
                'product_title' => $item->product_title,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'revenue' => $item->influencer_revenue,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ]);
        }
    }
}

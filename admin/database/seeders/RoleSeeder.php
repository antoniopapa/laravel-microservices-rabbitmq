<?php

namespace Database\Seeders;

use App\Models\LinkProduct;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = \DB::connection('old_mysql')->table('roles')->get();

        foreach ($items as $item) {
            Role::create([
                'id' => $item->id,
                'name' => $item->name,
            ]);
        }
    }
}

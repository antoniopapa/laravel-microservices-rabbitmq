<?php

namespace Database\Seeders;

use App\Models\LinkProduct;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = \DB::connection('old_mysql')->table('role_permission')->get();

        foreach ($items as $item) {
            \DB::table('role_permission')->insert([
                'id' => $item->id,
                'role_id' => $item->role_id,
                'permission_id' => $item->permission_id,
            ]);
        }
    }
}

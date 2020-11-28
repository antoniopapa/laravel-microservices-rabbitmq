<?php

namespace Database\Seeders;

use App\Models\LinkProduct;
use App\Models\Role;
use App\Models\UserRole;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = \DB::connection('old_mysql')->table('user_roles')->get();

        foreach ($items as $item) {
            UserRole::create([
                'id' => $item->id,
                'user_id' => $item->user_id,
                'role_id' => $item->role_id,
            ]);
        }
    }
}

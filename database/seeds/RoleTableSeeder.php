<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['name' => 'host', 'description' => 'Host Admin'],
            ['name' => 'admin', 'description' => 'Admin'],
            ['name' => 'editor', 'description' => 'Editor'],
            ['name' => 'owner', 'description' => 'Restaurant Owner'],
            ['name' => 'driver', 'description' => 'Driver'],
            ['name' => 'user', 'description' => 'User']
        ];
        DB::table('roles') -> insert($roles);
    }
}

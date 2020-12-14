<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'name' => 'category',
                'description' => 'Authorization for Creating Categories'
            ],[
                'name' => 'restaurant_add',
                'description' => 'Authorization	for Creating Restaurants'
            ],[
                'name' => 'restaurant_edit',
                'description' => 'Authorization	for Editing Restaurants'
            ],[
                'name' => 'restaurant_remove',
                'description' => 'Authorization	for Deleting Restaurants'
            ],[
                'name' => 'menu_add',
                'description' => 'Authorization for Creating Restaurants Menu and their pricing and discount price'
            ],[
                'name' => 'menu_edit',
                'description' => 'Authorization for Editing Restaurants Menu and their pricing and discount price'
            ],[
                'name' => 'menu_remove',
                'description' => 'Authorization for Deleting Restaurants Menu and their pricing and discount price'
            ],[
                'name' => 'owner_activity',
                'description' => "Authorization for restaurant owner activity"
            ],[
                'name' => 'owner_add',
                'description' => 'Authorization for Creating restaurant Owners'
            ],[
                'name' => 'owner_edit',
                'description' => 'Authorization for Editing restaurant Owners'
            ],[
                'name' => 'owner_remove',
                'description' => 'Authorization for Deleting restaurant Owners'
            ],[
                'name' => 'driver_add',
                'description' => 'Authorization for Creating Drivers'
            ],[
                'name' => 'driver_edit',
                'description' => 'Authorization for Editing Drivers'
            ],[
                'name' => 'driver_remove',
                'description' => 'Authorization for Deleting Drivers'
            ],[
                'name' => 'user_add',
                'description' => 'Authorization for Creating Users'
            ],[
                'name' => 'user_edit',
                'description' => 'Authorization for Editing Users'
            ],[
                'name' => 'user_remove',
                'description' => 'Authorization for Deleting Users'
            ],[
                'name' => 'advertisement',
                'description' => 'Authorization	for	advertising	management'
            ],[
                'name' => 'o_message',
                'description' => 'Authorization	for	sending	messages to restaurant owners'
            ],[
                'name' => 'u_message',
                'description' => 'Authorization for sending	messages to users'
            ]
        ];
        DB::table('permissions') -> insert($permissions);
    }
}

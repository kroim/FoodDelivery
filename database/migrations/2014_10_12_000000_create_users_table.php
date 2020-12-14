<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('role'); // 1: hostAdmin, 2: admin, 3: coAdmin 4: editor, 5: owner, 6: driver, 7: user
            $table->text('permissions');  // permission table
            $table->integer('state'); // 1: activate 2: deactivate
            $table->string('avatar');
            $table->string('address');
            $table->string('postcode');
            $table->string('city');
            $table->string('floor');
            $table->string('phone');
            $table->string('company');
            $table->text('description');
            $table->text('food_time');
            $table->text('service_hours');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

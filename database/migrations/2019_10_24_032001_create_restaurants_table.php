<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestaurantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('image');
            $table->text('description')->nullable();
            $table->integer('country_id');
            $table->time('service_from');
            $table->time('service_to');
            $table->integer('activation');  // 1: activation, 2: deactivation
            $table->string('special')->nullable();  // new, featured
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->float('lat');
            $table->float('long');
            $table->float('mini_order');  // Users can make order in minimum cost from this value
            $table->integer('holiday_closed_flag')->nullable();  // 0: not set 1: set, so restaurant can't be ordered
            $table->text('holiday_closed_content')->nullable();  // 0: not set 1: set, so restaurant can't be ordered
            $table->integer('owner_id'); // restaurant's owner id
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
        Schema::dropIfExists('restaurants');
    }
}

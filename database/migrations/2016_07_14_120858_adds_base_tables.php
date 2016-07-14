<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddsBaseTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('devices', function(Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('user_id'); //check relations with users
			$table->string('name');
			$table->string('url');
			$table->string('device_type');
			$table->string('methods');
			$table->timestamps();
			$table->softDeletes();

			//add indexes
			$table->foreign('user_id')->references('id')->on('users');
		});

		Schema::create('sensors', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('device_id');
			$table->string('sensor_type');
			$table->string('category'); // input (temp sensor)/output (LEDs)
			$table->string('state'); //on / off or active/not active
			$table->timestamps();
			$table->softDeletes();

			//add indexes
			$table->foreign('device_id')->references('id')->on('devices');
		});

		Schema::create('sensor_values', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('sensor_id');
			$table->integer('max_value');
			$table->integer('min_value');
			$table->integer('value');
			$table->timestamps();

			//add indexes
			$table->foreign('sensor_id')->references('id')->on('sensors');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//drop indexes
		Schema::table('devices', function (Blueprint $table) {
			$table->dropForeign('devices_user_id_foreign');
		});

		Schema::table('sensors', function (Blueprint $table) {
			$table->dropForeign('sensors_device_id_foreign');
		});

		Schema::table('sensor_values', function (Blueprint $table) {
			$table->dropForeign('sensor_values_sensor_id_foreign');
		});

		Schema::drop('devices');
		Schema::drop('sensors');
		Schema::drop('sensor_values');
	}
}

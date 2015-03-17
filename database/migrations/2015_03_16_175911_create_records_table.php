<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecordsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('records', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('user_id');
			$table->integer('category_id');
			$table->integer('shots');
			$table->integer('points');
			$table->dateTime('shot_at');
			$table->softDeletes();
			$table->timestamps();
			$table->boolean('is_public');
			$table->string('image_url');
			$table->float('shots_average');

			$table->foreign('user_id')->references('id')->on('users');
			$table->foreign('category_id')->references('id')->on('record_categories');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('records');
	}

}

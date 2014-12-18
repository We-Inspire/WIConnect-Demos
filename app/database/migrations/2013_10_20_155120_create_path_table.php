<?php

use Illuminate\Database\Migrations\Migration;

class CreatePathTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//

		Schema::create("posts", function($table) {
			$table->increments("id");
			$table->string("name");
			$table->string("author");
			$table->text("description")->nullable();
			$table->timestamps();
			$table->softDeletes();
		});		

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//

		Schema::drop("posts");
	}

}

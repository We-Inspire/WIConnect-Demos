<?php

use Illuminate\Database\Migrations\Migration;

class CreateChatTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//

		Schema::create("chat_users", function($table) {
			$table->increments("id");
			$table->string("laravel_id");
			$table->string("color");
			$table->string("name");
			$table->timestamps();
		});

		Schema::create("chats", function($table) {
			$table->increments("id");
			$table->integer("user_id")->unsigned();
			$table->text("message");
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
		Schema::dropIfExists("chat_users");
		Schema::dropIfExists("chats");
	}

}

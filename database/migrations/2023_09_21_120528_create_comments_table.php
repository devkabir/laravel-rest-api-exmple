<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
	public function up()
	{
		Schema::create('comments', function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(\App\Models\User::class);
			$table->foreignIdFor(\App\Models\Task::class);
            $table->text('comment');
			$table->timestamps();
			$table->softDeletes();
		});
	}

	public function down()
	{
		Schema::dropIfExists('comments');
	}
};

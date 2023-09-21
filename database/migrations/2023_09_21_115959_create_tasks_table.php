<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
	public function up()
	{
		Schema::create('tasks', function (Blueprint $table) {
			$table->id();
            $table->string('name');
            $table->text('description');
            $table->foreignIdFor(\App\Models\User::class, 'creator_id');
			$table->timestamps();
            $table->softDeletes();
		});
        Schema::create('task_user', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\User::class);
            $table->foreignIdFor(\App\Models\Task::class);
        });
	}

	public function down()
	{
		Schema::dropIfExists('tasks');
        Schema::dropIfExists('task_user');
	}
};

<?php

/**
 * Copyright (C) 2014 Robert Conner
 * Forked/Extended 2018 Jaye E. Miller
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLikeableTables extends Migration
{
	public function up()
	{
		Schema::create('likes', function(Blueprint $table) {
			$table->increments('id');
			$table->string('likeable_id', 36);
			$table->string('likeable_type', 255);
			$table->string('user_id', 36)->index();
			$table->timestamps();
			$table->unique(['likeable_id', 'likeable_type', 'user_id'], 'likeable_likes_unique');
		});
		
		Schema::create('likes_counters', function(Blueprint $table) {
			$table->increments('id');
			$table->string('likeable_id', 36);
			$table->string('likeable_type', 255);
			$table->unsignedInteger('count')->default(0);
			$table->unique(['likeable_id', 'likeable_type'], 'likeable_counts');
		});
		
	}

	public function down()
	{
		Schema::drop('likes');
		Schema::drop('likes_counters');
	}
}

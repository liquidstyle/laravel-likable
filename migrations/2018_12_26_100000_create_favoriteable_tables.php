<?php

/**
 * Copyright (C) 2014 Robert Conner
 * Forked/Extended 2018 Jaye E. Miller
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFavoriteableTables extends Migration
{
	public function up()
	{
		Schema::create('favorites', function(Blueprint $table) {
			$table->increments('id');
			$table->string('favoriteable_id', 36);
			$table->string('favoriteable_type', 255);
			$table->string('user_id', 36)->index();
			$table->timestamps();
			$table->unique(['favoriteable_id', 'favoriteable_type', 'user_id'], 'favoriteable_favorites_unique');
		});
		
		Schema::create('favorites_counters', function(Blueprint $table) {
			$table->increments('id');
			$table->string('favoriteable_id', 36);
			$table->string('favoriteable_type', 255);
			$table->unsignedInteger('count')->default(0);
			$table->unique(['favoriteable_id', 'favoriteable_type'], 'favoriteable_counts');
		});
		
	}

	public function down()
	{
		Schema::drop('favorites');
		Schema::drop('favorites_counters');
	}
}

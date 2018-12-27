<?php

/**
 * Copyright (C) 2014 Robert Conner
 * Forked/Extended 2018 Jaye E. Miller
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWishlistableTables extends Migration
{
	public function up()
	{
		Schema::create('wishlists', function(Blueprint $table) {
			$table->increments('id');
			$table->string('wishlistable_id', 36);
			$table->string('wishlistable_type', 255);
			$table->string('user_id', 36)->index();
			$table->timestamps();
			$table->unique(['wishlistable_id', 'wishlistable_type', 'user_id'], 'wishlistable_wishlists_unique');
		});
		
		Schema::create('wishlists_counters', function(Blueprint $table) {
			$table->increments('id');
			$table->string('wishlistable_id', 36);
			$table->string('wishlistable_type', 255);
			$table->unsignedInteger('count')->default(0);
			$table->unique(['wishlistable_id', 'wishlistable_type'], 'wishlistable_counts');
		});
		
	}

	public function down()
	{
		Schema::drop('wishlists');
		Schema::drop('wishlists_counters');
	}
}

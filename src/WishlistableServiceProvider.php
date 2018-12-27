<?php

namespace Liquidstyle\Wishlistable;

/**
 * Copyright (C) 2014 Robert Conner
 * Forked/Extended 2018 Jaye E. Miller
 */
use Illuminate\Support\ServiceProvider;

class WishlistableServiceProvider extends ServiceProvider
{
	public function boot()
	{
		$this->publishes([
			realpath(__DIR__.'/../migrations') => database_path('migrations')
		], 'migrations');
	}
	
	public function register() {}
}
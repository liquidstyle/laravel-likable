<?php

namespace Liquidstyle\Likeable;

/**
 * Copyright (C) 2014 Robert Conner
 * Forked/Extended 2018 Jaye E. Miller
 */
use Illuminate\Database\Eloquent\Model as Eloquent;

class Wishlist extends Eloquent
{
	protected $table = 'wishlistable_wishlists';
	public $timestamps = true;
	protected $fillable = ['wishlistable_id', 'wishlistable_type', 'user_id'];

	public function wishlistable()
	{
		return $this->morphTo();
	}
}
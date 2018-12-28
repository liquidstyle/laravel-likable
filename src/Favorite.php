<?php

namespace Liquidstyle\Likeable;

/**
 * Copyright (C) 2014 Robert Conner
 * Forked/Extended 2018 Jaye E. Miller
 */
use Illuminate\Database\Eloquent\Model as Eloquent;

class Favorite extends Eloquent
{
	protected $table = 'favorites';

	public $timestamps = true;

	protected $fillable = [
		'favoriteable_id',
		'favoriteable_type',
		'user_id'
	];

	public function favoriteable()
	{
		return $this->morphTo();
	}
}
<?php

namespace Liquidstyle\Likeable;

/**
 * Copyright (C) 2014 Robert Conner
 * Forked/Extended 2018 Jaye E. Miller
 */
use Illuminate\Database\Eloquent\Model as Eloquent;

class Like extends Eloquent
{
	protected $table = 'likes';

	public $timestamps = true;

	protected $fillable = [
		'likeable_id', 
		'likeable_type', 
		'user_id'
	];

	public function likeable()
	{
		return $this->morphTo();
	}
}
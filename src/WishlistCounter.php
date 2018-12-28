<?php

namespace Liquidstyle\Likeable;

/**
 * Copyright (C) 2014 Robert Conner
 * Forked/Extended 2018 Jaye E. Miller
 */
use Illuminate\Database\Eloquent\Model as Eloquent;

class WishlistCounter extends Eloquent
{
	protected $table = 'wishlists_counters';

	public $timestamps = false;

	protected $fillable = [
		'wishlistable_id', 
		'wishlistable_type', 
		'count'
	];
	
	public function wishlistable()
	{
		return $this->morphTo();
	}
	
	/**
	 * Delete all counts of the given model, and recount them and insert new counts
	 *
	 * @param string $model (should match Model::$morphClass)
	 */
	public static function rebuild($modelClass)
	{
		if(empty($modelClass)) {
			throw new \Exception('$modelClass cannot be empty/null. Maybe set the $morphClass variable on your model.');
		}
		
		$builder = Wishlist::query()
			->select(\DB::raw('count(*) as count, wishlistable_type, wishlistable_id'))
			->where('wishlistable_type', $modelClass)
			->groupBy('wishlistable_id');
		
		$results = $builder->get();
		
		$inserts = $results->toArray();
		
		\DB::table((new static)->table)->insert($inserts);
	}
	
}
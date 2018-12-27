<?php

namespace Liquidstyle\Likeable;

/**
 * Copyright (C) 2014 Robert Conner
 * Forked/Extended 2018 Jaye E. Miller
 */
use Illuminate\Database\Eloquent\Model as Eloquent;

class FavoriteCounter extends Eloquent
{
	protected $table = 'favorites_counters';

	public $timestamps = false;

	protected $fillable = [
		'favoriteable_id', 
		'favoriteable_type', 
		'count'
	];
	
	public function favoriteable()
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
		
		$builder = Favorite::query()
			->select(\DB::raw('count(*) as count, favoriteable_type, favoriteable_id'))
			->where('favoriteable_type', $modelClass)
			->groupBy('favoriteable_id');
		
		$results = $builder->get();
		
		$inserts = $results->toArray();
		
		\DB::table((new static)->table)->insert($inserts);
	}
	
}
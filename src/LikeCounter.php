<?php

namespace Liquidstyle\Likeable;

/**
 * Copyright (C) 2014 Robert Conner
 * Forked/Extended 2018 Jaye E. Miller
 */
use Illuminate\Database\Eloquent\Model as Eloquent;

class LikeCounter extends Eloquent
{
	protected $table = 'likes_counters';

	public $timestamps = false;

	protected $fillable = [
		'likeable_id', 
		'likeable_type', 
		'count'
	];
	
	public function likeable()
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
		
		$builder = Like::query()
			->select(\DB::raw('count(*) as count, likeable_type, likeable_id'))
			->where('likeable_type', $modelClass)
			->groupBy('likeable_id');
		
		$results = $builder->get();
		
		$inserts = $results->toArray();
		
		\DB::table((new static)->table)->insert($inserts);
	}
	
}
<?php

namespace Liquidstyle\Likeable;

/**
 * Copyright (C) 2014 Robert Conner
 * Forked/Extended 2018 Jaye E. Miller
 */
trait Favoriteable
{
	/**
	 * Boot the soft taggable trait for a model.
	 *
	 * @return void
	 */
	public static function bootFavoriteable()
	{
		if(static::removeFavoritesOnDelete()) {
			static::deleting(function($model) {
				$model->removeFavorites();
			});
		}
	}
	
	/**
	 * Fetch records that are favorited by a given user.
	 * Ex: Book::whereFavoritedBy(123)->get();
	 */
	public function scopeWhereFavoritedBy($query, $userId=null)
	{
		if(is_null($userId)) {
			$userId = $this->loggedInUserId();
		}
		
		return $query->whereHas('favorites', function($q) use($userId) {
			$q->where('user_id', '=', $userId);
		});
	}
	
	
	/**
	 * Populate the $model->favorites attribute
	 */
	public function getFavoriteCountAttribute()
	{
		return $this->favoriteCounter ? $this->favoriteCounter->count : 0;
	}
	
	/**
	 * Collection of the favorites on this record
	 */
	public function favorites()
	{
		return $this->morphMany(Favorite::class, 'favoriteable');
	}

	/**
	 * Counter is a record that stores the total favorites for the
	 * morphed record
	 */
	public function favoriteCounter()
	{
		return $this->morphOne(FavoriteCounter::class, 'favoriteable');
	}
	
	/**
	 * Add a favorite for this record by the given user.
	 * @param $userId mixed - If null will use currently logged in user.
	 */
	public function favorite($userId=null)
	{
		if(is_null($userId)) {
			$userId = $this->loggedInUserId();
		}
		
		if($userId) {
			$favorite = $this->favorites()
				->where('user_id', '=', $userId)
				->first();
	
			if($favorite) return;
	
			$favorite = new Favorite();
			$favorite->user_id = $userId;
			$this->favorites()->save($favorite);
		}

		$this->incrementFavoriteCount();
	}

	/**
	 * Remove a favorite from this record for the given user.
	 * @param $userId mixed - If null will use currently logged in user.
	 */
	public function unfavorite($userId=null)
	{
		if(is_null($userId)) {
			$userId = $this->loggedInUserId();
		}
		
		if($userId) {
			$favorite = $this->favorites()
				->where('user_id', '=', $userId)
				->first();
	
			if(!$favorite) { return; }
	
			$favorite->delete();
		}

		$this->decrementFavoriteCount();
	}
	
	/**
	 * Has the currently logged in user already "favorited" the current object
	 *
	 * @param string $userId
	 * @return boolean
	 */
	public function favorited($userId=null)
	{
		if(is_null($userId)) {
			$userId = $this->loggedInUserId();
		}
		
		return (bool) $this->favorites()
			->where('user_id', '=', $userId)
			->count();
	}
	
	/**
	 * Private. Increment the total favorite count stored in the counter
	 */
	private function incrementFavoriteCount()
	{
		$counter = $this->favoriteCounter()->first();
		
		if($counter) {
			$counter->count++;
			$counter->save();
		} else {
			$counter = new FavoriteCounter;
			$counter->count = 1;
			$this->favoriteCounter()->save($counter);
		}
	}
	
	/**
	 * Private. Decrement the total favorite count stored in the counter
	 */
	private function decrementFavoriteCount()
	{
		$counter = $this->favoriteCounter()->first();

		if($counter) {
			$counter->count--;
			if($counter->count) {
				$counter->save();
			} else {
				$counter->delete();
			}
		}
	}
	
	/**
	 * Fetch the primary ID of the currently logged in user
	 * @return number
	 */
	public function loggedInUserId()
	{
		return auth()->id();
	}
	
	/**
	 * Did the currently logged in user favorite this model
	 * Example : if($book->favorited) { }
	 * @return boolean
	 */
	public function getFavoritedAttribute()
	{
		return $this->favorited();
	}
	
	/**
	 * Should remove favorites on model row delete (defaults to true)
	 * public static removeFavoritesOnDelete = false;
	 */
	public static function removeFavoritesOnDelete()
	{
		return isset(static::$removeFavoritesOnDelete)
			? static::$removeFavoritesOnDelete
			: true;
	}
	
	/**
	 * Delete favorites related to the current record
	 */
	public function removeFavorites()
	{
		Favorite::where('favoriteable_type', $this->morphClass)->where('favoriteable_id', $this->id)->delete();
		
		FavoriteCounter::where('favoriteable_type', $this->morphClass)->where('favoriteable_id', $this->id)->delete();
	}
}

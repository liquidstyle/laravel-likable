<?php

namespace Liquidstyle\Likeable;

/**
 * Copyright (C) 2014 Robert Conner
 * Forked/Extended 2018 Jaye E. Miller
 */
trait Wishlistable
{
	/**
	 * Boot the soft taggable trait for a model.
	 *
	 * @return void
	 */
	public static function bootWishlistable()
	{
		if(static::removeWishlistsOnDelete()) {
			static::deleting(function($model) {
				$model->removeWishlists();
			});
		}
	}
	
	/**
	 * Fetch records that are wishlistd by a given user.
	 * Ex: Book::whereWishlistdBy(123)->get();
	 */
	public function scopeWhereWishlistdBy($query, $userId=null)
	{
		if(is_null($userId)) {
			$userId = $this->loggedInUserId();
		}
		
		return $query->whereHas('wishlists', function($q) use($userId) {
			$q->where('user_id', '=', $userId);
		});
	}
	
	
	/**
	 * Populate the $model->wishlists attribute
	 */
	public function getWishlistCountAttribute()
	{
		return $this->wishlistCounter ? $this->wishlistCounter->count : 0;
	}
	
	/**
	 * Collection of the wishlists on this record
	 */
	public function wishlists()
	{
		return $this->morphMany(Wishlist::class, 'wishlistable');
	}

	/**
	 * Counter is a record that stores the total wishlists for the
	 * morphed record
	 */
	public function wishlistCounter()
	{
		return $this->morphOne(WishlistCounter::class, 'wishlistable');
	}
	
	/**
	 * Add a wishlist for this record by the given user.
	 * @param $userId mixed - If null will use currently logged in user.
	 */
	public function wishlist($userId=null)
	{
		if(is_null($userId)) {
			$userId = $this->loggedInUserId();
		}
		
		if($userId) {
			$wishlist = $this->wishlists()
				->where('user_id', '=', $userId)
				->first();
	
			if($wishlist) return;
	
			$wishlist = new Wishlist();
			$wishlist->user_id = $userId;
			$this->wishlists()->save($wishlist);
		}

		$this->incrementWishlistCount();
	}

	/**
	 * Remove a wishlist from this record for the given user.
	 * @param $userId mixed - If null will use currently logged in user.
	 */
	public function unwishlist($userId=null)
	{
		if(is_null($userId)) {
			$userId = $this->loggedInUserId();
		}
		
		if($userId) {
			$wishlist = $this->wishlists()
				->where('user_id', '=', $userId)
				->first();
	
			if(!$wishlist) { return; }
	
			$wishlist->delete();
		}

		$this->decrementWishlistCount();
	}
	
	/**
	 * Has the currently logged in user already "wishlistd" the current object
	 *
	 * @param string $userId
	 * @return boolean
	 */
	public function wishlistd($userId=null)
	{
		if(is_null($userId)) {
			$userId = $this->loggedInUserId();
		}
		
		return (bool) $this->wishlists()
			->where('user_id', '=', $userId)
			->count();
	}
	
	/**
	 * Private. Increment the total wishlist count stored in the counter
	 */
	private function incrementWishlistCount()
	{
		$counter = $this->wishlistCounter()->first();
		
		if($counter) {
			$counter->count++;
			$counter->save();
		} else {
			$counter = new WishlistCounter;
			$counter->count = 1;
			$this->wishlistCounter()->save($counter);
		}
	}
	
	/**
	 * Private. Decrement the total wishlist count stored in the counter
	 */
	private function decrementWishlistCount()
	{
		$counter = $this->wishlistCounter()->first();

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
	 * Did the currently logged in user wishlist this model
	 * Example : if($book->wishlistd) { }
	 * @return boolean
	 */
	public function getWishlistdAttribute()
	{
		return $this->wishlistd();
	}
	
	/**
	 * Should remove wishlists on model row delete (defaults to true)
	 * public static removeWishlistsOnDelete = false;
	 */
	public static function removeWishlistsOnDelete()
	{
		return isset(static::$removeWishlistsOnDelete)
			? static::$removeWishlistsOnDelete
			: true;
	}
	
	/**
	 * Delete wishlists related to the current record
	 */
	public function removeWishlists()
	{
		Wishlist::where('wishlistable_type', $this->morphClass)->where('wishlistable_id', $this->id)->delete();
		
		WishlistCounter::where('wishlistable_type', $this->morphClass)->where('wishlistable_id', $this->id)->delete();
	}
}

!!! NOT STABLE - DO NOT USE !!!

Laravel Likeable Plugin
============

Trait for Laravel Eloquent models to allow easy implementation of a "like" or "favorite" or "remember" feature.

#### Composer Install (for Laravel 5)

	composer require liquidstyle/laravel-likeable "~2.0"

#### Install and then run the migrations


```bash
php artisan vendor:publish --provider="Liquidstyle\Likeable\LikeableServiceProvider" --tag=migrations
php artisan migrate
```

#### Setup your models

```php
class Item extends \Illuminate\Database\Eloquent\Model {
	use \Liquidstyle\Likeable\LikeableTrait;
	use \Liquidstyle\Likeable\FavoriteableTrait;
	use \Liquidstyle\Likeable\WishlistableTrait;
}
```

#### Sample Usage for LIKE

```php
$item->like(); // like the item for current user
$item->like($myUserId); // pass in your own user id
$item->like(0); // just add likes to the count, and don't track by user

$item->unlike(); // remove like from the item
$item->unlike($myUserId); // pass in your own user id
$item->unlike(0); // remove likes from the count -- does not check for user

$item->likeCount; // get count of likes

$item->likes; // Iterable Illuminate\Database\Eloquent\Collection of existing likes 

$item->liked(); // check if currently logged in user liked the item
$item->liked($myUserId);

Item::whereLikedBy($myUserId) // find only items where user liked them
	->with('likeCounter') // highly suggested to allow eager load
	->get();
```	

#### Sample Usage for FAVORITE

```php	
$item->favorite(); // favorite the item for current user
$item->favorite($myUserId); // pass in your own user id
$item->favorite(0); // just add favorites to the count, and don't track by user

$item->unfavorite(); // remove favorite from the item
$item->unfavorite($myUserId); // pass in your own user id
$item->unfavorite(0); // remove favorites from the count -- does not check for user

$item->favoriteCount; // get count of favorites

$item->favorites; // Iterable Illuminate\Database\Eloquent\Collection of existing favorites 

$item->favorited(); // check if currently logged in user favorited the item
$item->favorited($myUserId);

Item::whereFavoritedBy($myUserId) // find only items where user favorited them
	->with('favoriteCounter') // highly suggested to allow eager load
	->get();
```	

#### Sample Usage for WISHLISTR

```php	
$item->wishlist(); // wishlist the item for current user
$item->wishlist($myUserId); // pass in your own user id
$item->wishlist(0); // just add wishlists to the count, and don't track by user

$item->unwishlist(); // remove wishlist from the item
$item->unwishlist($myUserId); // pass in your own user id
$item->unwishlist(0); // remove wishlists from the count -- does not check for user

$item->wishlistCount; // get count of wishlists

$item->wishlist; // Iterable Illuminate\Database\Eloquent\Collection of existing wishlists 

$item->wishlisted(); // check if currently logged in user wishlisted the item
$item->wishlisted($myUserId);

Item::whereWishlistedBy($myUserId) // find only items where user wishlisted them
	->with('wishlistCounter') // highly suggested to allow eager load
	->get();
```	

#### Credits

 - Robert Conner - http://smartersoftware.net (Original Developer)
 - Jaye E. Miller - http://github.com/liquidstyle

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
class Article extends \Illuminate\Database\Eloquent\Model {
	use \Liquidstyle\Likeable\LikeableTrait;
	use \Liquidstyle\Likeable\FavoriteableTrait;
	use \Liquidstyle\Likeable\WishlistableTrait;
}
```

#### Sample Usage for LIKE

```php
$article->like(); // like the article for current user
$article->like($myUserId); // pass in your own user id
$article->like(0); // just add likes to the count, and don't track by user

$article->unlike(); // remove like from the article
$article->unlike($myUserId); // pass in your own user id
$article->unlike(0); // remove likes from the count -- does not check for user

$article->likeCount; // get count of likes

$article->likes; // Iterable Illuminate\Database\Eloquent\Collection of existing likes 

$article->liked(); // check if currently logged in user liked the article
$article->liked($myUserId);

Article::whereLikedBy($myUserId) // find only articles where user liked them
	->with('likeCounter') // highly suggested to allow eager load
	->get();
```	

#### Sample Usage for FAVORITE

```php	
$article->favorite(); // favorite the article for current user
$article->favorite($myUserId); // pass in your own user id
$article->favorite(0); // just add favorites to the count, and don't track by user

$article->unfavorite(); // remove favorite from the article
$article->unfavorite($myUserId); // pass in your own user id
$article->unfavorite(0); // remove favorites from the count -- does not check for user

$article->favoriteCount; // get count of favorites

$article->favorites; // Iterable Illuminate\Database\Eloquent\Collection of existing favorites 

$article->favorited(); // check if currently logged in user favorited the article
$article->favorited($myUserId);

Article::whereFavoritedBy($myUserId) // find only articles where user favorited them
	->with('favoriteCounter') // highly suggested to allow eager load
	->get();
```	

#### Sample Usage for WISHLISTR

```php	
$article->wishlist(); // wishlist the article for current user
$article->wishlist($myUserId); // pass in your own user id
$article->wishlist(0); // just add wishlists to the count, and don't track by user

$article->unwishlist(); // remove wishlist from the article
$article->unwishlist($myUserId); // pass in your own user id
$article->unwishlist(0); // remove wishlists from the count -- does not check for user

$article->wishlistCount; // get count of wishlists

$article->wishlist; // Iterable Illuminate\Database\Eloquent\Collection of existing wishlists 

$article->wishlisted(); // check if currently logged in user wishlisted the article
$article->wishlisted($myUserId);

Article::whereWishlistedBy($myUserId) // find only articles where user wishlisted them
	->with('wishlistCounter') // highly suggested to allow eager load
	->get();
```	

#### Credits

 - Robert Conner - http://smartersoftware.net (Original Developer)
 - Jaye E. Miller - http://github.com/liquidstyle

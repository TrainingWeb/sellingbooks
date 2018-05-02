<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */
Route::post('/register', 'UserController@create');
Route::post('/login', 'UserController@login')->name('login');

Route::group(['middleware' => ['auth:api', 'RedirectIfAuthenticated']], function () {
    Route::get('/admin/doanhthu', 'OrderController@Total');
    Route::resource('/admin/orders', 'OrderController');
    Route::resource('/admin/authors', 'AuthorController');
    Route::resource('/admin/books', 'BookController');
    Route::resource('/admin/categories', 'CategoryController');
    Route::get('/admin/categorycountbooks', 'CategoryController@categorywithcountbooks');
    Route::get('/admin/bookquantity', 'AdminController@bookquantity');
    Route::resource('/admin/storages', 'StorageController');
    Route::resource('/admin/tags', 'TagController');
    Route::resource('/admin/users', 'UserController');
    Route::post('/admin/book/updatequantity/{id}', 'BookController@addBookQuantity');
    Route::post('/admin/banneduser/{id}', 'AdminController@bandUser');
});

Route::group(['middleware'=> ['auth:api', 'ProtectedUserLogin']], function(){
    Route::get('/logout', 'UserController@logout')->name('logout');
    Route::get('/your-orders', 'OrderController@showOrderUser');
    Route::get('/delete-your-order/{id}', 'OrderController@deleteOrderUser');
    Route::put('/edit-your-profile', 'UserController@update');
    Route::get('/your-profile', 'UserController@show');
    Route::post('/finish-checkout', 'PageController@checkout');
    Route::post('/check-info', 'PageController@checkInfo');
    Route::post('/add-comment/{id}', 'PageController@postComment');
    Route::post('/add-favorite/{id}', 'PageController@postFavorite');
    Route::get('/get-favorite-books', 'PageController@getFavoriteBook');
    Route::get('/delete-all-your-order', 'OrderController@deleteAllOrderUser');
});

Route::get('/books{slug}', 'PageController@getBookInfo');
Route::get('/featurebooks', 'PageController@featuredBooks');
Route::get('/discountbooks', 'PageController@discountBooks');
Route::get('/newbooks', 'PageController@newBooks');
Route::post('/search', 'PageController@search');
Route::get('/authors', 'PageController@getAuthors');
Route::get('/authors/{slug}', 'PageController@getInfoAuthor');
Route::get('/categories', 'PageController@getCategoies');
Route::get('/categories/{slug}', 'PageController@getInfoCategory');
Route::resource('/home', 'PageController');


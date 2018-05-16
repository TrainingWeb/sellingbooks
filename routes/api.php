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
// Auth::routes();

Route::post('/register', 'PageController@createUser');
Route::post('/login', 'PageController@login');

Route::group(['middleware' => ['auth:api', 'RedirectIfAuthenticated']], function () {

    Route::resource('/admin/users', 'UserController');
    Route::resource('/admin/orders', 'OrderController');
    Route::resource('/admin/authors', 'AuthorController');
    Route::resource('/admin/books', 'BookController');
    Route::resource('/admin/categories', 'CategoryController');
    Route::resource('/admin/storages', 'StorageController');
    Route::resource('/admin/tags', 'TagController');

    Route::get('/admin', 'AdminController@index');
    Route::get('/admin/doanhthu', 'AdminController@Total');
    Route::get('/admin/hiddenbooks', 'AdminController@getHiddenBook');
    Route::post('/admin/editstoragequantity/{id}', 'AdminController@editStorage');
    Route::post('/admin/book/updatequantity/{id}', 'AdminController@addBookQuantity');
    Route::post('/admin/users/banneduser/{id}', 'AdminController@bandUser');
    Route::post('/admin/users/changeroleuser/{id}', 'AdminController@changeUserRole');
});

Route::group(['middleware' => ['auth:api', 'ProtectedUserLogin']], function () {
    Route::get('/your-profile', 'PageController@showInfoUser');

    Route::get('/your-orders', 'PageController@showOrderUser');
    Route::put('/edit-your-profile', 'PageController@updateUser');
    Route::get('/delete-your-order/{id}', 'PageController@deleteOrderUser');

    Route::post('/check-info', 'PageController@checkInfo');
    Route::post('/finish-checkout', 'PageController@checkout');

    Route::post('/add-comment/{slug}', 'PageController@postComment');
    Route::post('/edit-comment/{id}', 'PageController@editComment');
    Route::get('/delete-comment/{id}', 'PageController@deleteComment');

    Route::post('/add-favorite/{id}', 'PageController@postFavorite');
    Route::get('/get-favorite-books', 'PageController@getFavoriteBook');
    Route::get('/removefavorite/{id}', 'PageController@removeFavorite');
    Route::get('/test', 'PageController@test');
    Route::get('/index', 'PageController@index');
});

Route::get('/index', 'PageController@index');
Route::get('/search', 'PageController@search');
Route::get('/books/type/{type}', 'PageController@typeBooks');
Route::get('/books/{slug}', 'PageController@getBookInfo');
Route::get('/getmorecomments/{slug}', 'PageController@getMoreComments');
Route::get('/books/seemore/{slug}', 'PageController@seeMoreSameBooks');
Route::get('/authors', 'PageController@getAuthors');
Route::get('/authors/{slug}', 'PageController@getInfoAuthor');
Route::get('/categories', 'PageController@getCategoies');
Route::get('/categories/{slug}', 'PageController@getInfoCategory');
Route::get('/tags', 'PageController@getTags');
Route::get('/tags/{slug}', 'PageController@tagInfo');

Route::post('/sendmail', 'PageController@sendMailResetPassword');
Route::get('reset/password', 'PageController@testmail');
Route::post('/reset/password/{token}', 'PageController@resetPassword')->name('resetpassword');
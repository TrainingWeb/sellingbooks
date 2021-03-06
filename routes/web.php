<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
// use App\Order;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', function() {
    return view('index');  
})->name('home');
<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

// Route::get('/', function ()
// {
	// $test = new Foo\Bar;
	// return $test->derp();
	// $test = new Template\Partial;
// });
Route::get('/', 'home@index');
Route::get('/user/login/', 'user@login');

Route::get('/path/{all}/things', 'home@index')->where('all', '.*');
Route::get('{all}', 'home@index')->where('all', '.*');
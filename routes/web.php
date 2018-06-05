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

Route::get('/', function () {
    return view('welcome');
});

Route::view('scan','scan');

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/threads','ThreadController@index')->name('threads');

Route::get('/threads/create','ThreadController@create');
Route::post('/threads','ThreadController@store')->middleware('must-be-confirmed');
Route::get('threads/{channel}', 'ThreadController@index');

Route::post('locked-threads/{thread}', 'LockedThreadsController@store')->name('locked-threads.store')->middleware('admin');
Route::delete('locked-threads/{thread}', 'LockedThreadsController@destroy')->name('locked-threads.destroy')->middleware('admin');

Route::get('/thread/search', 'SearchController@show');




Route::get('/threads/{channel}/{thread}','ThreadController@show');

Route::patch('threads/{channel}/{thread}', 'ThreadController@update');
Route::delete('/threads/{channel}/{thread}','ThreadController@destroy');

Route::post('/replies/{reply}/best', 'BestRepliesController@store')->name('best-replies.store');

Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@store')->middleware('auth');
Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@destroy')->middleware('auth');

//Route::resource('threads', 'ThreadController');
Route::get('/threads/{channel}/{thread}/replies', 'ReplyController@index');
Route::post('/threads/{channel}/{thread}/replies','ReplyController@store');
Route::delete('/replies/{reply}/favorites', 'FavoritesController@destroy');
Route::patch('/replies/{reply}', 'ReplyController@update');
Route::delete('/replies/{reply}', 'ReplyController@destroy')->name('replies.destroy');
Route::post('/replies/{reply}/favorites', 'FavoritesController@store');
Route::get('/profiles/{user}','ProfilesController@show')->name('profile');
Route::get('/profiles/{user}/notifications','UserNotificationsController@index');
Route::delete('/profiles/{user}/notifications/{notification}','UserNotificationsController@destroy');

Route::get('/register/confirm', 'Auth\RegisterConfirmationController@index')->name('register.confirm');

Route::get('api/users', 'Api\UsersController@index');
Route::post('api/users/{user}/avatar', 'Api\UserAvatarController@store')->name('avatar');
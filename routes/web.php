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

// ./ngrok http -subdomain=youtube-clone 8000

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/webhook/encoding', 'EncodingWebhookController@handle');

Route::get('/videos/{video}', 'VideoController@show')->name('video.show');

Route::post('/videos/{video}/views', 'VideoViewController@create');

Route::get('/search', 'SearchController@index');

Route::get('/subscription/{channel}', 'ChannelSubscriptionController@show');

Route::get('/channel/{channel}', 'ChannelController@show');

Route::group(['middleware' => ['auth']], function() {
	Route::get('/upload', 'VideoUploadController@index')->name('video.upload');
	Route::post('/upload', 'VideoUploadController@store');

	Route::get('/videos', 'VideoController@index')->name('video.index');
	Route::get('/videos/{video}/edit', 'VideoController@edit')->name('video.edit');
	Route::post('/videos', 'VideoController@store');
	Route::put('/videos/{video}', 'VideoController@update');
	Route::delete('/videos/{video}', 'VideoController@delete');

	Route::get('/channel/{channel}/edit', 'ChannelSettingsController@edit')->name('channel.edit');
	Route::put('/channel/{channel}/edit', 'ChannelSettingsController@update')->name('channel.update');

	Route::post('/videos/{video}/comments', 'VideoCommentController@create');
	Route::delete('/videos/{video}/comments/{comment}', 'VideoCommentController@delete');

	Route::post('/subscription/{channel}', 'ChannelSubscriptionController@create');
	Route::delete('/subscription/{channel}', 'ChannelSubscriptionController@delete');
});

Route::get('/videos/{video}/comments', 'VideoCommentController@index');

Route::get('/videos/{video}/votes', 'VideoVoteController@show');
Route::post('/videos/{video}/votes', 'VideoVoteController@create');
Route::delete('/videos/{video}/votes', 'VideoVoteController@remove');
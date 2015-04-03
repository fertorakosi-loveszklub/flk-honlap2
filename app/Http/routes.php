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

Route::get('/', 'NewsController@getIndex');

// Hírek
Route::get('hir/{id}/{title}', 'NewsController@getShowNews');
Route::controller('hirek', 'NewsController');

// Rólunk
Route::controller('rolunk', 'PageController');

// Galéria
Route::get('album/{id}/{title}', 'AlbumController@getShowAlbum');
Route::controller('galeria', 'AlbumController');

// Rekordok
Route::controller('rekordok', 'RecordController');

// Felhasználó
Route::controller('felhasznalo', 'AccountController');
Route::controller('tagok', 'MemberController');

// Tagdij
Route::controller('tagdij', 'PaymentController');

// Nyomtatas
Route::controller('nyomtatas', 'PrintableController');

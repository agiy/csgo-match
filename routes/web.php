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
Route::get('/{any}', function () {
    return view('welcome');
});

Route::prefix('/api')->group(function () {
    Route::prefix('/gotv-demos')->group(function () {
        Route::get('/', 'GOTVDemoController@index');
        Route::get('/download', 'GOTVDemoController@download');
    });
});

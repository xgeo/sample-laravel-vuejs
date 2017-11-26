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

Auth::routes();

Route::get('/dashboard/product/list', 'Dashboard\ProductController@list');
Route::get('/dashboard/product/{product}/find', 'Dashboard\ProductController@find');
Route::get('/dashboard/product/list-categories', 'Dashboard\ProductController@listCategories');
Route::get('/dashboard', 'Dashboard\IndexController@index')->name('home');
Route::resource('/dashboard/product', 'Dashboard\ProductController', ['except' => ['edit', 'show']]);
Route::post('/dashboard/product/upload', 'Dashboard\ProductController@upload');

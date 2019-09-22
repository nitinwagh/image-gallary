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

Route::get('/', 'Auth\LoginController@showLoginForm');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/add-new', 'HomeController@addNew')->name('add-new');

Route::post('/add-folder', 'HomeController@addFolder')->name('add-folder');

Route::any('/add-image', 'HomeController@addImage')->name('add-image');

Route::any('/delete-image/{image_name}', 'HomeController@deleteImage')->name('delete-image');

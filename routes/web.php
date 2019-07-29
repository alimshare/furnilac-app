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

Route::get('/', 'HomeController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/employee', 'EmployeeController@list');
Route::get('/employee/new', 'EmployeeController@new');
Route::post('/employee/new', 'EmployeeController@save');
Route::get('/employee/edit/{id?}', 'EmployeeController@edit');
Route::post('/employee/edit', 'EmployeeController@update');
Route::post('/employee/delete', 'EmployeeController@delete');
Route::get('/employee/export', 'EmployeeController@export');

Route::get('/buyer', 'BuyerController@list');
Route::get('/buyer/new', 'BuyerController@new');
Route::post('/buyer/new', 'BuyerController@save');
Route::get('/buyer/edit/{id?}', 'BuyerController@edit');
Route::post('/buyer/edit', 'BuyerController@update');
Route::post('/buyer/delete', 'BuyerController@delete');
Route::get('/buyer/export', 'BuyerController@export');

Route::get('/item', 'ItemController@list');
Route::get('/item/new', 'ItemController@new');
Route::post('/item/new', 'ItemController@save');
Route::get('/item/edit/{id?}', 'ItemController@edit');
Route::post('/item/edit', 'ItemController@update');
Route::post('/item/delete', 'ItemController@delete');
Route::get('/item/export', 'ItemController@export');

Route::get('/item/{item_code}', 'ItemController@part_list');
Route::get('/item/{item_code}/new', 'ItemController@part_new');
Route::post('/item/{item_code}/new', 'ItemController@part_save');
Route::get('/item/{item_code}/edit/{part_number}', 'ItemController@part_edit');
Route::post('/item/{item_code}/edit', 'ItemController@part_update');
Route::post('/item/{item_code}/delete', 'ItemController@part_delete');

Route::get('/po', 'POController@list');
Route::get('/po/new', 'POController@new');
Route::post('/po/new', 'POController@save');
Route::get('/po/edit', 'POController@edit');
Route::post('/po/edit', 'POController@update');
Route::get('/po/export', 'POController@export');

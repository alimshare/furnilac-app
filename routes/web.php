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
Route::get('/change-password', 'HomeController@changePassword')->name('change-password');

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

Route::get('/item/{id}', 'ItemController@part_list');
Route::get('/item/{id}/new', 'ItemController@part_new');
Route::post('/item/{id}/new', 'ItemController@part_save');
Route::get('/item/{id}/edit/{part_id}', 'ItemController@part_edit');
Route::post('/item/{id}/edit', 'ItemController@part_update');
Route::post('/item/{id}/delete', 'ItemController@part_delete');

Route::get('/po', 'POController@list');
Route::get('/po/new', 'POController@new');
Route::post('/po/new', 'POController@save');
Route::get('/po/edit/{poNumber?}', 'POController@edit');
Route::post('/po/edit', 'POController@update');
Route::get('/po/export', 'POController@export');
Route::post('/po/delete', 'POController@delete');
Route::post('/po/delete/item', 'POController@deleteItem');

// Route::get('/po/production', 'POController@productionSearch');
Route::get('/po/monitor/{po_number?}', 'POController@monitor');

Route::get('/po/production', 'POController@production');
Route::post('/po/production/save', 'POController@productionSave');

Route::get('/po/mandays', 'POController@mandays');
Route::post('/po/mandays/save', 'POController@mandaysSave');

Route::get('/user', 'UserController@list');
Route::get('/user/new', 'UserController@new');
Route::post('/user/new', 'UserController@save');
Route::get('/user/edit/{id?}', 'UserController@edit');
Route::post('/user/edit', 'UserController@update');
Route::post('/user/delete', 'UserController@delete');
Route::post('/user/change-password', 'UserController@changePassword');

Route::get('/report/salary', 'ReportController@form');
Route::post('/report/salary/export', 'ReportController@export');
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

// Dashboard
Route::get('/', 'HomeController@Index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


//Categories
Route::get('/Categories', 'CategoriesController@Index');
Route::get('/Categories/Add', 'CategoriesController@Create');
Route::get('/Categories/Edit/{id}','CategoriesController@Show');
//Categories Saving
Route::post('/Categories/AddCategory', 'CategoriesController@Store');
//Route::post('/Categories/EditCategory', 'CategoriesController@Update');
Route::match(['put', 'patch'], '/Categories/EditCategory/{id}','CategoriesController@Update');

//Products
Route::get('/Products', 'ProductsController@Index');

// API
Route::get('/api/getCategories', 'APIController@getCategories')->name("api.getCategories");

// Suppliers
Route::get('/suppliers', 'SuppliersController@index')->name('suppliers');
Route::get('/suppliers/create', 'SuppliersController@create');
Route::post('suppliers/store', 'SuppliersController@store');

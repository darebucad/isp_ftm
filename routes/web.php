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
Route::match(['put', 'patch'], '/Categories/EditCategory/{id}','CategoriesController@Update');
Route::get('/Categories/Delete/{id}','CategoriesController@Destroy');

//Products
Route::get('/Products', 'ProductsController@Index');

// API
Route::get('/api/getCategories', 'APIController@getCategories')->name("api.getCategories");
Route::get('/api/getSuppliers', 'APIController@getSuppliers')->name('api.getSuppliers');
Route::get('/api/getWarehouse', 'APIController@getWarehouse')->name('api.getWarehouse');


// Suppliers
Route::get('/suppliers', 'SuppliersController@index')->name('suppliers');
Route::get('/suppliers/create', 'SuppliersController@create');
Route::post('/suppliers/store', 'SuppliersController@store');
Route::get('/suppliers/edit/{id}', 'SuppliersController@edit');
Route::post('/suppliers/update/{id}', 'SuppliersController@update');
Route::get('/suppliers/delete/{id}', 'SuppliersController@destroy');


//Warehouse
Route::get('/Warehouse', 'WarehouseController@index');
Route::get('/Warehouse/Add', 'WarehouseController@Create');
Route::get('/Warehouse/Edit/{id}','WarehouseController@Show');
//Warehouse saving
Route::post('/Warehouse/AddWarehouse', 'WarehouseController@Store');
Route::match(['put', 'patch'], '/Warehouse/EditWarehouse/{id}','WarehouseController@Update');
Route::get('/Warehouse/Delete/{id}','WarehouseController@Destroy');

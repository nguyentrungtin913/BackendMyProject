<?php

use Illuminate\Support\Facades\Route;

//social
Route::get('/get-info-facebook/{social}', 'App\Http\Controllers\SocialController@getInfo' );
Route::get('/check-info-facebook/{social}', 'App\Http\Controllers\SocialController@callback_facebook' );

//index
Route::get('/all', function (){
    return view('Admin');
})->middleware('checkLogout');
//order
Route::get('/orders','App\Http\Controllers\OrderController@index');
Route::get('/order','App\Http\Controllers\OrderController@find');
Route::put('/order','App\Http\Controllers\OrderController@updateStatus');
Route::delete('/order','App\Http\Controllers\OrderController@delete');

Route::post('/many-updates','App\Http\Controllers\OrderController@manyUpdate');
Route::post('/update','App\Http\Controllers\OrderController@Update');
Route::get('/create-order/{cartId}','App\Http\Controllers\OrderController@insert');
Route::post('/save-order','App\Http\Controllers\OrderController@save');
//Route::get('/order','App\Http\Controllers\OrderController@getByUserId');


//cart
Route::post('/cart','App\Http\Controllers\CartController@save');
Route::get('/carts','App\Http\Controllers\CartController@index');
Route::get('/cart','App\Http\Controllers\CartController@find');
Route::delete('/cart','App\Http\Controllers\CartController@delete');
//user
Route::get('/login','App\Http\Controllers\UserController@login');
Route::post('/auth','App\Http\Controllers\UserController@authenticate');
Route::get('/logout','App\Http\Controllers\UserController@logout');

Route::get('/users','App\Http\Controllers\UserController@index');
Route::post('/user','App\Http\Controllers\UserController@save');
Route::get('/user','App\Http\Controllers\UserController@find');
Route::delete('/user','App\Http\Controllers\UserController@delete');

//product
Route::get('/products','App\Http\Controllers\ProductController@index');
Route::get('/create-product','App\Http\Controllers\ProductController@insert');
Route::post('/save-product','App\Http\Controllers\ProductController@save');
Route::get('/delete-product/{productId}','App\Http\Controllers\ProductController@delete');
Route::get('/edit-product/{productId}','App\Http\Controllers\ProductController@edit');
Route::post('/update-product/{productId}','App\Http\Controllers\ProductController@update');

//productType
Route::get('/product-types','App\Http\Controllers\ProductTypeController@index');
Route::get('/create-product-type','App\Http\Controllers\ProductTypeController@insert');
Route::post('/save-product-type','App\Http\Controllers\ProductTypeController@save');
Route::get('/edit-product-type/{typeId}','App\Http\Controllers\ProductTypeController@edit');
Route::post('/update-product-type/{typeId}','App\Http\Controllers\ProductTypeController@update');
Route::get('/delete-product-type/{typeId}','App\Http\Controllers\ProductTypeController@delete');

//mockup
Route::get('/mockups','App\Http\Controllers\MockupController@index');
Route::post('/mockup','App\Http\Controllers\MockupController@save');
Route::get('/mockup/{mockupId}','App\Http\Controllers\MockupController@find');
Route::put('/mockup','App\Http\Controllers\MockupController@update');
Route::post('/test-mk','App\Http\Controllers\MockupController@test');

Route::get('/show-mockup/{mockupId}','App\Http\Controllers\MockupController@show');

Route::post('/render/{mockupId}','App\Http\Controllers\MockupController@render');
Route::get('/create-mockup','App\Http\Controllers\MockupController@insert');

Route::delete('/mockup/{mockupId}','App\Http\Controllers\MockupController@delete');
Route::post('/update-mockup/{mockupId}','App\Http\Controllers\MockupController@update');
Route::get('/image-render/','App\Http\Controllers\MockupController@imageRender');


//mockuptype

Route::get('/mockup-types','App\Http\Controllers\MockupTypeController@index');
Route::post('/mockup-type','App\Http\Controllers\MockupTypeController@save');
Route::put('/mockup-type','App\Http\Controllers\MockupTypeController@update');

Route::get('/mockup-type/{typeId}','App\Http\Controllers\MockupTypeController@find');
Route::delete('/mockup-type/{typeId}','App\Http\Controllers\MockupTypeController@delete');

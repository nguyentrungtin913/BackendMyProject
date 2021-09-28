<?php

use Illuminate\Support\Facades\Route;


//social
//fb
Route::get('/get-info-facebook/{social}', 'App\Http\Controllers\SocialController@getInfo' );
Route::get('/check-info-facebook/{social}', 'App\Http\Controllers\SocialController@callback_facebook' );
//google
Route::get('/redirect', 'App\Http\Controllers\LoginController@redirectToProvider')->name("login.provider");
Route::get('/callback', 'App\Http\Controllers\LoginController@handleProviderCallback');
Route::get('/login','App\Http\Controllers\LoginController@login');


//index
Route::get('/all', function (){
    return view('Admin');
})->middleware('checkLogout');
//order
Route::get('/orders','App\Http\Controllers\OrderController@index')->middleware('auth');
Route::get('/order','App\Http\Controllers\OrderController@find')->middleware('auth');
Route::put('/order','App\Http\Controllers\OrderController@updateStatus')->middleware('auth');
Route::delete('/order','App\Http\Controllers\OrderController@delete')->middleware('auth');
Route::post('/order','App\Http\Controllers\OrderController@save')->middleware('auth');

//order detail
Route::get('/export-order','App\Http\Controllers\OrderDetailController@exportDetailOrder')->middleware('auth');

Route::post('/many-updates','App\Http\Controllers\OrderController@manyUpdate')->middleware('auth');
Route::post('/update','App\Http\Controllers\OrderController@Update')->middleware('auth');
Route::get('/create-order/{cartId}','App\Http\Controllers\OrderController@insert')->middleware('auth');

//Route::get('/order','App\Http\Controllers\OrderController@getByUserId');


//cart
Route::post('/cart','App\Http\Controllers\CartController@save')->middleware('auth');
Route::get('/carts','App\Http\Controllers\CartController@index')->middleware('auth');
Route::get('/cart','App\Http\Controllers\CartController@find')->middleware('auth');
Route::put('/cart','App\Http\Controllers\CartController@update')->middleware('auth');
Route::delete('/cart','App\Http\Controllers\CartController@delete')->middleware('auth');
//user
    //Route::get('/login','App\Http\Controllers\UserController@login');
Route::post('/auth','App\Http\Controllers\UserController@authenticate');
Route::get('/logout','App\Http\Controllers\UserController@logout')->middleware('auth');

Route::post('/register','App\Http\Controllers\UserController@save');
Route::post('/reset-password','App\Http\Controllers\UserController@resetPassword');
Route::get('/activate-account','App\Http\Controllers\MailController@activateAccount');
Route::get('/send-otp','App\Http\Controllers\MailController@sendMail');


Route::get('/users','App\Http\Controllers\UserController@index')->middleware('auth');
Route::get('/user','App\Http\Controllers\UserController@find')->middleware('auth');
Route::delete('/user','App\Http\Controllers\UserController@delete')->middleware('auth');

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
Route::get('/mockups','App\Http\Controllers\MockupController@index')->middleware('auth');
Route::post('/mockup','App\Http\Controllers\MockupController@save')->middleware('auth');
Route::get('/mockup','App\Http\Controllers\MockupController@find')->middleware('auth');
Route::put('/mockup','App\Http\Controllers\MockupController@update')->middleware('auth');
Route::delete('/mockup','App\Http\Controllers\MockupController@delete')->middleware('auth');



Route::post('/test-mk','App\Http\Controllers\MockupController@test');

Route::get('/show-mockup/{mockupId}','App\Http\Controllers\MockupController@show');

Route::post('/render/{mockupId}','App\Http\Controllers\MockupController@render')->middleware('auth');
Route::get('/create-mockup','App\Http\Controllers\MockupController@insert');

Route::post('/update-mockup/{mockupId}','App\Http\Controllers\MockupController@update');
Route::get('/image-render/','App\Http\Controllers\MockupController@imageRender');


//mockuptype
//x
Route::get('/mockup-types','App\Http\Controllers\MockupTypeController@index')->middleware('auth');
Route::post('/mockup-type','App\Http\Controllers\MockupTypeController@save')->middleware('auth');
Route::put('/mockup-type','App\Http\Controllers\MockupTypeController@update')->middleware('auth');

Route::get('/mockup-type','App\Http\Controllers\MockupTypeController@find')->middleware('auth');
Route::delete('/mockup-type','App\Http\Controllers\MockupTypeController@delete')->middleware('auth');
